<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Encabezado de planilla semanal
        Schema::create('planillas_jornal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->date('semana_inicio');   // Lunes de la semana
            $table->date('semana_fin');      // Sábado de la semana
            $table->string('observaciones')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Detalle por trabajador en esa planilla
        Schema::create('planilla_jornal_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planilla_id')->constrained('planillas_jornal')->onDelete('cascade');
            $table->foreignId('trabajador_id')->constrained('trabajadores')->onDelete('cascade');

            // Asistencia: 1 = presente, 0 = falta, 0.5 = medio día
            $table->decimal('lunes',    3, 1)->default(0);
            $table->decimal('martes',   3, 1)->default(0);
            $table->decimal('miercoles',3, 1)->default(0);
            $table->decimal('jueves',   3, 1)->default(0);
            $table->decimal('viernes',  3, 1)->default(0);
            $table->decimal('sabado',   3, 1)->default(0);

            // Horas extra por día
            $table->decimal('hs_extra_lunes',    5, 2)->default(0);
            $table->decimal('hs_extra_martes',   5, 2)->default(0);
            $table->decimal('hs_extra_miercoles',5, 2)->default(0);
            $table->decimal('hs_extra_jueves',   5, 2)->default(0);
            $table->decimal('hs_extra_viernes',  5, 2)->default(0);
            $table->decimal('hs_extra_sabado',   5, 2)->default(0);

            // Descuentos
            $table->decimal('descuento_anticipo', 10, 2)->default(0);
            $table->decimal('descuento_otros',    10, 2)->default(0);
            $table->string('descuento_notas')->nullable();

            // Snapshot del salario al momento del registro (por si cambia después)
            $table->decimal('salario_dia_snapshot', 10, 2)->default(0);
            $table->decimal('hora_extra_snapshot',  10, 2)->default(0);

            // Totales calculados
            $table->decimal('total_dias',     5, 1)->storedAs(
                'lunes + martes + miercoles + jueves + viernes + sabado'
            );
            $table->decimal('total_hs_extra', 8, 2)->storedAs(
                'hs_extra_lunes + hs_extra_martes + hs_extra_miercoles + hs_extra_jueves + hs_extra_viernes + hs_extra_sabado'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planilla_jornal_detalle');
        Schema::dropIfExists('planillas_jornal');
    }
};