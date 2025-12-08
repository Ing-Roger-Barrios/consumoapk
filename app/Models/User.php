<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // Relación: ¿quiénes creó este usuario?
    public function residents()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // Relación: ¿quién lo creó?
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accesor: ¿es contratista?
    public function isContractor()
    {
        return $this->role === 'contractor';
    }

    // Accesor: ¿es residente?
    public function isResident()
    {
        return $this->role === 'resident';
    }



    // Dentro de la clase User
    public function proyectosAsignados()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_residente', 'residente_id', 'proyecto_id');
    }

    // También puedes mantener:
    public function proyectosCreados() // los que creó como contractor
    {
        return $this->hasMany(Proyecto::class, 'user_id');
    }
}
