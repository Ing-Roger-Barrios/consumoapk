<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id(); // ID autoincremental (big int, primary key)

            $table->string('nombre'); // Nombre del proyecto
            $table->string('cliente'); // Nombre del cliente
            $table->string('ubicacion')->nullable(); // Ubicación, puede ser opcional
            $table->string('residente')->nullable(); // Residente a cargo, puede ser opcional
            
            // Para el monto, se recomienda usar 'decimal' para precisión exacta (moneda)
            // precision: 10 dígitos en total, scale: 2 decimales
            $table->decimal('monto', 10, 2); 

            // Campo para la clave foránea user_id. 
            // 'foreignId' es la forma moderna y recomendada en Laravel para esto.
            // constrained() asume que la tabla referenciada es 'users'.
            // cascadeOnDelete() significa que si el usuario se elimina, sus proyectos también.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps(); // Columnas created_at y updated_at automáticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
