<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion', function (Blueprint $table) {
            // Añadimos el booleano. Al ser default(false), tus rutas actuales no se romperán
            $table->boolean('is_public')->default(false)->after('dias_totales');
        });
    }

    public function down(): void
    {
        Schema::table('planificacion', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
