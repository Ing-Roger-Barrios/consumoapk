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
        Schema::create('equipo_maquinaria_ejecucion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->text('descripcion');
            $table->string('unidad', 50);
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unit', 12, 2);
            $table->decimal('total', 12, 2);
            $table->longText('comprobante')->nullable(); // Comprobante obligatorio para ejecución
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_maquinaria_ejecucion');
    }
};
