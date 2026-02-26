<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ManoObraItemAvance extends Model
{
    protected $table = 'mano_obra_item_avance';

    protected $fillable = [
        'asignacion_id', 'mano_obra_item_id', 'fecha', 'porcentaje_avance',
        'monto_pagar', 'foto1', 'foto2', 'foto3','fecha_avance',
        'observaciones', 'aprobado_por','planilla_avance_detalle_id',
    ];

    protected $casts = [
        'fecha'             => 'date',
        'porcentaje_avance' => 'decimal:2',
        'monto_pagar'       => 'decimal:2',
    ];

    public function asignacion()
{
    return $this->belongsTo(ManoObraItemAsignacion::class, 'asignacion_id');
}

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
    public function planillaDetalle()
    {
        return $this->belongsTo(PlanillaAvanceDetalle::class);
    }
    public function mano_obra_item()
    {
        return $this->belongsTo(ManoObraItem::class);
    }
    public function detalle()
    {
        return $this->belongsTo(
            PlanillaAvanceDetalle::class,
            'planilla_avance_detalle_id'
        );
    }
}

