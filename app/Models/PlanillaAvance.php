<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaAvance extends Model
{
    protected $table = 'planillas_avance';
    protected $fillable = [
        'proyecto_id',
        'semana_inicio',
        'semana_fin',
        'total_pagar',
        'archivo_constancia',
        'observaciones'
    ];
    protected $casts = [
        'semana_inicio' => 'date',
        'semana_fin'    => 'date',
    ];

    protected $dates = ['semana_inicio', 'semana_fin'];

    public function detalles()
    {
        return $this->hasMany(PlanillaAvanceDetalle::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
