<?php
// ═══════════════════════════════════════════════════════════
// app/Models/PlanillaJornal.php
// ═══════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaJornal extends Model
{
    protected $table = 'planillas_jornal';

    protected $fillable = [
        'proyecto_id', 'semana_inicio', 'semana_fin',
        'observaciones', 'registrado_por',
    ];

    protected $casts = [
        'semana_inicio' => 'date',
        'semana_fin'    => 'date',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function detalles()
    {
        return $this->hasMany(PlanillaJornalDetalle::class, 'planilla_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // Total a pagar de toda la planilla
    public function getTotalPagarAttribute(): float
    {
        return $this->detalles->sum(fn($d) => $d->total_neto);
    }
}


