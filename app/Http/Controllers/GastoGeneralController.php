<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\GastoGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;

class GastoGeneralController extends Controller
{
    public function index(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $gastos = $proyecto->gastosGenerales()->latest()->get();
        return view('gastos.index', compact('proyecto', 'gastos'));
    }

    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $categorias = GastoGeneral::categorias();
        return view('gastos.create', compact('proyecto', 'categorias'));
    }

    public function store(Request $request, Proyecto $proyecto,  CloudinaryService $cloudinary )
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0.01',
            'fecha_gasto' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = null;
        /*if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes/gastos', 'public');
        }*/
        if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/gastos/' . $proyecto->id );

            $comprobantePath = $url;
        }

        GastoGeneral::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'monto' => $request->monto,
            'fecha_gasto' => $request->fecha_gasto,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('gastos.index', $proyecto)
            ->with('success', 'Gasto general registrado exitosamente.');
    }

    public function edit(Proyecto $proyecto, GastoGeneral $gasto)
    {
        $this->authorizeAccess($proyecto);
        if ($gasto->proyecto_id !== $proyecto->id) {
            abort(403);
        }
        $categorias = GastoGeneral::categorias();
        return view('gastos.edit', compact('proyecto', 'gasto', 'categorias'));
    }

    public function update(Request $request, Proyecto $proyecto, GastoGeneral $gasto,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($proyecto);
        if ($gasto->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0.01',
            'fecha_gasto' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = $gasto->comprobante;
        /*if ($request->hasFile('comprobante')) {
            if ($gasto->comprobante) {
                Storage::disk('public')->delete($gasto->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/gastos', 'public');
        }*/
        if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/gastos/' . $proyecto->id );

            $comprobantePath = $url;
        }

        $gasto->update([
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'monto' => $request->monto,
            'fecha_gasto' => $request->fecha_gasto,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('gastos.index', $proyecto)
            ->with('success', 'Gasto general actualizado exitosamente.');
    }

    public function destroy(Proyecto $proyecto, GastoGeneral $gasto)
    {
        $this->authorizeAccess($proyecto);
        if ($gasto->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        if ($gasto->comprobante) {
            Storage::disk('public')->delete($gasto->comprobante);
        }

        $gasto->delete();

        return redirect()
            ->route('gastos.index', $proyecto)
            ->with('success', 'Gasto general eliminado exitosamente.');
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