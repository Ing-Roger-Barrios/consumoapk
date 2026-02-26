<?php
// ═══════════════════════════════════════════════════════════
// app/Models/ManoObraItem.php  (reemplaza ManoObraContrato)
// ═══════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManoObraItem extends Model
{
    protected $table = 'mano_obra_items';

    protected $fillable = [
        'modulo_id', 'proyecto_id', 'numero',
        'descripcion', 'unidad', 'cantidad',
        'precio_unitario', 'parcial', 'orden',
    ];

    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'parcial'         => 'decimal:2',
    ];

    public function modulo()
    {
        return $this->belongsTo(ManoObraModulo::class, 'modulo_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(ManoObraItemAsignacion::class, 'mano_obra_item_id');
    }

    // Total pagado en avances para este ítem
    public function getTotalPagadoAttribute(): float
    {
        return ManoObraItemAvance::whereHas('asignacion',
            fn($q) => $q->where('mano_obra_item_id', $this->id)
        )->sum('monto_pagar');
    }

    public function getPorcentajeAvanceAttribute()
    {
        $total = \App\Models\ManoObraItemAvance::where('mano_obra_item_id', $this->id)
            ->sum('porcentaje_avance');

        return round($total, 2);
    }
    public function getTrabajadorAsignadoAttribute()
    {
        // Si el módulo tiene asignación completa
        if ($this->modulo->asignacionModulo) {
            return $this->modulo->asignacionModulo->trabajador;
        }

        return null;
    }
        /*public function getTrabajadorAsignadoAttribute()
        {
            // 1️⃣ Buscar asignación directa del item
            $directa = \App\Models\ManoObraItemAsignacion::where('tipo_asignacion', 'item')
                ->where('item_id', $this->id)
                ->first();

            if ($directa) {
                return $directa->trabajador;
            }

            // 2️⃣ Buscar asignación por módulo
            $moduloAsignado = \App\Models\ManoObraItemAsignacion::where('tipo_asignacion', 'modulo')
                ->where('modulo_id', $this->modulo_id)
                ->first();

            if ($moduloAsignado) {
                return $moduloAsignado->trabajador;
            }

            return null;
        }*/
}
