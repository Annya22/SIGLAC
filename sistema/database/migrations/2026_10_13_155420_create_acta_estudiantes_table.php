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
        Schema::create('acta_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('acta_id')
                ->constrained('actas')
                ->restrictOnDelete();

            $table->foreignId('estudiante_id')
                ->constrained('estudiantes')
                ->restrictOnDelete();

            $table->boolean('activo')->default(true);

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
                ['acta_id', 'estudiante_id'],
                'acta_estudiante_unique'
            );

            $table->index('acta_id');
            $table->index('estudiante_id');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_estudiantes');
    }
};