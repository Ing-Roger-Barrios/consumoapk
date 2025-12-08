<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoGeneral extends Model
{
    protected $table = 'gastos_generales';
    
    protected $fillable = [
        'proyecto_id',
        'descripcion',
        'categoria',
        'monto',
        'fecha_gasto',
        'comprobante',
        'notas'
    ];

    protected $casts = [
        'fecha_gasto' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Categorías comunes (puedes personalizar)
    public static function categorias()
    {
        return [
            'Alimentación',
            'Transporte',
            'Equipos y Herramientas',
            'Combustible',
            'Comunicaciones',
            'Oficina',
            'Seguridad',
            'Mantenimiento',
            'Otros'
        ];
    }
}