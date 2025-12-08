<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;

class ListMaterialsController extends Controller
{
    public function listmat(Proyecto $proyecto) 
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

        // Materiales de contrato: 1 por descripción
        $contrato = $proyecto->materialesContrato->keyBy('descripcion');

        // Materiales en ejecución: agrupar y sumar por descripción
        $ejecucionAgrupada = $proyecto->materialesEjecucion
            ->groupBy('descripcion')
            ->map(function ($items, $descripcion) {
                $first = $items->first();
                return [
                    'descripcion' => $descripcion,
                    'unidad' => $first->unidad,
                    'cantidad' => $items->sum('cantidad'),
                    'precio_promedio' => $items->sum('total') / ($items->sum('cantidad') ?: 1), // promedio ponderado
                    'total' => $items->sum('total'),
                    'compras' => $items->count(), // opcional: cantidad de facturas
                ];
            });

        // Todas las descripciones (de contrato + ejecución)
        $descripciones = $contrato->keys()
            ->merge($ejecucionAgrupada->keys())
            ->unique()
            ->sort();

        $comparacion = $descripciones->map(function ($desc) use ($contrato, $ejecucionAgrupada) {
            $c = $contrato->get($desc);
            $e = $ejecucionAgrupada->get($desc);

            $cantidadC = $c ? $c->cantidad : 0;
            $precioC = $c ? $c->precio_unit : 0;
            $totalC = $c ? $c->total : 0;

            $cantidadE = $e ? $e['cantidad'] : 0;
            $precioE = $e ? $e['precio_promedio'] : 0;
            $totalE = $e ? $e['total'] : 0;

            return [
                'descripcion' => $desc,
                'unidad' => $c?->unidad ?? ($e['unidad'] ?? '—'),
                'contrato' => [
                    'cantidad' => $cantidadC,
                    'precio' => $precioC,
                    'total' => $totalC,
                ],
                'ejecucion' => [
                    'cantidad' => $cantidadE,
                    'precio' => $precioE,
                    'total' => $totalE,
                    'compras' => $e['compras'] ?? 0, // útil para auditoría
                ],
                'diferencias' => [
                    'cantidad' => $cantidadE - $cantidadC,
                    'precio' => $precioE - $precioC,
                    'total' => $totalE - $totalC,
                ],
            ];
        });

        return view('listmaterials', compact('proyecto', 'comparacion'));
    }
}
