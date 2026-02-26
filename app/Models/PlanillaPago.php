<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanillaPago extends Model
{
    use HasFactory;

    protected $table = 'planillas_pago';

    protected $fillable = [
        'proyecto_id',
        'numero_planilla',
        'concepto',
        'monto',
        'fecha_pago',
        'comprobante',
        'notas',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto'      => 'decimal:2',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}