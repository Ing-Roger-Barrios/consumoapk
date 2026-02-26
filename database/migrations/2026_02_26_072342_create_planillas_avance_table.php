<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('planillas_avance', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->date('semana_inicio');
            $table->date('semana_fin');

            $table->decimal('total_pagar', 14, 2)->default(0);

            $table->string('archivo_constancia')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planillas_avance');
    }
};