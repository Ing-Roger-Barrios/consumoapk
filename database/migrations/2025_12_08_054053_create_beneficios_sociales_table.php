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
        Schema::create('beneficios_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->string('tipo_beneficio'); // Ej: Bono de producción, Utilidades, Aguinaldo, etc.
            $table->decimal('monto', 12, 2);
            $table->date('fecha_pago');
            $table->longText('comprobante')->nullable(); // Ruta del archivo
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficios_sociales');
    }
};
