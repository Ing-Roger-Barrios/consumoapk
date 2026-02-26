<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catálogo global de trabajadores (reutilizables entre proyectos)
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ci')->nullable();           // Cédula de identidad
            $table->string('cargo')->nullable();        // Ej: Albañil, Carpintero
            $table->decimal('salario_dia', 10, 2)->default(0); // Para jornal
            $table->decimal('hora_extra', 10, 2)->default(0);  // Valor hora extra
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Tabla pivote: trabajador asignado a un proyecto
        Schema::create('proyecto_trabajador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('trabajador_id')->constrained('trabajadores')->onDelete('cascade');
            $table->enum('tipo', ['jornal', 'item']); // tipo de contrato en este proyecto
            $table->timestamps();

            $table->unique(['proyecto_id', 'trabajador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto_trabajador');
        Schema::dropIfExists('trabajadores');
    }
};