<?php

// app/Http/Controllers/MaterialContratoController.php
namespace App\Http\Controllers;

use App\Imports\MaterialesContratoImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;
use App\Models\MaterialContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use import;

class MaterialContratoController extends Controller
{
    public function create(Proyecto $proyecto)
    {
        // Solo el dueño
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
        return view('materiales.contrato.create', compact('proyecto'));
    }

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
        ]);

        $total = $request->cantidad * $request->precio_unit;

        MaterialContrato::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
        ]);

        return redirect()->route('mat.list', $proyecto)->with('success', 'Material de contrato agregado.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Proyecto $proyecto, MaterialContrato $material)
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

        return view('materiales.contrato.edit', compact('proyecto', 'material'));
    }

    /**
     * Actualizar material de contrato existente.
     */
    public function update(Request $request, Proyecto $proyecto, MaterialContrato $material)
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
        ]);

        $total = $request->cantidad * $request->precio_unit;

        $material->update([
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
        ]);

        return redirect()
            ->route('materiales.contrato.index', $proyecto)
            ->with('success', 'Material de contrato actualizado exitosamente.');
    }

    /**
     * Eliminar material de contrato.
     */
    public function destroy(Proyecto $proyecto, MaterialContrato $material)
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

        $material->delete();

        return redirect()
            ->route('materiales.contrato.index', $proyecto)
            ->with('success', 'Material de contrato eliminado exitosamente.');
    }

    /**
     * Listar materiales de contrato.
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

        $materiales = $proyecto->materialesContrato;
        return view('materiales.contrato.index', compact('proyecto', 'materiales'));
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

    public function import(Request $request, Proyecto $proyecto)
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
            'archivo_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Crear instancia del importador
            $import = new MaterialesContratoImport($proyecto->id);
            
            // Importar usando el facade Excel (esto llama automáticamente toCollection)
            Excel::import($import, $request->file('archivo_excel'));

            $message = "Importación completada: {$import->importedCount} materiales importados, {$import->skippedCount} duplicados saltados.";
            
            return redirect()
                ->route('materiales.contrato.index', $proyecto)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error importing Excel: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors(['error' => 'Error al importar el archivo. Verifique el formato.']);
        }
    }
}
