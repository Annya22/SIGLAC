<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Grado;
use App\Models\InstitucionEducativa;
use App\Models\Nivel;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    /**
     * Mostrar la búsqueda avanzada de documentos históricos.
     */
    public function index(Request $request)
    {
        $buscar = trim((string) $request->get('buscar', ''));
        $institucionId = $request->get('institucion_id');
        $nivelId = $request->get('nivel_id');
        $gradoId = $request->get('grado_id');
        $anio = trim((string) $request->get('anio', ''));

        /*
        |--------------------------------------------------------------------------
        | Consulta principal
        |--------------------------------------------------------------------------
        |
        | La búsqueda parte de las actas históricas y recupera sus principales
        | relaciones académicas y documentales.
        |
        */

        $query = Acta::query()
            ->with([
                'tipoActa',
                'institucionEducativa',
                'nivel',
                'grado',
                'ubicacion',
                'estudiantes',
            ])
            ->where('activo', true);

        /*
        |--------------------------------------------------------------------------
        | Búsqueda general
        |--------------------------------------------------------------------------
        |
        | Permite buscar utilizando un único campo por:
        | - número de acta;
        | - institución educativa;
        | - nombres del estudiante;
        | - apellidos del estudiante;
        | - número de documento.
        |
        */

        if ($buscar !== '') {
            $query->where(function ($subquery) use ($buscar) {

                $subquery
                    ->where(
                        'numero_acta',
                        'like',
                        "%{$buscar}%"
                    )

                    ->orWhereHas(
                        'institucionEducativa',
                        function ($institucionQuery) use ($buscar) {
                            $institucionQuery->where(
                                'nombre',
                                'like',
                                "%{$buscar}%"
                            );
                        }
                    )

                    ->orWhereHas(
                        'estudiantes',
                        function ($estudianteQuery) use ($buscar) {
                            $estudianteQuery
                                ->where(
                                    'nombres',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'apellido_paterno',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'apellido_materno',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'numero_documento',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhereRaw(
                                    "CONCAT_WS(
                                        ' ',
                                        apellido_paterno,
                                        apellido_materno,
                                        nombres
                                    ) LIKE ?",
                                    ["%{$buscar}%"]
                                )
                                ->orWhereRaw(
                                    "CONCAT_WS(
                                        ' ',
                                        nombres,
                                        apellido_paterno,
                                        apellido_materno
                                    ) LIKE ?",
                                    ["%{$buscar}%"]
                                );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filtro por institución educativa
        |--------------------------------------------------------------------------
        */

        if (! empty($institucionId)) {
            $query->where(
                'institucion_educativa_id',
                $institucionId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtro por nivel
        |--------------------------------------------------------------------------
        */

        if (! empty($nivelId)) {
            $query->where(
                'nivel_id',
                $nivelId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtro por grado
        |--------------------------------------------------------------------------
        */

        if (! empty($gradoId)) {
            $query->where(
                'grado_id',
                $gradoId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtro por año
        |--------------------------------------------------------------------------
        */

        if ($anio !== '') {
            $query->where(
                'anio',
                $anio
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resultados
        |--------------------------------------------------------------------------
        */

        $actas = $query
            ->orderByDesc('anio')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Catálogos para los filtros
        |--------------------------------------------------------------------------
        */

        $instituciones = InstitucionEducativa::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $niveles = Nivel::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $grados = Grado::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'busqueda.index',
            compact(
                'actas',
                'instituciones',
                'niveles',
                'grados',
                'buscar',
                'institucionId',
                'nivelId',
                'gradoId',
                'anio'
            )
        );
    }
}