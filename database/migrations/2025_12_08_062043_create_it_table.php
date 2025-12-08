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
        Schema::create('it', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->decimal('porcentaje', 5, 2)->default(14.94); // Porcentaje editable (ej: 14.94%)
            $table->decimal('monto', 12, 2)->nullable(); // Calculado automáticamente
            $table->longText('comprobante')->nullable(); // Comprobante de pago
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it');
    }
};
