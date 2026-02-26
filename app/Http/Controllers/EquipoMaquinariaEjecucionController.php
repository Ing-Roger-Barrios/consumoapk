<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\EquipoMaquinariaEjecucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;


class EquipoMaquinariaEjecucionController extends Controller
{
    public function index(Proyecto $proyecto, string $descripcion, string $unidad)
    {
        $this->authorizeAccess($proyecto);
        //$equipos = $proyecto->equipoMaquinariaEjecucion;
        // Filtrar los equipos asociados al proyecto que coincidan con la descripción
        $equipos = $proyecto->equipoMaquinariaEjecucion()
            ->where('descripcion', $descripcion)
            ->get();

        // Obtener la unidad desde el contrato (si existe)
        ///$unidad = $materiales->pluck('unidad')->first();;

        //return view('compramaterial', compact('proyecto', 'materiales', 'descripcion', 'unidad'));
        return view('equipo.ejecucion.index', compact('proyecto', 'equipos', 'descripcion', 'unidad'));
    }

    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        return view('equipo.ejecucion.create', compact('proyecto'));
    }

    public function store(Request $request, Proyecto $proyecto,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0',
            'precio_unit' => 'required|numeric|min:0',
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Obligatorio
        ]);

        $total = $request->cantidad * $request->precio_unit;
        $comprobantePath = null;
        //$comprobantePath = $request->file('comprobante')->store('comprobantes/equipo', 'public');

        if ($request->hasFile('comprobante')) {

                 $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/equipo/' . $proyecto->id );

            $comprobantePath = $url;
        }


        EquipoMaquinariaEjecucion::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        $descripcion = $request->descripcion;
        $unidad = $request->unidad;

        return redirect()
            ->route('equipo.ejecucion.index',  [
                                    'proyecto' => $proyecto,
                                    'descripcion' => $descripcion,
                                    'unidad' => $unidad,
                                    'comprobante'=> $comprobantePath
                                ])
            ->with('success', 'Equipo/Maquinaria ejecutado registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un equipo ejecutado existente.
     */
    public function edit(Proyecto $proyecto, EquipoMaquinariaEjecucion $equipo)
    {
        $this->authorizeAccess($proyecto);
        // Verificar que el equipo pertenece al proyecto
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Equipo no pertenece al proyecto');
        }
        return view('equipo.ejecucion.edit', compact('proyecto', 'equipo'));
    }

    /**
     * Actualiza un equipo ejecutado existente.
     */
    public function update(Request $request, Proyecto $proyecto, EquipoMaquinariaEjecucion $equipo,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($proyecto);
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Equipo no pertenece al proyecto');
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unit' => 'required|numeric|min:0.01',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $total = $request->cantidad * $request->precio_unit;
        $comprobantePath = $equipo->comprobante;

        // Si se sube un nuevo comprobante
        /*if ($request->hasFile('comprobante')) {
            // Eliminar el comprobante anterior si existe
            if ($equipo->comprobante) {
                Storage::disk('public')->delete($equipo->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/equipo', 'public');
        }*/
        if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/equipo/' . $proyecto->id );

            $comprobantePath = $url;
        }

        $equipo->update([
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);
        $descripcion = $request->descripcion;
        $unidad = $request->unidad;
        return redirect()
            ->route('equipo.ejecucion.index', [
                                    'proyecto' => $proyecto,
                                    'descripcion' => $descripcion,
                                    'unidad' => $unidad
                                ])
            ->with('success', 'Equipo/Maquinaria ejecutado actualizado exitosamente.');
    }

    /**
     * Elimina un equipo ejecutado.
     */
    public function destroy(Proyecto $proyecto, EquipoMaquinariaEjecucion $equipo)
    {
        $this->authorizeAccess($proyecto);
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Equipo no pertenece al proyecto');
        }

        // Eliminar comprobante si existe
        if ($equipo->comprobante) {
            Storage::disk('public')->delete($equipo->comprobante);
        }

        $equipo->delete();

        return redirect()
            ->route('equipo.ejecucion.index', [
                                    'proyecto' => $proyecto,
                                    'descripcion' => $equipo->descripcion,
                                    'unidad' => $equipo->unidad
                                ])
            ->with('success', 'Equipo/Maquinaria ejecutado eliminado exitosamente.');
    }


    // ... métodos edit, update, destroy (con manejo de comprobante) ...
    
    private function authorizeAccess(Proyecto $proyecto)
    {
        // Mismo método que en el otro controlador
        $user = Auth::user();
        if ($user->role === 'contractor') {
            if ($proyecto->user_id !== $user->id) {
                abort(403);
            }
        } elseif ($user->role === 'resident') {
            if (!$proyecto->residentes()->where('users.id', $user->id)->exists()) {
                abort(403);
            }
        } else {
            abort(403);
        }
    }
}