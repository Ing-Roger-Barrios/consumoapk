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
<<<<<<< HEAD
        // Materiales de contrato: 1 por descripción
        $contrato = $proyecto->equipoMaquinariaContrato->keyBy('descripcion');

        // Materiales en ejecución: agrupar y sumar por descripción
        $ejecucionAgrupada = $proyecto->equipoMaquinariaEjecucion
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


        return view('equipo.contrato.index', compact('proyecto', 'comparacion', 'equipos'));
=======
        return view('equipo.contrato.index', compact('proyecto', 'equipos'));
>>>>>>> b92c1913736957a2b206b43dc016d8445eeff9fc
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
<<<<<<< HEAD
    public function edit(Proyecto $proyecto, string $equipodesc)
    {
        $this->authorizeAccess($proyecto);
        // Verificar que el ítem pertenece al proyecto
        

        $equipo = $proyecto->equipoMaquinariaContrato()
        ->where('descripcion', $equipodesc)
        ->first();

=======
    public function edit(Proyecto $proyecto, EquipoMaquinariaContrato $equipo)
    {
        $this->authorizeAccess($proyecto);
        // Verificar que el ítem pertenece al proyecto
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Ítem no pertenece al proyecto');
        }
>>>>>>> b92c1913736957a2b206b43dc016d8445eeff9fc
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
<<<<<<< HEAD
    public function destroy(Proyecto $proyecto, string $equipodesc)
    {
        $this->authorizeAccess($proyecto);
        
        // 🔎 Buscar material por descripción DENTRO del proyecto
        $equipo = $proyecto->materialesContrato()
            ->where('descripcion', $equipodesc)
            ->first();

        if (!$equipo) {
            abort(404, 'Material no encontrado');
        }


=======
    public function destroy(Proyecto $proyecto, EquipoMaquinariaContrato $equipo)
    {
        $this->authorizeAccess($proyecto);
        if ($equipo->proyecto_id !== $proyecto->id) {
            abort(403, 'Ítem no pertenece al proyecto');
        }

>>>>>>> b92c1913736957a2b206b43dc016d8445eeff9fc
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