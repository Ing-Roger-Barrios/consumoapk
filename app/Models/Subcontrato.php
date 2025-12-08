<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcontrato extends Model
{
    protected $table = 'subcontratos'; // Asegúrate del nombre correcto
    
    protected $fillable = [
        'proyecto_id',
        'nombre',
        'descripcion',
        'monto_acordado',
        'contrato', // ← Agregado
        'notas'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function pagos()
    {
        return $this->hasMany(PagoSubcontrato::class);
    }

    // Monto total pagado
    public function getMontoPagadoAttribute()
    {
        return $this->pagos->sum('monto_pagado');
    }

    // Saldo pendiente
    public function getSaldoPendienteAttribute()
    {
        return $this->monto_acordado - $this->monto_pagado;
    }

    // Porcentaje completado
    public function getPorcentajeCompletadoAttribute()
    {
        return $this->monto_acordado > 0 ? ($this->monto_pagado / $this->monto_acordado) * 100 : 0;
    }
}