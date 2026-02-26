<?php
// ═══════════════════════════════════════════════════════════
// app/Models/ManoObraModulo.php
// ═══════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManoObraModulo extends Model
{
    protected $table = 'mano_obra_modulos';

    protected $fillable = ['proyecto_id', 'codigo', 'nombre', 'orden'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function items()
    {
        return $this->hasMany(ManoObraItem::class, 'modulo_id')->orderBy('orden');
    }

    public function asignaciones()
    {
        return $this->hasMany(ManoObraItemAsignacion::class, 'modulo_id');
    }

    // Total presupuestado del módulo
    public function getTotalPresupuestadoAttribute(): float
    {
        return $this->items->sum('parcial');
    }

    // Total pagado en avances de todos los ítems del módulo
    public function getTotalPagadoAttribute()
    {
        return \App\Models\ManoObraItemAvance::whereIn(
            'mano_obra_item_id',
            $this->items->pluck('id')
        )->sum('monto_pagar');
    }

    // Porcentaje de avance global del módulo
    public function getPorcentajeAvanceAttribute()
    {
        if ($this->total_presupuestado <= 0) return 0;

        $total = 0;

        foreach ($this->items as $item) {

            $peso = $item->parcial / $this->total_presupuestado;
            $pctItem = $item->porcentaje_avance;

            $total += $pctItem * $peso;
        }

        return round($total, 2);
    }
    public function asignacionModulo()
    {
        return $this->hasOne(\App\Models\ManoObraItemAsignacion::class, 'modulo_id')
            ->where('tipo_asignacion', 'modulo');
    }
}