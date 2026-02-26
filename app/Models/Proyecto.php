<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos'; // porque usas nombre en español

    protected $fillable = [
        'nombre',
        'cliente',
        'ubicacion',
        'monto',
        'user_id', // el contractor que creó el proyecto
    ];

    // Relación: el contractor dueño del proyecto
    public function contractor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: muchos a muchos con residentes (que también son User)
    public function residentes()
    {
        return $this->belongsToMany(User::class, 'proyecto_residente', 'proyecto_id', 'residente_id');
    }


    // Materiales presupuestados (de contrato)
    public function materialesContrato()
    {
        return $this->hasMany(MaterialContrato::class, 'proyecto_id');
    }

    // Materiales ejecutados (reales)
    public function materialesEjecucion()
    {
        return $this->hasMany(MaterialEjecucion::class, 'proyecto_id');
    }
    // Mano de obra contrato
    public function manoObraContrato()
    {
        return $this->hasMany(ManoObraContrato::class);
    }

    // Subcontratos
    public function subcontratos()
    {
        return $this->hasMany(Subcontrato::class);
    }
    // Gastos generales
    public function gastosGenerales()
    {
        return $this->hasMany(GastoGeneral::class);
    }
    // Beneficios sociales
    public function beneficiosSociales()
    {
        return $this->hasMany(BeneficioSocial::class);
    }
    // Impuesto a las Transferencias
    public function it()
    {
        return $this->hasOne(IT::class);
    }
    // Facturas IVA
    public function ivaFacturas()
    {
        return $this->hasMany(IvaFactura::class);
    }
    // Equipo y maquinaria contrato
    public function equipoMaquinariaContrato()
    {
        return $this->hasMany(EquipoMaquinariaContrato::class);
    }

    // Equipo y maquinaria ejecucion
    public function equipoMaquinariaEjecucion()
    {
        return $this->hasMany(EquipoMaquinariaEjecucion::class);
    }
<<<<<<< HEAD
    public function planillasPago()
    {
        return $this->hasMany(PlanillaPago::class, 'proyecto_id');
    }
    // Todos los trabajadores del proyecto (jornal + ítem)
    public function trabajadores()
    {
        return $this->belongsToMany(Trabajador::class, 'proyecto_trabajador')
                    ->withPivot('tipo')
                    ->withTimestamps();
    }

    // Solo trabajadores de jornal
    public function trabajadoresJornal()
    {
        return $this->belongsToMany(Trabajador::class, 'proyecto_trabajador')
                    ->withPivot('tipo')
                    ->wherePivot('tipo', 'jornal')
                    ->withTimestamps();
    }

    // Solo trabajadores por ítem
    public function trabajadoresItem()
    {
        return $this->belongsToMany(Trabajador::class, 'proyecto_trabajador')
                    ->withPivot('tipo')
                    ->wherePivot('tipo', 'item')
                    ->withTimestamps();
    }

    // Planillas semanales de jornal
    public function planillasJornal()
    {
        return $this->hasMany(PlanillaJornal::class);
    }
    public function manoObraModulos()
    {
        return $this->hasMany(ManoObraModulo::class);
    }

    public function manoObraItems()
    {
        return $this->hasMany(ManoObraItem::class);
    }

=======
>>>>>>> b92c1913736957a2b206b43dc016d8445eeff9fc
}