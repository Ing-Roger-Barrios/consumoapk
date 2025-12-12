<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\EquipoMaquinariaContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EquipoMaquinariaContratoController extends Controller
{
    public function index(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $equipos = $proyecto->equipoMaquinariaContrato;
        return view('equipo.contrato.index', compact('proyecto', 'equipos'));
    }

    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        return view('equipo.contrato.create', compact('proyecto'));
    }

    public function store(Request $request, Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0',
            'precio_unit' => 'required|numeric|min:0',
        ]);

        $total = $request->cantidad * $request->precio_unit;

        EquipoMaquinariaContrato::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
        ]);

        return redirect()
            ->route('equipo.contrato.index', $proyecto)
            ->with('success', 'Equipo/Maquinaria de contrato agregado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un ítem existente.
     */
    public function edit(Proyecto $proyecto, EquipoMaquinariaContrato $equipo)
    {
        $this->authorizeAccess($proyecto);
        // Verificar que el ítem pertenece al proyecto
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Ítem no pertenece al proyecto');
        }
        return view('equipo.contrato.edit', compact('proyecto', 'equipo'));
    }

    /**
     * Actualiza un ítem existente.
     */
    public function update(Request $request, Proyecto $proyecto, EquipoMaquinariaContrato $equipo)
    {
        $this->authorizeAccess($proyecto);
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Ítem no pertenece al proyecto');
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0',
            'precio_unit' => 'required|numeric|min:0',
        ]);

        $total = $request->cantidad * $request->precio_unit;

        $equipo->update([
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'total' => $total,
        ]);

        return redirect()
            ->route('equipo.contrato.index', $proyecto)
            ->with('success', 'Ítem de equipo y maquinaria actualizado exitosamente.');
    }

    /**
     * Elimina un ítem de equipo y maquinaria.
     */
    public function destroy(Proyecto $proyecto, EquipoMaquinariaContrato $equipo)
    {
        $this->authorizeAccess($proyecto);
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Ítem no pertenece al proyecto');
        }

        $equipo->delete();

        return redirect()
            ->route('equipo.contrato.index', $proyecto)
            ->with('success', 'Ítem de equipo y maquinaria eliminado exitosamente.');
    }

    // ... métodos edit, update, destroy (similares a materiales) ...
    
    private function authorizeAccess(Proyecto $proyecto)
    {
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