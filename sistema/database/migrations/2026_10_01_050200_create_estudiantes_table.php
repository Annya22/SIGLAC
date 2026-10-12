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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_documento', 30)
                ->nullable();

            $table->string('numero_documento', 30)
                ->nullable();

            $table->string('apellido_paterno', 100);

            $table->string('apellido_materno', 100)
                ->nullable();

            $table->string('nombres', 150);

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
            | Restricciones e índices
            |--------------------------------------------------------------------------
            */

            $table->unique(
                ['tipo_documento', 'numero_documento'],
                'estudiantes_documento_unique'
            );

            $table->index('numero_documento');
            $table->index('apellido_paterno');
            $table->index('apellido_materno');
            $table->index('nombres');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
