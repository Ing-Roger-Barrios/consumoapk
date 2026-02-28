<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ManoObraModulo;
use App\Models\ManoObraItem;
use App\Imports\ManoObraImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManoObraModuloController extends Controller
{
    // ─────────────────────────────────────────────
    // ÍNDICE: lista de módulos con sus ítems
    // ─────────────────────────────────────────────
    public function index(Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $modulos = ManoObraModulo::where('proyecto_id', $proyecto->id)
            ->with(['items.asignaciones.avances'])
            ->orderBy('orden')
            ->get();

        $totalPresupuestado = $modulos->sum('total_presupuestado');
        $totalPagado        = $modulos->sum('total_pagado');

        return view('mano-obra.modulos.Index',
            compact('proyecto', 'modulos', 'totalPresupuestado', 'totalPagado'));
    }

    // ─────────────────────────────────────────────
    // IMPORTAR EXCEL
    // ─────────────────────────────────────────────
    public function import(Request $request, Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(
                new ManoObraImport($proyecto->id),
                $request->file('archivo_excel')
            );

            return redirect()->route('mano.obra.modulos.index', $proyecto)
                ->with('success', 'Módulos e ítems importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // CREAR MÓDULO MANUAL
    // ─────────────────────────────────────────────
    public function createModulo(Proyecto $proyecto)
    {
        $this->authorize($proyecto);
        $ultimoOrden = ManoObraModulo::where('proyecto_id', $proyecto->id)->max('orden') ?? 0;

        return view('mano-obra.modulos.create_modulo', compact('proyecto', 'ultimoOrden'));
    }

    public function storeModulo(Request $request, Proyecto $proyecto)
    {
        $this->authorize($proyecto);

        $data = $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
        ]);

        $data['proyecto_id'] = $proyecto->id;
        $data['orden']       = ManoObraModulo::where('proyecto_id', $proyecto->id)->max('orden') + 1;

        ManoObraModulo::create($data);

        return redirect()->route('mano.obra.modulos.index', $proyecto)
            ->with('success', "Módulo {$data['codigo']} creado.");
    }

    public function destroyModulo(Proyecto $proyecto, ManoObraModulo $modulo)
    {
        $this->authorize($proyecto);
        $modulo->delete();

        return redirect()->route('mano.obra.modulos.index', $proyecto)
            ->with('success', 'Módulo eliminado.');
    }

    // ─────────────────────────────────────────────
    // VER DETALLE DE UN MÓDULO con sus ítems
    // ─────────────────────────────────────────────
    public function showModulo(Proyecto $proyecto, ManoObraModulo $modulo)
    {
        $this->authorize($proyecto);
        $modulo->load(['items.asignaciones.avances', 'asignaciones.trabajador']);

        return view('mano-obra.modulos.show_modulo', compact('proyecto', 'modulo'));
    }

    // ─────────────────────────────────────────────
    // CREAR ÍTEM MANUAL dentro de un módulo
    // ─────────────────────────────────────────────
    public function createItem(Proyecto $proyecto, ManoObraModulo $modulo)
    {
        $this->authorize($proyecto);
        return view('mano-obra.modulos.create_item', compact('proyecto', 'modulo'));
    }

    public function storeItem(Request $request, Proyecto $proyecto, ManoObraModulo $modulo)
    {
        $this->authorize($proyecto);

        $data = $request->validate([
            'descripcion'     => 'required|string|max:255',
            'unidad'          => 'required|string|max:50',
            'cantidad'        => 'required|numeric|min:0',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        $data['modulo_id']   = $modulo->id;
        $data['proyecto_id'] = $proyecto->id;
        $data['parcial']     = $data['cantidad'] * $data['precio_unitario'];
        $data['numero']      = ManoObraItem::where('modulo_id', $modulo->id)->max('numero') + 1;
        $data['orden']       = ManoObraItem::where('modulo_id', $modulo->id)->max('orden') + 1;

        ManoObraItem::create($data);

        return redirect()->route('mano.obra.modulos.show', [$proyecto, $modulo])
            ->with('success', 'Ítem agregado al módulo.');
    }

    public function destroyItem(Proyecto $proyecto, ManoObraItem $item)
    {
        $this->authorize($proyecto);
        $modulo = $item->modulo;
        $item->delete();

        return redirect()->route('mano.obra.modulos.show', [$proyecto, $modulo])
            ->with('success', 'Ítem eliminado.');
    }

    // ─────────────────────────────────────────────
    private function authorize(Proyecto $proyecto): void
    {
        $user = Auth::user();
        $esOwner    = $user->isContractor() && $proyecto->user_id === $user->id;
        $esResident = $user->isResident() && $proyecto->residentes->contains($user->id);
        if (!$esOwner && !$esResident) abort(403);
    }
}