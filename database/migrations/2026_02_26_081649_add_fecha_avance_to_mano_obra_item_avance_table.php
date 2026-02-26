<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('mano_obra_item_avance', function (Blueprint $table) {
        $table->date('fecha_avance')->nullable()->after('monto_pagar');
    });
}

public function down()
{
    Schema::table('mano_obra_item_avance', function (Blueprint $table) {
        $table->dropColumn('fecha_avance');
    });
}
};

