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
        Schema::table('planificacion', function (Blueprint $table) {
            $table->boolean('en_curso')->default(false)->after('activo');
        });

        Schema::table('etapas', function (Blueprint $table) {
            $table->boolean('completada')->default(false)->after('distancia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planificacion', function (Blueprint $table) {
            $table->dropColumn('en_curso');
        });

        Schema::table('etapas', function (Blueprint $table) {
            $table->dropColumn('completada');
        });
    }
};
