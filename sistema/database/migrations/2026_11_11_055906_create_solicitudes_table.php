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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Identificación de la solicitud
            |--------------------------------------------------------------------------
            */

            $table->string('codigo', 50)->unique();

            $table->timestamp('fecha_ingreso')
                ->useCurrent();

            /*
            |--------------------------------------------------------------------------
            | Datos del solicitante
            |--------------------------------------------------------------------------
            |
            | El solicitante puede ser una persona distinta del estudiante
            | cuya documentación histórica se está buscando.
            |
            */

            $table->string('tipo_documento', 20)
                ->nullable();

            $table->string('numero_documento', 30)
                ->nullable();

            $table->string('nombres_solicitante', 150);

            $table->string('apellidos_solicitante', 150)
                ->nullable();

            $table->string('celular', 30)
                ->nullable();

            $table->string('correo', 254)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Datos declarados para la búsqueda
            |--------------------------------------------------------------------------
            |
            | Estos campos conservan exactamente la información proporcionada
            | por el solicitante, aunque posteriormente se identifique una
            | institución, estudiante o nivel dentro de SIGLAC.
            |
            */

            $table->string(
                'nombres_estudiante_declarados',
                250
            )->nullable();

            $table->string(
                'institucion_declarada',
                200
            )->nullable();

            $table->string(
                'nivel_declarado',
                50
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Registros identificados en SIGLAC
            |--------------------------------------------------------------------------
            |
            | Estas relaciones son opcionales porque una solicitud puede
            | registrarse antes de que el técnico logre identificar los
            | registros correspondientes.
            |
            */

            $table->foreignId('estudiante_id')
                ->nullable()
                ->constrained('estudiantes')
                ->nullOnDelete();

            $table->foreignId('institucion_educativa_id')
                ->nullable()
                ->constrained('instituciones_educativas')
                ->nullOnDelete();

            $table->foreignId('nivel_id')
                ->nullable()
                ->constrained('niveles')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Periodo declarado
            |--------------------------------------------------------------------------
            */

            $table->unsignedSmallInteger(
                'anio_inicio_declarado'
            )->nullable();

            $table->unsignedSmallInteger(
                'anio_fin_declarado'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Información académica declarada
            |--------------------------------------------------------------------------
            |
            | Valores previstos:
            | si
            | no
            | no_precisa
            |
            */

            $table->string(
                'declara_desaprobado',
                20
            )->default('no_precisa');

            /*
            |--------------------------------------------------------------------------
            | Seguimiento de la solicitud
            |--------------------------------------------------------------------------
            |
            | Estados previstos:
            | en_busqueda
            | observada
            | atendida
            | no_localizada
            |
            */

            $table->string('estado', 20)
                ->default('en_busqueda');

            $table->text('observaciones')
                ->nullable();

            $table->text('respuesta')
                ->nullable();

            $table->timestamp('fecha_cierre')
                ->nullable();

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
            | Índices
            |--------------------------------------------------------------------------
            */

            $table->index([
                'estado',
                'fecha_ingreso',
            ]);

            $table->index('numero_documento');

            $table->index([
                'anio_inicio_declarado',
                'anio_fin_declarado',
            ]);

            $table->index('estudiante_id');

            $table->index('institucion_educativa_id');

            $table->index('nivel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};