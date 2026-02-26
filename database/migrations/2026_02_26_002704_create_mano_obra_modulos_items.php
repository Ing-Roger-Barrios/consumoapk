<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MÓDULOS: M01, M02, M03...
        Schema::create('mano_obra_modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->string('codigo');           // Ej: M01, M02
            $table->string('nombre');           // Ej: OBRAS PRELIMINARES
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // ÍTEMS dentro de cada módulo
        Schema::create('mano_obra_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('mano_obra_modulos')->onDelete('cascade');
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->integer('numero');          // Nº del ítem (1, 2, 3...)
            $table->text('descripcion');
            $table->string('unidad', 50);
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('parcial', 12, 2);  // cantidad * precio_unitario
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mano_obra_items');
        Schema::dropIfExists('mano_obra_modulos');
    }
};