<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;
use App\Models\MaterialEjecucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialEjecucionController extends Controller
{
    /**
     * Mostrar el formulario para crear un material en ejecución.
     */
    public function create(Proyecto $proyecto)
    {
        // Solo el contractor dueño del proyecto puede acceder
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

        return view('materiales.ejecucion.create', compact('proyecto'));
    }

    /**
     * Guardar un nuevo material en ejecución.
     */
    public function store(Request $request, Proyecto $proyecto)
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

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0',
            'precio_unit' => 'required|numeric|min:0',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // hasta 5MB
        ], [
            'comprobante.mimes' => 'El comprobante debe ser una imagen (jpg, png) o PDF.',
            'comprobante.max' => 'El comprobante no debe superar los 5 MB.',
        ]);

        // Calcular total
        $total = $request->cantidad * $request->precio_unit;

        // Manejar subida de comprobante
        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            // Guardar en storage/app/public/comprobantes/proyectos/{id}/
            $comprobantePath = $request->file('comprobante')->store('comprobantes/proyectos/' . $proyecto->id, 'public');
        }

        MaterialEjecucion::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
            'comprobante' => $comprobantePath, // guarda la ruta relativa
        ]);
        $descripcion = $request->descripcion;
        $unidad = $request->unidad;

        return redirect()->route('mat.compra', [
                                    'proyecto' => $proyecto,
                                    'descripcion' => $descripcion,
                                    'unidad' => $unidad
                                ])->with('success', 'Material en ejecución registrado exitosamente.');
    }

    public function edit(Proyecto $proyecto, MaterialEjecucion $material)
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
        // Verificar que el material pertenece al proyecto
        if ($material->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        return view('materiales.ejecucion.edit', compact('proyecto', 'material'));
    }

    /**
     * Actualizar material existente.
     */
    public function update(Request $request, Proyecto $proyecto, MaterialEjecucion $material)
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
        if ($material->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0',
            'precio_unit' => 'required|numeric|min:0',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $total = $request->cantidad * $request->precio_unit;
        $comprobantePath = $material->comprobante; // mantener el existente

        // Si se sube un nuevo comprobante
        if ($request->hasFile('comprobante')) {
            // Eliminar el anterior si existe
            if ($material->comprobante) {
                Storage::disk('public')->delete($material->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/proyectos/' . $proyecto->id, 'public');
        }

        $material->update([
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
            'comprobante' => $comprobantePath,
        ]);

        $descripcion = $request->descripcion;
        $unidad = $request->unidad;
        return redirect()->route('mat.compra', [
                                    'proyecto' => $proyecto,
                                    'descripcion' => $descripcion,
                                    'unidad' => $unidad
                                ])->with('success', 'Material en ejecución Actualizado exitosamente.');
    }

    /**
     * Eliminar material.
     */
    public function destroy(Proyecto $proyecto, MaterialEjecucion $material)
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

        // Eliminar archivo si existe
        if ($material->comprobante) {
            Storage::disk('public')->delete($material->comprobante);
        }

        $material->delete();

        return redirect()->route('mat.compra', [
                                    'proyecto' => $proyecto,
                                    'descripcion' => $material->descripcion,
                                    'unidad' => $material->unidad
                                ])->with('success', 'Material eliminado exitosamente');
    }









    /**
     * Listar todos los materiales en ejecución de un proyecto.
     */
    public function index(Proyecto $proyecto)
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

        $materiales = $proyecto->materialesEjecucion;
        return view('materiales.ejecucion.index', compact('proyecto', 'materiales'));
    }

    /**
     * Helper: verificar acceso del contractor.
     */
    private function authorizeAccess(Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
    }
}