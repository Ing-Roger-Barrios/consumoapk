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
        Schema::create('mano_obra_contrato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->string('descripcion'); // Ej: Albañilería, Carpintería
            $table->string('unidad', 50);
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unit', 12, 2);
            $table->decimal('monto_presupuestado', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mano_obra_contrato');
    }
};
