<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Asignación de trabajador a un ítem de mano_obra_contrato
        Schema::create('mano_obra_item_asignacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('mano_obra_contrato_id')->constrained('mano_obra_contrato')->onDelete('cascade');
            $table->foreignId('trabajador_id')->constrained('trabajadores')->onDelete('cascade');
            $table->decimal('monto_acordado', 12, 2); // Puede ser distinto al contrato
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Registro de avances/pagos parciales por ítem
        Schema::create('mano_obra_item_avance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignacion_id')->constrained('mano_obra_item_asignacion')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('porcentaje_avance', 5, 2); // Ej: 40.00 = 40%
            $table->decimal('monto_pagar', 12, 2);      // Calculado: monto_acordado * porcentaje / 100
            $table->string('foto1')->nullable();         // URL Cloudinary
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mano_obra_item_avance');
        Schema::dropIfExists('mano_obra_item_asignacion');
    }
};