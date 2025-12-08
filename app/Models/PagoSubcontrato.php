<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoSubcontrato extends Model
{
    protected $table = 'pagos_subcontratos';
    
    protected $fillable = [
        'subcontrato_id',
        'monto_pagado',
        'fecha_pago',
        'comprobante',
        'notas'
    ];

    // ✅ Usa $casts (método moderno)
    protected $casts = [
        'fecha_pago' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function subcontrato()
    {
        return $this->belongsTo(Subcontrato::class);
    }
}