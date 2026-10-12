<?php

namespace Database\Seeders;

use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Database\Seeder;

class AcademicCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Nivel Primaria
        |--------------------------------------------------------------------------
        */
        $primaria = Nivel::updateOrCreate(
            [
                'nombre' => 'Primaria',
            ],
            [
                'orden' => 1,
                'activo' => true,
            ]
        );

        $gradosPrimaria = [
            ['nombre' => '1.º', 'orden' => 1],
            ['nombre' => '2.º', 'orden' => 2],
            ['nombre' => '3.º', 'orden' => 3],
            ['nombre' => '4.º', 'orden' => 4],
            ['nombre' => '5.º', 'orden' => 5],
            ['nombre' => '6.º', 'orden' => 6],
        ];

        foreach ($gradosPrimaria as $grado) {
            Grado::updateOrCreate(
                [
                    'nivel_id' => $primaria->id,
                    'nombre' => $grado['nombre'],
                ],
                [
                    'orden' => $grado['orden'],
                    'activo' => true,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Nivel Secundaria
        |--------------------------------------------------------------------------
        */
        $secundaria = Nivel::updateOrCreate(
            [
                'nombre' => 'Secundaria',
            ],
            [
                'orden' => 2,
                'activo' => true,
            ]
        );

        $gradosSecundaria = [
            ['nombre' => '1.º', 'orden' => 1],
            ['nombre' => '2.º', 'orden' => 2],
            ['nombre' => '3.º', 'orden' => 3],
            ['nombre' => '4.º', 'orden' => 4],
            ['nombre' => '5.º', 'orden' => 5],
        ];

        foreach ($gradosSecundaria as $grado) {
            Grado::updateOrCreate(
                [
                    'nivel_id' => $secundaria->id,
                    'nombre' => $grado['nombre'],
                ],
                [
                    'orden' => $grado['orden'],
                    'activo' => true,
                ]
            );
        }
    }
}
