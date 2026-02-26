<?php

namespace App\Services;

use App\Models\Proyecto;

class LibroContableService
{
    /**
     * Construye el resumen completo del libro contable para un proyecto.
     * Retorna ingresos, egresos desglosados, balance y comparación contrato vs ejecución.
     */
    public function resumen(Proyecto $proyecto): array
    {
        $ingresos  = $this->calcularIngresos($proyecto);
        $egresos   = $this->calcularEgresos($proyecto);
        $contrato  = $this->calcularTotalContrato($proyecto);

        $totalIngresos = $ingresos['total'];
        $totalEgresos  = $egresos['total'];
        $saldo         = $totalIngresos - $totalEgresos;

        return [
            'proyecto'      => $proyecto,
            'ingresos'      => $ingresos,
            'egresos'       => $egresos,
            'contrato'      => $contrato,
            'total_ingresos'=> $totalIngresos,
            'total_egresos' => $totalEgresos,
            'saldo'         => $saldo,
            'utilidad'      => $contrato['total'] - $totalEgresos,
            'porcentaje_ejecucion' => $contrato['total'] > 0
                ? round(($totalEgresos / $contrato['total']) * 100, 2)
                : 0,
        ];
    }

    // ─────────────────────────────────────────────
    // INGRESOS: planillas de pago del cliente
    // ─────────────────────────────────────────────
    private function calcularIngresos(Proyecto $proyecto): array
    {
        $planillas = $proyecto->planillasPago()
            ->orderBy('fecha_pago')
            ->get();

        return [
            'planillas' => $planillas,
            'total'     => $planillas->sum('monto'),
        ];
    }

    // ─────────────────────────────────────────────
    // EGRESOS: consolidación de todos los módulos
    // ─────────────────────────────────────────────
    private function calcularEgresos(Proyecto $proyecto): array
    {
        // Materiales en ejecución
        $materiales = $proyecto->materialesEjecucion()->sum('total');

        // Mano de obra: si tienes mano_obra_ejecucion úsala; si no, usamos contrato como fallback
        // Cuando implementes mano_obra_ejecucion, cambia aquí.
        $manoObra = $proyecto->manoObraContrato()->sum('monto_presupuestado');

        // Equipo y maquinaria en ejecución
        $equipo = $proyecto->equipoMaquinariaEjecucion()->sum('total');

        // Subcontratos: suma de pagos reales, no el monto acordado
        $subcontratos = 0;
        foreach ($proyecto->subcontratos as $sub) {
            $subcontratos += $sub->pagos()->sum('monto_pagado');
        }

        // Gastos generales
        $gastos = $proyecto->gastosGenerales()->sum('monto');

        // Beneficios sociales
        $beneficios = $proyecto->beneficiosSociales()->sum('monto');

        // IVA pagado
        $iva = $proyecto->ivaFacturas()->sum('monto_iva');

        // IT (Impuesto a las Transferencias)
        $it = $proyecto->it ? $proyecto->it->monto : 0;

        $total = $materiales + $manoObra + $equipo + $subcontratos
               + $gastos + $beneficios + $iva + $it;

        return [
            'detalle' => [
                ['concepto' => 'Materiales (Ejecución)',        'monto' => $materiales],
                ['concepto' => 'Mano de Obra',                  'monto' => $manoObra],
                ['concepto' => 'Equipo y Maquinaria',           'monto' => $equipo],
                ['concepto' => 'Subcontratos (Pagos realizados)','monto' => $subcontratos],
                ['concepto' => 'Gastos Generales',              'monto' => $gastos],
                ['concepto' => 'Beneficios Sociales',           'monto' => $beneficios],
                ['concepto' => 'IVA Facturas',                  'monto' => $iva],
                ['concepto' => 'IT (Imp. Transferencias)',      'monto' => $it],
            ],
            'total' => $total,
        ];
    }

    // ─────────────────────────────────────────────
    // CONTRATO: lo presupuestado originalmente
    // ─────────────────────────────────────────────
    private function calcularTotalContrato(Proyecto $proyecto): array
    {
        $materiales   = $proyecto->materialesContrato()->sum('total');
        $manoObra     = $proyecto->manoObraContrato()->sum('monto_presupuestado');
        $equipo       = $proyecto->equipoMaquinariaContrato()->sum('total');
        $subcontratos = $proyecto->subcontratos()->sum('monto_acordado');

        $total = $materiales + $manoObra + $equipo + $subcontratos;

        return [
            'detalle' => [
                ['concepto' => 'Materiales (Contrato)',    'monto' => $materiales],
                ['concepto' => 'Mano de Obra (Contrato)',  'monto' => $manoObra],
                ['concepto' => 'Equipo y Maquinaria',      'monto' => $equipo],
                ['concepto' => 'Subcontratos (Acordado)',  'monto' => $subcontratos],
            ],
            'total' => $total,
        ];
    }
}