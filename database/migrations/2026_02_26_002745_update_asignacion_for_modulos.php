<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Actualizar la tabla de asignaciones para apuntar al nuevo modelo
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            // Agregar columna para el nuevo modelo de ítem
            $table->foreignId('mano_obra_item_id')
                  ->nullable()
                  ->after('mano_obra_contrato_id')
                  ->constrained('mano_obra_items')
                  ->onDelete('cascade');

            // Agregar columna para asignación a módulo completo
            $table->foreignId('modulo_id')
                  ->nullable()
                  ->after('mano_obra_item_id')
                  ->constrained('mano_obra_modulos')
                  ->onDelete('cascade');

            // tipo_asignacion: 'item' o 'modulo'
            $table->enum('tipo_asignacion', ['item', 'modulo'])->default('item')->after('modulo_id');
        });
    }

    public function down(): void
    {
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            $table->dropForeign(['mano_obra_item_id']);
            $table->dropForeign(['modulo_id']);
            $table->dropColumn(['mano_obra_item_id', 'modulo_id', 'tipo_asignacion']);
        });
    }
};