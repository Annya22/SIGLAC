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
        Schema::create('solicitud_actas', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones principales
            |--------------------------------------------------------------------------
            */

            $table->foreignId('solicitud_id')
                ->constrained('solicitudes')
                ->cascadeOnDelete();

            $table->foreignId('acta_id')
                ->constrained('actas')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información del vínculo
            |--------------------------------------------------------------------------
            |
            | Permite registrar qué aporte tiene el acta localizada
            | dentro de la atención de la solicitud.
            |
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
            |
            | Una misma acta no debe vincularse dos veces
            | a la misma solicitud.
            |
            */

            $table->unique(
                [
                    'solicitud_id',
                    'acta_id',
                ],
                'solicitud_acta_unica'
            );

            $table->index('solicitud_id');
            $table->index('acta_id');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_actas');
    }
};