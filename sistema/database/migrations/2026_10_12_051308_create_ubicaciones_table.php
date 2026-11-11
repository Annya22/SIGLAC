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
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();

            $table->string('ambiente', 100);

            $table->string('estante', 50)->nullable();

            $table->string('caja', 50)->nullable();

            $table->string('descripcion', 255)->nullable();

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

            $table->index('ambiente');
            $table->index('estante');
            $table->index('caja');
            $table->index('activo');

            $table->unique(
                ['ambiente', 'estante', 'caja'],
                'ubicaciones_ambiente_estante_caja_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
