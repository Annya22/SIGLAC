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
        Schema::create('eventos_solicitud', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Solicitud
            |--------------------------------------------------------------------------
            */

            $table->foreignId('solicitud_id')
                ->constrained('solicitudes')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Usuario responsable
            |--------------------------------------------------------------------------
            */

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Tipo de evento
            |--------------------------------------------------------------------------
            |
            | Valores previstos:
            | registro
            | cambio_estado
            | observacion
            |
            */

            $table->string('tipo', 30);

            /*
            |--------------------------------------------------------------------------
            | Transición de estado
            |--------------------------------------------------------------------------
            |
            | Para una observación sin cambio de estado ambos pueden ser NULL.
            | Para un cambio de estado se registran el estado anterior
            | y el nuevo estado.
            |
            */

            $table->string('estado_anterior', 20)
                ->nullable();

            $table->string('estado_nuevo', 20)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Detalle del evento
            |--------------------------------------------------------------------------
            |
            | Aquí se conservará la explicación de observaciones,
            | cierres, reaperturas o cualquier información relevante
            | durante la atención.
            |
            */

            $table->text('detalle')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Fecha del evento
            |--------------------------------------------------------------------------
            */

            $table->timestamp('fecha')
                ->useCurrent();

            /*
            |--------------------------------------------------------------------------
            | Marcas de tiempo
            |--------------------------------------------------------------------------
            |
            | Los eventos serán tratados como registros históricos:
            | una vez creados no se editarán desde el sistema.
            |
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Índices
            |--------------------------------------------------------------------------
            */

            $table->index(
                [
                    'solicitud_id',
                    'fecha',
                ],
                'eventos_solicitud_fecha_idx'
            );

            $table->index('tipo');

            $table->index('estado_nuevo');

            $table->index('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_solicitud');
    }
};