<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IT extends Model
{
    protected $table = 'it';
    
    protected $fillable = [
        'proyecto_id',
        'porcentaje',
        'monto',
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

    // Calcular monto automáticamente
    public function getMontoCalculadoAttribute()
    {
        return $this->proyecto->monto * ($this->porcentaje / 100);
    }

    // Actualizar monto cuando se guarda
    protected static function booted()
    {
        static::saving(function ($it) {
            $it->monto = $it->proyecto->monto * ($it->porcentaje / 100);
        });
    }
}