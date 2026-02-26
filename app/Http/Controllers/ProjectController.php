<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;

 

class ProjectController extends Controller
{
    public function index()
    {
        $user = Auth::User();

        if ($user->role === 'contractor') {
            // Solo sus proyectos creados
            $proy = $user->proyectosCreados()->latest()->get();
        } elseif ($user->role === 'resident') {
            // Solo los proyectos en los que está asignado
            $proy = $user->proyectosAsignados()->latest()->get();
        } else {
            $proy = collect(); // o abort(403)
        }
        
      
        
        

        return view('dashboard', compact('proy'));
    }




    public function create() 
    {
        $residentes = Auth::user()->residents; // solo los que él creó
        return view('newproject', compact('residentes'));
    }

    // Para editar
    public function edit(Proyecto $proyecto)
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

        $residentes = Auth::user()->residents;
        return view('newproject', compact('proyecto', 'residentes'));
    }

    public function update(Request $request, Proyecto $proyecto)
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
            'nombre' => 'required|string|max:255',
            'cliente' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'monto' => 'required|numeric|min:0',
            'residentes_ids' => 'nullable|array',
            'residentes_ids.*' => 'exists:users,id',
        ]);

         

        $proyecto->update([
            'nombre' => $request->nombre,
            'cliente' => $request->cliente,
            'ubicacion' => $request->ubicacion,
            'monto' => $request->monto,
        ]);

        if ($request->filled('residentes_ids')) {
            $proyecto->residentes()->sync($request->residentes_ids);
        } else {
            $proyecto->residentes()->sync([]);
        }

        return redirect()->route('dashboard')->with('success', 'Proyecto actualizado exitosamente.');
    }



    public function project(Proyecto $proyecto) 
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
                    'cantidad' => $cantidadC - $cantidadE,
                    'precio' =>   $precioC - $precioE,
                    'total' =>  $totalC - $totalE ,
                ],
            ];
        });

        function normalizeNumber($number)
        {
            // Si contiene coma decimal (formato latino)
            if (strpos($number, ',') !== false) {
                // Quitar puntos de miles
                $number = str_replace('.', '', $number);
                // Cambiar coma decimal a punto
                $number = str_replace(',', '.', $number);
            }

            return (float)$number;
        }


        $matCont = round(normalizeNumber($proyecto->materialesContrato->sum('total')), 2);
        $manodeobra = round(normalizeNumber($proyecto->manoObraContrato->sum('monto_presupuestado')), 2);
        $equipoMaqui = round(normalizeNumber($proyecto->equipoMaquinariaContrato->sum('total')), 2);
        $beneficiosSoc = round(normalizeNumber($manodeobra * 0.55), 2);
        $iva = round(normalizeNumber(0.1494 * ($manodeobra + $beneficiosSoc)), 2);
        $totalManodeObra = round(normalizeNumber($manodeobra + $beneficiosSoc + $iva), 2);

        $herrMenores = round(normalizeNumber(0.05 * $totalManodeObra), 2);
        $totalHerrEquipo = round(normalizeNumber( $equipoMaqui + $herrMenores), 2);

        $subtotal = round(normalizeNumber($matCont + $totalManodeObra + $totalHerrEquipo), 2);
       //$subtotal = normalizeNumber(211321.68 + 164220.81 + 11328.46, 2);
        $gastosgral = round(normalizeNumber(0.1 * $subtotal), 2);

        $it = round(normalizeNumber(0.0309 * $proyecto->monto), 2);

        //$gastosgral = round(0.1 * $subtotal, 2);
        $utilidad = round(normalizeNumber($proyecto->monto - ($gastosgral + $subtotal + $it)), 2);

        $proytotal = round(normalizeNumber($utilidad + $gastosgral + $subtotal + $it), 2);



        

        // Total de mano de obra directa
        $totalManoObraDirecta = $proyecto->manoObraContrato->sum('monto_presupuestado');
        // Total de mano de obra ejecutada
        //$totalManoObraEjecucion = $proyecto->totalManoObraEjecucion->sum('total');
        
        // Total de subcontratos
        $totalSubcontratos = $proyecto->subcontratos->sum('monto_acordado');

        //total ejecutado
        $totalEjecutado =  $totalSubcontratos;       //$totalManoObraEjecucion +
        //facturas
        $facturas = $proyecto->ivaFacturas()->latest()->get();

        


        return view('project', compact('proyecto', 'comparacion', 'totalManoObraDirecta', 'totalEjecutado', 
                    'facturas','matCont', 'manodeobra','beneficiosSoc', 'totalHerrEquipo', 'equipoMaqui', 'subtotal', 'gastosgral', 'utilidad', 'iva', 'it','proytotal'   ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'monto' => 'required|numeric|min:0',
            'residentes_ids' => 'nullable|array',
            'residentes_ids.*' => 'exists:users,id', // asegura que sean IDs válidos de users
        ]);

        // Crear el proyecto
        $proyecto = Proyecto::create([
            'nombre' => $request->nombre,
            'cliente' => $request->cliente,
            'ubicacion' => $request->ubicacion,
            'monto' => $request->monto,
            'user_id' => Auth::id(), // el contractor
        ]);

        // Asignar residentes (si se seleccionaron)
        if ($request->filled('residentes_ids')) {
            $proyecto->residentes()->sync($request->residentes_ids);
        }

        return redirect()->route('dashboard')->with('success', 'Proyecto creado exitosamente.');
    }

    // Eliminar
    public function destroy(Proyecto $proyecto)
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

        $proyecto->delete();
        return redirect()->route('proy.index')->with('success', 'Proyecto eliminado.');
    }
    public function comparacion(Proyecto $proyecto)
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
                    'cantidad' => $cantidadC - $cantidadE,
                    'precio' =>   $precioC - $precioE,
                    'total' =>  $totalC - $totalE ,
                ],
            ];
        });

        return view('proyectos.comparacion', compact('proyecto', 'comparacion'));
    }
}
