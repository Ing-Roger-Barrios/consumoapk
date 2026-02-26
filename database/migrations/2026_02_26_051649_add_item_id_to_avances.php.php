<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mano_obra_item_avance', function (Blueprint $table) {
            // Referencia al ítem específico (para avances de módulo completo)
            $table->foreignId('mano_obra_item_id')
                  ->nullable()
                  ->after('asignacion_id')
                  ->constrained('mano_obra_items')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('mano_obra_item_avance', function (Blueprint $table) {
            $table->dropForeign(['mano_obra_item_id']);
            $table->dropColumn('mano_obra_item_id');
        });
    }
};