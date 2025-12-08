<?php

namespace App\Http\Controllers;

use App\Models\ManoObraContrato;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;




class ManoObraContratoController extends Controller
{
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

        $manoObra  = $proyecto->manoObraContrato;
        return view('mano-obra/contrato/index', compact('proyecto', 'manoObra'));
    }
     // Método para mostrar el formulario de creación
    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        return view('mano-obra.contrato.create', compact('proyecto'));
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

        $monto_presupuestado = $request->cantidad * $request->precio_unit;

        ManoObraContrato::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'monto_presupuestado' => $monto_presupuestado,
        ]);

        return redirect()
            ->route('mano.obra.contrato.index', $proyecto)
            ->with('success', 'Ítem de mano de obra agregado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un ítem existente.
     */
    public function edit(Proyecto $proyecto, ManoObraContrato $manoObraContrato)
    {
        $this->authorizeAccess($proyecto);
        // Verificar que el ítem pertenece al proyecto
        if ($manoObraContrato->proyecto_id !== $proyecto->id) {
            abort(403, 'Ítem no pertenece al proyecto');
        }
        return view('mano-obra.contrato.edit', compact('proyecto', 'manoObraContrato'));
    }

    public function update(Request $request, Proyecto $proyecto, ManoObraContrato $manoObraContrato)
    {
        $this->authorizeAccess($proyecto);
        if ($manoObraContrato->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'cantidad' => 'required|numeric|min:0',
            'precio_unit' => 'required|numeric|min:0',
        ]);

        $monto_presupuestado = $request->cantidad * $request->precio_unit;

        $manoObraContrato->update([
            'descripcion' => $request->descripcion,
            'unidad' => $request->unidad,
            'cantidad' => $request->cantidad,
            'precio_unit' => $request->precio_unit,
            'monto_presupuestado' => $monto_presupuestado,
        ]);

        return redirect()
            ->route('mano.obra.contrato.index', $proyecto)
            ->with('success', 'Ítem de mano de obra actualizado exitosamente.');
    }
    private function authorizeAccess(Proyecto $proyecto)
    {
        if ($proyecto->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
    }
}
