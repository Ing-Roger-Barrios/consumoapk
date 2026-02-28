<?php
// ═══════════════════════════════════════════════════════════
// app/Models/ManoObraItemAsignacion.php
// ═══════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManoObraItemAsignacion extends Model
{
    protected $table = 'mano_obra_item_asignacion';

    protected $fillable = [
        'proyecto_id',
        'mano_obra_contrato_id',  // nullable - sistema viejo
        'mano_obra_item_id',      // ítem específico (nuevo)
        'modulo_id',              // módulo completo (nuevo)
        'tipo_asignacion',        // 'item' o 'modulo'
        'trabajador_id',
        'monto_acordado',
        'notas',
    ];

    protected $casts = [
        'monto_acordado' => 'decimal:2',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function itemContrato()
    {
        return $this->belongsTo(ManoObraContrato::class, 'mano_obra_contrato_id');
    }

    // Nueva relación: ítem del nuevo sistema de módulos
    public function item()
    {
        return $this->belongsTo(ManoObraItem::class, 'mano_obra_item_id');
    }

    // Nueva relación: módulo completo asignado
    public function modulo()
    {
        return $this->belongsTo(ManoObraModulo::class, 'modulo_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

     

    public function avances()
    {
        return $this->hasMany(ManoObraItemAvance::class, 'asignacion_id');
    }

    // Porcentaje total aprobado hasta ahora
    public function getPorcentajeTotalAttribute()
    {
        if ($this->tipo_asignacion === 'item') {
            return round($this->avances->sum('porcentaje_avance'), 2);
        }

        // 🔥 CASO MÓDULO (ponderado)
        if (!$this->modulo) return 0;

        $totalModulo = $this->modulo->total_presupuestado;
        if ($totalModulo <= 0) return 0;

        $total = 0;

        foreach ($this->modulo->items as $item) {

            $peso = $item->parcial / $totalModulo;

            $pctItem = $this->avances
                ->where('mano_obra_item_id', $item->id)
                ->sum('porcentaje_avance');

            $total += $pctItem * $peso;
        }

        return round($total, 2);
    }

    // Monto total pagado
    public function getMontoPagadoAttribute(): float
    {
        return $this->avances->sum('monto_pagar');
    }

    // Monto pendiente
    public function getMontoPendienteAttribute(): float
    {
        return $this->monto_acordado - $this->monto_pagado;
    }
}