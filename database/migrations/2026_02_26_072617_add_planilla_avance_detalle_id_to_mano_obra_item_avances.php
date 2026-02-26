<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mano_obra_item_avance', function (Blueprint $table) {
            $table->foreignId('planilla_avance_detalle_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('planilla_avance_detalles')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mano_obra_item_avance', function (Blueprint $table) {
            $table->dropConstrainedForeignId('planilla_avance_detalle_id');
        });
    }
};
