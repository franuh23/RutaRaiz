<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planificacion_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('planificacion_id')->constrained('planificacion')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['usuario_id', 'planificacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planificacion_likes');
    }
};
