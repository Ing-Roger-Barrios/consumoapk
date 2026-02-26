<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            // Hacer nullable el campo antiguo ya que ahora usamos
            // mano_obra_item_id (ítem específico) o modulo_id (módulo completo)
            $table->foreignId('mano_obra_contrato_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            $table->foreignId('mano_obra_contrato_id')->nullable(false)->change();
        });
    }
};