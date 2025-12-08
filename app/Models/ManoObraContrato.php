<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManoObraContrato extends Model
{
    protected $table = 'mano_obra_contrato'; // ← Nombre singular

    protected $fillable = [
        'proyecto_id',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unit',
        'monto_presupuestado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}