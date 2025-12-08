<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficioSocial extends Model
{
    protected $table = 'beneficios_sociales';
    
    protected $fillable = [
        'proyecto_id',
        'tipo_beneficio',
        'monto',
        'fecha_pago',
        'comprobante',
        'notas'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Tipos comunes de beneficios sociales
    public static function tiposBeneficios()
    {
        return [
            'Aguinaldo',
            'Bono de Producción',
            'Utilidades',
            'Gratificaciones',
            'Bonos por Asistencia',
            'Seguro Médico',
            'Capacitación',
            'Otros Beneficios'
        ];
    }
}
