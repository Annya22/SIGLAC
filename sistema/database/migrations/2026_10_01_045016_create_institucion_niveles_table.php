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
        Schema::create('institucion_niveles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('institucion_educativa_id')
                ->constrained('instituciones_educativas')
                ->restrictOnDelete();

            $table->foreignId('nivel_id')
                ->constrained('niveles')
                ->restrictOnDelete();

            $table->boolean('activo')
                ->default(true);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['institucion_educativa_id', 'nivel_id'],
                'institucion_nivel_unique'
            );

            $table->index('institucion_educativa_id');
            $table->index('nivel_id');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucion_niveles');
    }
};
