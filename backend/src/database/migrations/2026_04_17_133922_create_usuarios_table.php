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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nick', 50)->unique();
            $table->string('nombre', 100);
            $table->string('apellidos', 150);
            $table->enum('rol', ['admin', 'usuario'])->default('usuario');
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
