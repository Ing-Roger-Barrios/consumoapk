<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaAvanceDetalle extends Model
{
    protected $fillable = [
        'planilla_avance_id',
        'trabajador_id',
        'total_monto',
        'total_porcentaje'
    ];

    public function planilla()
    {
        return $this->belongsTo(PlanillaAvance::class, 'planilla_avance_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function avances()
    {
        return $this->hasMany(
            ManoObraItemAvance::class,
            'planilla_avance_detalle_id' // 👈 foreign key real
        );
    }
}
