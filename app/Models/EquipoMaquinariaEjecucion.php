<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoMaquinariaEjecucion extends Model
{
    protected $table = 'equipo_maquinaria_ejecucion';
    
    protected $fillable = [
        'proyecto_id',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unit',
        'total',
        'comprobante',
        'notas'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}