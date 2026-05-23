<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion', function (Blueprint $blueprint) {
            $blueprint->foreignId('original_id')
                ->nullable()
                ->constrained('planificacion')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planificacion', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['original_id']);
            $blueprint->dropColumn('original_id');
        });
    }
};
