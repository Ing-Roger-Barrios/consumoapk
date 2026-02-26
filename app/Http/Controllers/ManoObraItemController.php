<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Trabajador;
use App\Models\ManoObraModulo;
use App\Models\ManoObraItem;
use App\Models\ManoObraItemAsignacion;
use App\Models\ManoObraItemAvance;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManoObraItemController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    // ─────────────────────────────────────────────
    // ASIGNACIONES (ítem → trabajador)
    // ─────────────────────────────────────────────

    public function index(Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $asignaciones = ManoObraItemAsignacion::where('proyecto_id', $proyecto->id)
            ->with(['trabajador', 'item', 'modulo', 'avances'])
            ->get();

        $trabajadores = $proyecto->trabajadoresItem;

        return view('mano-obra.item.index',
                    compact('proyecto', 'asignaciones', 'trabajadores'));
    }

    public function createAsignacion(Proyecto $proyecto, Request $request)
    {
        $this->authorize($proyecto);

        $modulos      = ManoObraModulo::where('proyecto_id', $proyecto->id)
                            ->with('items')
                            ->orderBy('orden')
                            ->get();
        $trabajadores = Trabajador::all();
        $preItemId    = $request->query('item_id');
        $preModuloId  = $request->query('modulo_id');

        return view('mano-obra.item.create_asignacion',
                    compact('proyecto', 'modulos', 'trabajadores', 'preItemId', 'preModuloId'));
    }

    public function storeAsignacion(Request $request, Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $tipo = $request->input('tipo_asignacion');

        $request->validate([
            'tipo_asignacion'   => 'required|in:item,modulo',
            'trabajador_id'     => 'required|exists:trabajadores,id',
            'monto_acordado'    => 'required|numeric|min:0',
            'notas'             => 'nullable|string',
            'mano_obra_item_id' => $tipo === 'item'   ? 'required|exists:mano_obra_items,id'   : 'nullable',
            'modulo_id'         => $tipo === 'modulo' ? 'required|exists:mano_obra_modulos,id' : 'nullable',
        ]);

        $asignacionData = [
            'proyecto_id'       => $proyecto->id,
            'tipo_asignacion'   => $tipo,
            'trabajador_id'     => $request->trabajador_id,
            'monto_acordado'    => $request->monto_acordado,
            'notas'             => $request->notas,
            'mano_obra_item_id' => $tipo === 'item'   ? $request->mano_obra_item_id : null,
            'modulo_id'         => $tipo === 'modulo' ? $request->modulo_id         : null,
        ];

        // Asegurar que el trabajador esté en el proyecto como tipo 'item'
        $proyecto->trabajadores()->syncWithoutDetaching([
            $request->trabajador_id => ['tipo' => 'item']
        ]);

        ManoObraItemAsignacion::create($asignacionData);

        return redirect()->route('mano.obra.item.index', $proyecto)
                         ->with('success', 'Ítem asignado al trabajador correctamente.');
    }

    // ─────────────────────────────────────────────
    // AVANCES (registro de avance + fotos)
    // ─────────────────────────────────────────────

    public function createAvance(Proyecto $proyecto, ManoObraItemAsignacion $asignacion)
    {
        $this->authorize($proyecto);
        $asignacion->load(['item', 'modulo.items', 'trabajador', 'avances']);

        $porcentajeAcumulado = $asignacion->porcentaje_total;

        if ($porcentajeAcumulado >= 100) {
            return redirect()->route('mano.obra.item.index', $proyecto)
                             ->with('error', 'Este ítem ya está al 100%. No se puede registrar más avance.');
        }

        $maxPorcentaje = 100 - $porcentajeAcumulado;

        // Para modo módulo: calcular avance previo por cada ítem
        $avancesPorItem = [];
        if ($asignacion->tipo_asignacion === 'modulo') {
            foreach ($asignacion->avances as $av) {
                if ($av->mano_obra_item_id) {
                    $avancesPorItem[$av->mano_obra_item_id] =
                        ($avancesPorItem[$av->mano_obra_item_id] ?? 0) + $av->porcentaje_avance;
                }
            }
        }

        return view('mano-obra.item.create_avance',
                    compact('proyecto', 'asignacion', 'porcentajeAcumulado', 'maxPorcentaje', 'avancesPorItem'));
    }

    public function storeAvance(Request $request, Proyecto $proyecto, ManoObraItemAsignacion $asignacion)
    {
        $this->authorize($proyecto);

        $request->validate(['fecha' => 'required|date']);
        $folder = "proyectos/{$proyecto->id}/mano_obra_item/{$asignacion->id}";
        $modo   = $request->input('modo', 'item');

        // ── MODO MÓDULO: un avance por ítem ───────────────────────────
        if ($modo === 'modulo') {
            $items   = $request->input('items', []);
            $guardados = 0;

            foreach ($items as $itemId => $itemData) {
                $pct = floatval($itemData['porcentaje'] ?? 0);
                if ($pct <= 0) continue; // omitir ítems sin avance

                $manoObraItem = \App\Models\ManoObraItem::find($itemId);
                if (!$manoObraItem) continue;

                // Calcular monto proporcional: monto_acordado * (parcialItem / totalModulo) * pct%
                $totalModulo = $asignacion->modulo->total_presupuestado;
                $proporcion  = $totalModulo > 0 ? ($manoObraItem->parcial / $totalModulo) : 0;
                $montoItem   = $asignacion->monto_acordado * $proporcion * ($pct / 100);

                $avanceData = [
                    'asignacion_id'     => $asignacion->id,
                    'mano_obra_item_id' => $itemId,
                    'fecha'             => $request->input('fecha'),
                    'porcentaje_avance' => $pct,
                    'monto_pagar'       => $montoItem,
                    'observaciones'     => $itemData['observaciones'] ?? null,
                    'aprobado_por'      => Auth::id(),
                    'fecha_avance' => now(), // 🔥 ESTO FALTABA
                ];

                // Subir fotos del ítem
                foreach (['foto1', 'foto2', 'foto3'] as $fotoKey) {
                    $fileKey = "items.{$itemId}.{$fotoKey}";
                    if ($request->hasFile("items.{$itemId}.{$fotoKey}")) {
                        $avanceData[$fotoKey] = $this->cloudinary->upload(
                            $request->file("items.{$itemId}.{$fotoKey}"),
                            $folder . "/{$itemId}"
                        );
                    }
                }

                ManoObraItemAvance::create($avanceData);
                $guardados++;
            }

            if ($guardados === 0) {
                return redirect()->back()->with('error', 'No registraste avance en ningún ítem. Ingresa al menos un % mayor a 0.');
            }

            return redirect()->route('mano.obra.item.index', $proyecto)
                ->with('success', "Avance del módulo registrado en {$guardados} ítem(s).");
        }

        // ── MODO ÍTEM SIMPLE ──────────────────────────────────────────
        $porcentajeAcumulado = $asignacion->porcentaje_total;
        $maxPorcentaje       = 100 - $porcentajeAcumulado;

        $data = $request->validate([
            'fecha'             => 'required|date',
            'porcentaje_avance' => "required|numeric|min:1|max:{$maxPorcentaje}",
            'foto1'             => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto2'             => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto3'             => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'observaciones'     => 'nullable|string',
        ]);

        $data['monto_pagar']   = $asignacion->monto_acordado * ($data['porcentaje_avance'] / 100);
        $data['asignacion_id'] = $asignacion->id;
        $data['aprobado_por']  = Auth::id();

        foreach (['foto1', 'foto2', 'foto3'] as $foto) {
            if ($request->hasFile($foto)) {
                $data[$foto] = $this->cloudinary->upload($request->file($foto), $folder);
            } else {
                unset($data[$foto]);
            }
        }

        ManoObraItemAvance::create($data);

        return redirect()->route('mano.obra.item.index', $proyecto)
            ->with('success', "Avance de {$data['porcentaje_avance']}% registrado correctamente.");
    }

    public function showAsignacion(Proyecto $proyecto, ManoObraItemAsignacion $asignacion)
    {
        $this->authorize($proyecto);
        $asignacion->load(['trabajador', 'item.modulo', 'modulo', 'avances.aprobadoPor']);

        return view('mano-obra.item.show_asignacion', compact('proyecto', 'asignacion'));
    }

    public function destroyAvance(Proyecto $proyecto, ManoObraItemAvance $avance)
    {
        $this->authorize($proyecto);
        $avance->delete();

        return back()->with('success', 'Avance eliminado.');
    }

    // ─────────────────────────────────────────────
    private function authorize(Proyecto $proyecto): void
    {
        $user = Auth::user();
        $esOwner    = $user->isContractor() && $proyecto->user_id === $user->id;
        $esResident = $user->isResident() && $proyecto->residentes->contains($user->id);

        if (!$esOwner && !$esResident) {
            abort(403);
        }
    }
}