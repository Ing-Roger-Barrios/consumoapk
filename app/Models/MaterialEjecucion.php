<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialEjecucion extends Model
{
    use HasFactory;

    protected $table = 'materiales_en_ejecucion';

    protected $fillable = [
        'proyecto_id',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unit',
        'total',
        'comprobante',
         
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}