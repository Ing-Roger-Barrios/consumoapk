<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Trabajador;
use App\Models\PlanillaJornal;
use App\Models\PlanillaJornalDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManoObraJornalController extends Controller
{
    // ─────────────────────────────────────────────
    // TRABAJADORES DEL PROYECTO (tipo jornal)
    // ─────────────────────────────────────────────

    public function trabajadores(Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $asignados = $proyecto->trabajadoresJornal()->get();
        $disponibles = Trabajador::whereNotIn('id', $asignados->pluck('id'))->get();

        return view('mano-obra.jornal.trabajadores', compact('proyecto', 'asignados', 'disponibles'));
    }

    public function asignarTrabajador(Request $request, Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $request->validate([
            'trabajador_id' => 'nullable|exists:trabajadores,id',
            'nombre'        => 'required_without:trabajador_id|string|max:100',
            'ci'            => 'nullable|string|max:20',
            'cargo'         => 'nullable|string|max:100',
            'salario_dia'   => 'required|numeric|min:0',
            'hora_extra'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $proyecto) {
            if ($request->trabajador_id) {
                $trabajador = Trabajador::findOrFail($request->trabajador_id);
            } else {
                $trabajador = Trabajador::create([
                    'nombre'      => $request->nombre,
                    'ci'          => $request->ci,
                    'cargo'       => $request->cargo,
                    'salario_dia' => $request->salario_dia,
                    'hora_extra'  => $request->hora_extra,
                    'created_by'  => Auth::id(),
                ]);
            }

            // Asignar al proyecto si no está ya
            $proyecto->trabajadores()->syncWithoutDetaching([
                $trabajador->id => ['tipo' => 'jornal']
            ]);

            // Si se pasan salarios distintos para este proyecto, actualizamos el trabajador
            $trabajador->update([
                'salario_dia' => $request->salario_dia,
                'hora_extra'  => $request->hora_extra,
            ]);
        });

        return redirect()->route('jornal.trabajadores', $proyecto)
                         ->with('success', 'Trabajador asignado correctamente.');
    }

    public function desasignarTrabajador(Proyecto $proyecto, Trabajador $trabajador)
    {
        $this->authorize($proyecto);
        $proyecto->trabajadores()->detach($trabajador->id);

        return redirect()->route('jornal.trabajadores', $proyecto)
                         ->with('success', 'Trabajador removido del proyecto.');
    }

    // ─────────────────────────────────────────────
    // PLANILLAS SEMANALES
    // ─────────────────────────────────────────────

    public function planillas(Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $planillas = $proyecto->planillasJornal()
                              ->with('detalles.trabajador')
                              ->orderByDesc('semana_inicio')
                              ->get();

        return view('mano-obra.jornal.planillas', compact('proyecto', 'planillas'));
    }

    public function createPlanilla(Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $trabajadores = $proyecto->trabajadoresJornal;

        if ($trabajadores->isEmpty()) {
            return redirect()->route('jornal.trabajadores', $proyecto)
                             ->with('error', 'Primero debes asignar trabajadores al proyecto.');
        }

        // Sugerir la semana actual (lunes a sábado)
        $lunes  = now()->startOfWeek()->format('Y-m-d');
        $sabado = now()->startOfWeek()->addDays(5)->format('Y-m-d');

        return view('mano-obra.jornal.create_planilla',
                    compact('proyecto', 'trabajadores', 'lunes', 'sabado'));
    }

    public function storePlanilla(Request $request, Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $request->validate([
            'semana_inicio'  => 'required|date',
            'semana_fin'     => 'required|date|after_or_equal:semana_inicio',
            'observaciones'  => 'nullable|string',
            'trabajadores'   => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request, $proyecto) {
            $planilla = PlanillaJornal::create([
                'proyecto_id'    => $proyecto->id,
                'semana_inicio'  => $request->semana_inicio,
                'semana_fin'     => $request->semana_fin,
                'observaciones'  => $request->observaciones,
                'registrado_por' => Auth::id(),
            ]);

            foreach ($request->trabajadores as $tid => $datos) {
                $trabajador = Trabajador::findOrFail($tid);

                PlanillaJornalDetalle::create([
                    'planilla_id'   => $planilla->id,
                    'trabajador_id' => $tid,

                    'lunes'      => $datos['lunes']      ?? 0,
                    'martes'     => $datos['martes']     ?? 0,
                    'miercoles'  => $datos['miercoles']  ?? 0,
                    'jueves'     => $datos['jueves']     ?? 0,
                    'viernes'    => $datos['viernes']    ?? 0,
                    'sabado'     => $datos['sabado']     ?? 0,

                    'hs_extra_lunes'     => $datos['hs_extra_lunes']     ?? 0,
                    'hs_extra_martes'    => $datos['hs_extra_martes']    ?? 0,
                    'hs_extra_miercoles' => $datos['hs_extra_miercoles'] ?? 0,
                    'hs_extra_jueves'    => $datos['hs_extra_jueves']    ?? 0,
                    'hs_extra_viernes'   => $datos['hs_extra_viernes']   ?? 0,
                    'hs_extra_sabado'    => $datos['hs_extra_sabado']    ?? 0,

                    'descuento_anticipo' => $datos['descuento_anticipo'] ?? 0,
                    'descuento_otros'    => $datos['descuento_otros']    ?? 0,
                    'descuento_notas'    => $datos['descuento_notas']    ?? null,

                    // Snapshot del salario actual
                    'salario_dia_snapshot' => $trabajador->salario_dia,
                    'hora_extra_snapshot'  => $trabajador->hora_extra,
                ]);
            }
        });

        return redirect()->route('jornal.planillas', $proyecto)
                         ->with('success', 'Planilla semanal registrada correctamente.');
    }

    public function showPlanilla(Proyecto $proyecto, PlanillaJornal $planilla)
    {
        $this->authorize($proyecto);
        $planilla->load('detalles.trabajador');

        return view('mano-obra.jornal.show_planilla', compact('proyecto', 'planilla'));
    }

    public function destroyPlanilla(Proyecto $proyecto, PlanillaJornal $planilla)
    {
        $this->authorize($proyecto);
        $planilla->delete();

        return redirect()->route('jornal.planillas', $proyecto)
                         ->with('success', 'Planilla eliminada.');
    }

    // ─────────────────────────────────────────────
    private function authorize(Proyecto $proyecto): void
    {
        $user = Auth::user();
        $esOwner   = $user->isContractor() && $proyecto->user_id === $user->id;
        $esResident = $user->isResident() && $proyecto->residentes->contains($user->id);

        if (!$esOwner && !$esResident) {
            abort(403);
        }
    }
}