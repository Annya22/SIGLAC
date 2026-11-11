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
        Schema::create('actas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tipo_acta_id')
                ->constrained('tipos_acta')
                ->restrictOnDelete();

            $table->foreignId('institucion_educativa_id')
                ->constrained('instituciones_educativas')
                ->restrictOnDelete();

            $table->foreignId('nivel_id')
                ->constrained('niveles')
                ->restrictOnDelete();

            $table->foreignId('grado_id')
                ->nullable()
                ->constrained('grados')
                ->restrictOnDelete();

            $table->foreignId('ubicacion_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->restrictOnDelete();

            $table->string('numero_acta', 50)->nullable();

            $table->unsignedSmallInteger('anio');

            $table->date('fecha_acta')->nullable();

            $table->string('seccion', 30)->nullable();

            $table->text('observaciones')->nullable();

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

            $table->index('tipo_acta_id');
            $table->index('institucion_educativa_id');
            $table->index('nivel_id');
            $table->index('grado_id');
            $table->index('ubicacion_id');
            $table->index('numero_acta');
            $table->index('anio');
            $table->index('fecha_acta');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actas');
    }
};