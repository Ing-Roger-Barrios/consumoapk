<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class CompraMaterialsController extends Controller
{
    public function compmat(Proyecto $proyecto, string $descripcion, string $unidad) 
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

        // Filtrar los materiales asociados al proyecto que coincidan con la descripción
        $materiales = $proyecto->materialesEjecucion()
            ->where('descripcion', $descripcion)
            ->get();

        // Obtener la unidad desde el contrato (si existe)
        ///$unidad = $materiales->pluck('unidad')->first();;

        return view('compramaterial', compact('proyecto', 'materiales', 'descripcion', 'unidad'));

    }
}
