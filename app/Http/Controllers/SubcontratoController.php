<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Subcontrato;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;

class SubcontratoController extends Controller
{
    public function index(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $subcontratos = $proyecto->subcontratos;
        return view('subcontratos.index', compact('proyecto', 'subcontratos'));
    }

    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        return view('subcontratos.create', compact('proyecto'));
    }

    public function store(Request $request, Proyecto $proyecto,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'monto_acordado' => 'required|numeric|min:0',
            'contrato' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // hasta 10MB
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = null;
        /*if ($request->hasFile('contrato')) {
            $contratoPath = $request->file('contrato')->store('contratos/subcontratos'. $proyecto->id, 'public');
            
        }*/
        if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/proyectos/' . $proyecto->id );

            $comprobantePath = $url;
        }

        Subcontrato::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'monto_acordado' => $request->monto_acordado,
            'contrato' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('subcontratos.index', $proyecto)
            ->with('success', 'Subcontrato registrado exitosamente.');
    }

public function update(Request $request, Proyecto $proyecto, Subcontrato $subcontrato,  CloudinaryService $cloudinary)
{
    $this->authorizeAccess($proyecto);
    if ($subcontrato->proyecto_id !== $proyecto->id) {
        abort(403);
    }

    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string|max:255',
        'monto_acordado' => 'required|numeric|min:0',
        'contrato' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'notas' => 'nullable|string|max:500',
    ]);

    $comprobantePath = $subcontrato->contrato; // Mantener el existente
    
    // Si se sube un nuevo contrato
    /*if ($request->hasFile('contrato')) {
        // Eliminar el anterior si existe
        if ($subcontrato->contrato) {
            Storage::disk('public')->delete($subcontrato->contrato);
        }
        $contratoPath = $request->file('contrato')->store('contratos/subcontratos', 'public');
    }*/
    if ($request->hasFile('comprobante')) {

        $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/subcontratos/' . $proyecto->id );

        $comprobantePath = $url;
    }

    $subcontrato->update([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'monto_acordado' => $request->monto_acordado,
        'contrato' => $comprobantePath,
        'notas' => $request->notas,
    ]);

    return redirect()
        ->route('subcontratos.index', $proyecto)
        ->with('success', 'Subcontrato actualizado exitosamente.');
}

    public function edit(Proyecto $proyecto, Subcontrato $subcontrato)
    {
        $this->authorizeAccess($proyecto);
        if ($subcontrato->proyecto_id !== $proyecto->id) {
            abort(403);
        }
        return view('subcontratos.edit', compact('proyecto', 'subcontrato'));
    }

    

    public function destroy(Proyecto $proyecto, Subcontrato $subcontrato)
    {
        $this->authorizeAccess($proyecto);
        if ($subcontrato->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        if ($subcontrato->pagos()->count() > 0) {
            return redirect()
                ->back()
                ->withErrors(['No se puede eliminar: ya existen pagos registrados para este subcontrato.']);
        }

        // Eliminar archivo de contrato si existe
        if ($subcontrato->contrato) {
            Storage::disk('public')->delete($subcontrato->contrato);
        }

        $subcontrato->delete();

        return redirect()
            ->route('subcontratos.index', $proyecto)
            ->with('success', 'Subcontrato eliminado exitosamente.');
    }

    private function authorizeAccess(Proyecto $proyecto)
    {
        $user = Auth::user();
        // Verificar si el usuario tiene acceso al proyecto
        if ($user->role === 'contractor') {
            // Solo puede ver sus propios proyectos
            if ($proyecto->user_id !== $user->id) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } elseif ($user->role === 'resident') {
            // Solo puede ver proyectos en los que está asignado
            if (!$proyecto->residentes()->where('users.id', $user->id)->exists()) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } else {
            abort(403, 'Acceso denegado');
        }
    }
}