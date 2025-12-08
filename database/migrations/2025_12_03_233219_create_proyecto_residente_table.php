<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyecto_residente', function (Blueprint $table) {
             
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('residente_id')->constrained('users')->onDelete('cascade');

            // Opcional: si un residente puede estar en el mismo proyecto más de una vez con diferentes roles, agrega ID.
            // Pero si solo puede estar una vez, no necesitas ID primario.
            $table->primary(['proyecto_id', 'residente_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_residente');
    }
};
