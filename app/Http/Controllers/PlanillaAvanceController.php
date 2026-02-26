<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\PlanillaAvance;
use App\Models\PlanillaAvanceDetalle;
use App\Models\ManoObraItemAvance;
use Illuminate\Http\Request;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PlanillaAvanceController extends Controller
{
    // 🔹 LISTADO
    public function index(Proyecto $proyecto)
    {
        $planillas = PlanillaAvance::where('proyecto_id', $proyecto->id)
            ->latest()
            ->get();

        return view('mano-obra.planilla_avance.index', compact('proyecto', 'planillas'));
    }

    // 🔹 FORM CREAR
    public function create(Proyecto $proyecto)
    {
        return view('mano-obra.planilla_avance.create', compact('proyecto'));
    }

    // 🔥 STORE COMPLETO (GENERAR PLANILLA)
    public function store(Request $request, Proyecto $proyecto)
{
    Log::info('=== INICIO STORE PLANILLA ===');

    $request->validate([
        'semana_inicio' => 'required|date',
        'semana_fin'    => 'required|date|after_or_equal:semana_inicio',
    ]);

    $inicio = Carbon::parse($request->semana_inicio)->startOfDay();
    $fin    = Carbon::parse($request->semana_fin)->endOfDay();

    Log::info('Rango fechas:', [
        'inicio' => $inicio,
        'fin'    => $fin,
        'proyecto_id' => $proyecto->id
    ]);

    DB::beginTransaction();

    try {

        $avances = ManoObraItemAvance::whereBetween('fecha_avance', [$inicio, $fin])
            ->whereNull('planilla_avance_detalle_id')
            ->whereHas('asignacion', function ($q) use ($proyecto) {
                $q->where('proyecto_id', $proyecto->id);
            })
            ->with('asignacion.trabajador')
            ->get();

        Log::info('Cantidad avances encontrados: ' . $avances->count());

        if ($avances->isEmpty()) {
            Log::warning('No hay avances encontrados.');
            DB::rollBack();
            return redirect()
            ->back()
            ->with('error', 'No hubo avances en el rango de fechas seleccionado.');
        }

        $planilla = PlanillaAvance::create([
            'proyecto_id'   => $proyecto->id,
            'semana_inicio' => $inicio,
            'semana_fin'    => $fin,
            'total_pagar'   => 0
        ]);

        Log::info('Planilla creada ID: ' . $planilla->id);

        $totalGeneral = 0;

        $agrupados = $avances
            ->filter(fn($a) => $a->asignacion && $a->asignacion->trabajador_id)
            ->groupBy(fn($a) => $a->asignacion->trabajador_id);

        Log::info('Cantidad grupos trabajadores: ' . $agrupados->count());

        foreach ($agrupados as $trabajadorId => $lista) {

            Log::info("Procesando trabajador ID: $trabajadorId");
            Log::info("Cantidad avances trabajador: " . $lista->count());

            $totalMonto = $lista->sum('monto_pagar');
            $totalPct   = $lista->sum('porcentaje_avance');

            $detalle = PlanillaAvanceDetalle::create([
                'planilla_avance_id' => $planilla->id,
                'trabajador_id'      => $trabajadorId,
                'total_monto'        => $totalMonto,
                'total_porcentaje'   => $totalPct,
            ]);

            Log::info('Detalle creado ID: ' . $detalle->id);

            foreach ($lista as $avance) {

                Log::info('Actualizando avance ID: ' . $avance->id);

                $avance->planilla_avance_detalle_id = $detalle->id;
                $avance->save();

                Log::info('Guardado OK avance ID: ' . $avance->id);
            }

            $totalGeneral += $totalMonto;
        }

        $planilla->update([
            'total_pagar' => $totalGeneral
        ]);

        Log::info('Total general: ' . $totalGeneral);

        DB::commit();

        Log::info('=== FIN STORE OK ===');

        return redirect()
            ->route('planilla_avance.show', $planilla)
            ->with('success', 'Planilla generada correctamente.');

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('ERROR STORE PLANILLA: ' . $e->getMessage());

        return back()->with('error', 'Error al generar planilla.');
    }
}

    // 🔥 SHOW (AQUÍ VA EL LOAD)
    public function show(PlanillaAvance $planilla)
    {
        $planilla->load(
            'detalles.trabajador',
            'detalles.avances.mano_obra_item'
        );

        return view('mano-obra.planilla_avance.show', compact('planilla'));
    }

    // 🔹 SUBIR CONSTANCIA
    public function subirConstancia(
        Request $request,
        PlanillaAvance $planilla,
        CloudinaryService $cloudinary
    ) {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        if ($planilla->archivo_constancia) {
            return back()->with('error', 'Esta planilla ya fue cerrada.');
        }

        $url = $cloudinary->upload(
            $request->file('archivo'),
            'planillas_avance'
        );

        $planilla->update([
            'archivo_constancia' => $url,
        ]);

        return back()->with('success', 'Constancia subida correctamente.');
    }
}