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
        Schema::create('cursos_desaprobados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('acta_estudiante_id')
                ->constrained('acta_estudiantes')
                ->restrictOnDelete();

            $table->foreignId('asignatura_id')
                ->constrained('asignaturas')
                ->restrictOnDelete();

            $table->string('observacion', 255)
                ->nullable();

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
                [
                    'acta_estudiante_id',
                    'asignatura_id',
                ],
                'curso_desaprobado_unico'
            );

            $table->index('acta_estudiante_id');
            $table->index('asignatura_id');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos_desaprobados');
    }
};