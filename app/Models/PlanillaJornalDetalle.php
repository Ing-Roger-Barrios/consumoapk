<?php
// ═══════════════════════════════════════════════════════════
// app/Models/PlanillaJornalDetalle.php
// ═══════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaJornalDetalle extends Model
{
    protected $table = 'planilla_jornal_detalle';

    protected $fillable = [
        'planilla_id', 'trabajador_id',
        'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado',
        'hs_extra_lunes', 'hs_extra_martes', 'hs_extra_miercoles',
        'hs_extra_jueves', 'hs_extra_viernes', 'hs_extra_sabado',
        'descuento_anticipo', 'descuento_otros', 'descuento_notas',
        'salario_dia_snapshot', 'hora_extra_snapshot',
    ];

    protected $casts = [
        'lunes' => 'decimal:1', 'martes' => 'decimal:1',
        'miercoles' => 'decimal:1', 'jueves' => 'decimal:1',
        'viernes' => 'decimal:1', 'sabado' => 'decimal:1',
    ];

    public function planilla()
    {
        return $this->belongsTo(PlanillaJornal::class, 'planilla_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    // Total bruto: días * salario + horas extra * valor hora
    public function getTotalBrutoAttribute(): float
    {
        $dias = $this->total_dias;
        $hs   = $this->total_hs_extra;
        return ($dias * $this->salario_dia_snapshot) + ($hs * $this->hora_extra_snapshot);
    }

    // Total descuentos
    public function getTotalDescuentosAttribute(): float
    {
        return $this->descuento_anticipo + $this->descuento_otros;
    }

    // Total neto a pagar
    public function getTotalNetoAttribute(): float
    {
        return $this->total_bruto - $this->total_descuentos;
    }
}