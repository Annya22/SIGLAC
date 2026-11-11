<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digitalizaciones', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Vinculación con documento estructurado
            |--------------------------------------------------------------------------
            |
            | Puede ser nulo porque el documento puede digitalizarse primero
            | y vincularse o crear el acta después de la revisión OCR.
            |
            */

            $table->foreignId('acta_id')
                ->nullable()
                ->constrained('actas')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Clasificación del documento
            |--------------------------------------------------------------------------
            */

            $table->string('tipo_documento', 50);

            /*
            |--------------------------------------------------------------------------
            | Archivo digital
            |--------------------------------------------------------------------------
            */

            $table->string('nombre_original', 255);

            $table->string('nombre_archivo', 255);

            $table->string('ruta_archivo', 500);

            $table->string('mime_type', 100);

            $table->unsignedBigInteger('tamanio_bytes');

            $table->unsignedInteger('numero_paginas')
                ->nullable();

            $table->string('hash_sha256', 64)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Procesamiento OCR
            |--------------------------------------------------------------------------
            */

            $table->string('motor_ocr', 100)
                ->nullable();

            $table->string('estado_ocr', 30)
                ->default('pendiente');

            $table->longText('texto_ocr')
                ->nullable();

            $table->json('datos_extraidos')
                ->nullable();

            $table->decimal('confianza_ocr', 5, 2)
                ->nullable();

            $table->text('error_ocr')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Revisión humana
            |--------------------------------------------------------------------------
            */

            $table->string('estado_revision', 30)
                ->default('pendiente');

            $table->foreignId('revisado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('fecha_revision')
                ->nullable();

            $table->text('observacion_revision')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Estado y auditoría
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Índices
            |--------------------------------------------------------------------------
            */

            $table->index('acta_id');
            $table->index('tipo_documento');
            $table->index('estado_ocr');
            $table->index('estado_revision');
            $table->index('hash_sha256');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digitalizaciones');
    }
};