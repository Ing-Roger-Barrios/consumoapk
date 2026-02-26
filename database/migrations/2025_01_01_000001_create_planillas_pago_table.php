<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planillas_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->string('numero_planilla'); // Ej: "Planilla #1", "Anticipo", "Pago Final"
            $table->string('concepto');        // Descripción del pago
            $table->decimal('monto', 12, 2);
            $table->date('fecha_pago');
            $table->string('comprobante')->nullable(); // URL Cloudinary
            $table->text('notas')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planillas_pago');
    }
};