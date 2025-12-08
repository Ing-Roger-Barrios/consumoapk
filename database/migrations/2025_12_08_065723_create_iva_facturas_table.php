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
        Schema::create('iva_facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->string('numero_factura'); // Número de factura
            $table->decimal('monto_factura', 12, 2); // Monto de la factura base
            $table->decimal('porcentaje_iva', 5, 2)->default(13.00); // Porcentaje IVA (Bolivia: 13%)
            $table->decimal('monto_iva', 12, 2); // Monto IVA calculado
            $table->date('fecha_factura');
            $table->longText('comprobante')->nullable(); // Comprobante de la factura
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iva_facturas');
    }
};
