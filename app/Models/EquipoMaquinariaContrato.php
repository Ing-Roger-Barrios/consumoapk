<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoMaquinariaContrato extends Model
{
    protected $table = 'equipo_maquinaria_contrato';
    
    protected $fillable = [
        'proyecto_id',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unit',
        'total'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}