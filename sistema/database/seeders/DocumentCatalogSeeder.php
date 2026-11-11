<?php

namespace Database\Seeders;

use App\Models\TipoActa;
use Illuminate\Database\Seeder;

class DocumentCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoActa::updateOrCreate(
            [
                'nombre' => 'Acta histórica',
            ],
            [
                'descripcion' =>
                    'Acta académica histórica registrada y localizada mediante SIGLAC.',
                'activo' => true,
            ]
        );

        TipoActa::updateOrCreate(
            [
                'nombre' => 'Acta de subsanación',
            ],
            [
                'descripcion' =>
                    'Acta correspondiente al registro de procesos de subsanación académica.',
                'activo' => true,
            ]
        );
    }
}