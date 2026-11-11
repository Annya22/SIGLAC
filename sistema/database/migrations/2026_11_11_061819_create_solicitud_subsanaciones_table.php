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
        Schema::create('solicitud_subsanaciones', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones principales
            |--------------------------------------------------------------------------
            */

            $table->foreignId('solicitud_id')
                ->constrained('solicitudes')
                ->cascadeOnDelete();

            $table->foreignId('subsanacion_id')
                ->constrained('subsanaciones')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información del vínculo
            |--------------------------------------------------------------------------
            */

            $table->text('observaciones')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Auditoría
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Restricciones e índices
            |--------------------------------------------------------------------------
            */

            $table->unique(
                [
                    'solicitud_id',
                    'subsanacion_id',
                ],
                'solicitud_subsanacion_unica'
            );

            $table->index('solicitud_id');
            $table->index('subsanacion_id');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_subsanaciones');
    }
};