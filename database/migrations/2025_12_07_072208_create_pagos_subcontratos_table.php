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
        Schema::create('pagos_subcontratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontrato_id')->constrained()->onDelete('cascade');
            $table->decimal('monto_pagado', 12, 2);
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
        Schema::dropIfExists('pagos_subcontratos');
    }
};
