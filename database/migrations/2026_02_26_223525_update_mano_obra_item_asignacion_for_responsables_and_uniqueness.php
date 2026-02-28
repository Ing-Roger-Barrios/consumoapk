<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Añadido para la limpieza

return new class extends Migration
{
    public function up(): void
    {
        // 1. Solo agregamos la columna si NO existe
        if (!Schema::hasColumn('mano_obra_item_asignacion', 'subcontrato_id')) {
            Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
                $table->foreignId('subcontrato_id')
                    ->nullable()
                    ->after('trabajador_id')
                    ->constrained('subcontratos')
                    ->nullOnDelete();
            });
        }

        // 2. Cambiamos la propiedad de trabajador_id (esto no suele dar error si ya es nullable)
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            $table->foreignId('trabajador_id')->nullable()->change();
        });

        // 3. LIMPIEZA CRÍTICA: Borramos los duplicados antes de crear el índice único
        // Esto evita el error "Duplicate entry" que tuviste primero
        DB::statement("
            DELETE t1 FROM mano_obra_item_asignacion t1
            INNER JOIN mano_obra_item_asignacion t2 
            WHERE t1.id < t2.id 
            AND t1.proyecto_id = t2.proyecto_id 
            AND t1.tipo_asignacion = t2.tipo_asignacion 
            AND (t1.mano_obra_item_id = t2.mano_obra_item_id OR (t1.mano_obra_item_id IS NULL AND t2.mano_obra_item_id IS NULL))
        ");

        // 4. Ahora sí, intentamos crear los índices
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            // Usamos un try-catch o verificamos si el índice existe para que no truene
            try {
                $table->unique(['proyecto_id', 'tipo_asignacion', 'mano_obra_item_id'], 'uq_mano_obra_item_unico');
                $table->unique(['proyecto_id', 'tipo_asignacion', 'modulo_id'], 'uq_mano_obra_modulo_unico');
            } catch (\Exception $e) {
                // Si ya existen, simplemente ignoramos el error
            }
        });
    }

    public function down(): void
    {
        Schema::table('mano_obra_item_asignacion', function (Blueprint $table) {
            $table->dropUnique('uq_mano_obra_item_unico');
            $table->dropUnique('uq_mano_obra_modulo_unico');
            $table->dropConstrainedForeignId('subcontrato_id');
            $table->foreignId('trabajador_id')->nullable(false)->change();
        });
    }
};