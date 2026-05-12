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
        Schema::create('etapas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planificacion_id')->constrained('planificacion')->onDelete('cascade');
            $table->integer('dia');
            $table->foreignId('localizacion_inicio_id')->constrained('localizaciones');
            $table->foreignId('localizacion_fin_id')->constrained('localizaciones');
            $table->decimal('distancia', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas');
    }
};
