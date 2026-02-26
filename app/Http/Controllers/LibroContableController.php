<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\PlanillaPago;
use App\Services\LibroContableService;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibroContableController extends Controller
{
    public function __construct(
        private LibroContableService $service,
        private CloudinaryService $cloudinary
    ) {}

    // ─────────────────────────────────────────────
    // LIBRO CONTABLE: vista principal del balance
    // ─────────────────────────────────────────────
    public function index(Proyecto $proyecto)
    {
        $this->authorizeProyecto($proyecto);

        $resumen = $this->service->resumen($proyecto);

        return view('libro_contable.index', $resumen);
    }

    // ─────────────────────────────────────────────
    // PLANILLAS DE PAGO (INGRESOS)
    // ─────────────────────────────────────────────
    public function planillasIndex(Proyecto $proyecto)
    {
        $this->authorizeProyecto($proyecto);

        $planillas = $proyecto->planillasPago()->orderByDesc('fecha_pago')->get();
        $total     = $planillas->sum('monto');

        return view('libro_contable.planillas.index', compact('proyecto', 'planillas', 'total'));
    }

    public function planillasCreate(Proyecto $proyecto)
    {
        $this->authorizeProyecto($proyecto);

        return view('libro_contable.planillas.create', compact('proyecto'));
    }

    public function planillasStore(Request $request, Proyecto $proyecto)
    {
        $this->authorizeProyecto($proyecto);

        $data = $request->validate([
            'numero_planilla' => 'required|string|max:100',
            'concepto'        => 'required|string|max:255',
            'monto'           => 'required|numeric|min:0.01',
            'fecha_pago'      => 'required|date',
            'comprobante'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas'           => 'nullable|string',
        ]);

        // Upload a Cloudinary usando CloudinaryService (igual que en MaterialEjecucionController)
        if ($request->hasFile('comprobante')) {
            $data['comprobante'] = $this->cloudinary->upload(
                $request->file('comprobante'),
                'comprobantes/proyectos/' . $proyecto->id . '/planillas'
            );
        }

        $data['proyecto_id']    = $proyecto->id;
        $data['registrado_por'] = Auth::id();

        PlanillaPago::create($data);

        return redirect()
            ->route('libro.planillas.index', $proyecto)
            ->with('success', 'Planilla de pago registrada correctamente.');
    }

    public function planillasEdit(Proyecto $proyecto, PlanillaPago $planilla)
    {
        $this->authorizeProyecto($proyecto);

        return view('libro_contable.planillas.edit', compact('proyecto', 'planilla'));
    }

    public function planillasUpdate(Request $request, Proyecto $proyecto, PlanillaPago $planilla)
    {
        $this->authorizeProyecto($proyecto);

        $data = $request->validate([
            'numero_planilla' => 'required|string|max:100',
            'concepto'        => 'required|string|max:255',
            'monto'           => 'required|numeric|min:0.01',
            'fecha_pago'      => 'required|date',
            'comprobante'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas'           => 'nullable|string',
        ]);

        if ($request->hasFile('comprobante')) {
            $data['comprobante'] = $this->cloudinary->upload(
                $request->file('comprobante'),
                'comprobantes/proyectos/' . $proyecto->id . '/planillas'
            );
        }

        $planilla->update($data);

        return redirect()
            ->route('libro.planillas.index', $proyecto)
            ->with('success', 'Planilla actualizada correctamente.');
    }

    public function planillasDestroy(Proyecto $proyecto, PlanillaPago $planilla)
    {
        $this->authorizeProyecto($proyecto);

        $planilla->delete();

        return redirect()
            ->route('libro.planillas.index', $proyecto)
            ->with('success', 'Planilla eliminada.');
    }

    // ─────────────────────────────────────────────
    // Seguridad: solo el contractor dueño o sus residentes
    // ─────────────────────────────────────────────
    private function authorizeProyecto(Proyecto $proyecto): void
    {
        $user = Auth::user();

        $esContractorDueno = $user->isContractor() && $proyecto->user_id === $user->id;
        $esResidenteAsignado = $user->isResident() &&
            $proyecto->residentes->contains($user->id);

        if (!$esContractorDueno && !$esResidenteAsignado) {
            abort(403, 'No tienes acceso a este proyecto.');
        }
    }
}