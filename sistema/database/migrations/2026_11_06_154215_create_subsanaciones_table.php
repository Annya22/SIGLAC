<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subsanaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('curso_desaprobado_id')
                ->constrained('cursos_desaprobados')
                ->restrictOnDelete();

            $table->foreignId('acta_subsanacion_id')
                ->constrained('actas')
                ->restrictOnDelete();

            $table->date('fecha_subsanacion')
                ->nullable();

            $table->string('resultado', 30);

            $table->string('calificacion', 20)
                ->nullable();

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

            $table->index('curso_desaprobado_id');
            $table->index('acta_subsanacion_id');
            $table->index('resultado');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subsanaciones');
    }
};