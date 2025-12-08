<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialContrato extends Model
{
    use HasFactory;

    protected $table = 'materiales_de_contrato';

    protected $fillable = [
        'proyecto_id',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unit',
        'total',
    ];

    // Relación: pertenece a un proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}