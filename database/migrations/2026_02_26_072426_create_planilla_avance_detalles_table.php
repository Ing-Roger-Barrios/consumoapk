<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('planilla_avance_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('planilla_avance_id')
                  ->constrained('planillas_avance')
                  ->cascadeOnDelete();

            $table->foreignId('trabajador_id')
                ->constrained('trabajadores')
                ->cascadeOnDelete();

            $table->decimal('total_monto', 14, 2)->default(0);
            $table->decimal('total_porcentaje', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planilla_avance_detalles');
    }
};
