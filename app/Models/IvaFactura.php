<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvaFactura extends Model
{
    protected $table = 'iva_facturas';
    
    protected $fillable = [
        'proyecto_id',
        'numero_factura',
        'monto_factura',
        'porcentaje_iva',
        'monto_iva',
        'fecha_factura',
        'comprobante',
        'notas'
    ];

    protected $casts = [
        'fecha_factura' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Calcular monto IVA automáticamente
    public function getMontoIvaCalculadoAttribute()
    {
        return $this->monto_factura * ($this->porcentaje_iva / 100);
    }

    // Actualizar monto IVA al guardar
    protected static function booted()
    {
        static::saving(function ($factura) {
            $factura->monto_iva = $factura->monto_factura * ($factura->porcentaje_iva / 100);
        });
    }
}