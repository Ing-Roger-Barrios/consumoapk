<?php
// ═══════════════════════════════════════════════════════════
// app/Models/Trabajador.php
// ═══════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'nombre', 'ci', 'cargo',
        'salario_dia', 'hora_extra', 'created_by',
    ];

    protected $casts = [
        'salario_dia' => 'decimal:2',
        'hora_extra'  => 'decimal:2',
    ];

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_trabajador')
                    ->withPivot('tipo')
                    ->withTimestamps();
    }

    public function asignacionesItem()
    {
        return $this->hasMany(ManoObraItemAsignacion::class);
    }
}