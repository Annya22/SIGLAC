<?php

namespace App\Http\Controllers;

use App\Models\InstitucionEducativa;
use App\Models\InstitucionNivel;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstitucionEducativaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado y búsqueda
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('buscar'));

        $instituciones = InstitucionEducativa::query()
            ->with([
                'niveles' => function ($query) {
                    $query->wherePivot('activo', true)
                        ->orderBy('orden');
                },
            ])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where(
                        'codigo_modular',
                        'like',
                        "%{$busqueda}%"
                    )
                    ->orWhere(
                        'nombre',
                        'like',
                        "%{$busqueda}%"
                    );
                });
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view(
            'instituciones.index',
            compact(
                'instituciones',
                'busqueda'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario de creación
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $puedeAsignarNiveles = Auth::user()
            ->hasPermission('instituciones.asignar_niveles');

        $niveles = collect();

        if ($puedeAsignarNiveles) {
            $niveles = Nivel::where('activo', true)
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get();
        }

        return view(
            'instituciones.create',
            compact(
                'niveles',
                'puedeAsignarNiveles'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar institución
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $puedeAsignarNiveles = Auth::user()
            ->hasPermission('instituciones.asignar_niveles');

        $reglas = [
            'codigo_modular' => [
                'nullable',
                'string',
                'max:20',
                'unique:instituciones_educativas,codigo_modular',
            ],

            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
        ];

        if ($puedeAsignarNiveles) {
            $reglas['niveles'] = [
                'nullable',
                'array',
            ];

            $reglas['niveles.*'] = [
                'integer',
                'distinct',
                Rule::exists('niveles', 'id')->where(
                    fn ($query) => $query->where('activo', true)
                ),
            ];
        }

        $datos = $request->validate(
            $reglas,
            [
                'codigo_modular.unique' =>
                    'Ya existe una institución educativa con ese código modular.',

                'nombre.required' =>
                    'El nombre de la institución educativa es obligatorio.',

                'niveles.*.exists' =>
                    'Uno de los niveles seleccionados no es válido.',
            ]
        );

        DB::transaction(function () use (
            $datos,
            $puedeAsignarNiveles
        ) {
            $institucion = InstitucionEducativa::create([
                'codigo_modular' =>
                    $this->normalizarCodigoModular(
                        $datos['codigo_modular'] ?? null
                    ),

                'nombre' =>
                    trim($datos['nombre']),

                'activo' => true,

                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            if ($puedeAsignarNiveles) {
                foreach ($datos['niveles'] ?? [] as $nivelId) {
                    InstitucionNivel::create([
                        'institucion_educativa_id' =>
                            $institucion->id,

                        'nivel_id' =>
                            $nivelId,

                        'activo' =>
                            true,

                        'created_by' =>
                            Auth::id(),

                        'updated_by' =>
                            Auth::id(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('instituciones.index')
            ->with(
                'success',
                'Institución educativa registrada correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario de edición
    |--------------------------------------------------------------------------
    */
    public function edit(
        InstitucionEducativa $institucion
    ) {
        $puedeAsignarNiveles = Auth::user()
            ->hasPermission('instituciones.asignar_niveles');

        $niveles = collect();
        $nivelesAsignados = [];

        if ($puedeAsignarNiveles) {
            $niveles = Nivel::where('activo', true)
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get();

            $nivelesAsignados = $institucion
                ->institucionNiveles()
                ->where('activo', true)
                ->pluck('nivel_id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->toArray();
        }

        return view(
            'instituciones.edit',
            compact(
                'institucion',
                'niveles',
                'nivelesAsignados',
                'puedeAsignarNiveles'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar institución
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        InstitucionEducativa $institucion
    ) {
        $puedeAsignarNiveles = Auth::user()
            ->hasPermission('instituciones.asignar_niveles');

        $reglas = [
            'codigo_modular' => [
                'nullable',
                'string',
                'max:20',

                Rule::unique(
                    'instituciones_educativas',
                    'codigo_modular'
                )->ignore($institucion->id),
            ],

            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
        ];

        if ($puedeAsignarNiveles) {
            $reglas['niveles'] = [
                'nullable',
                'array',
            ];

            $reglas['niveles.*'] = [
                'integer',
                'distinct',

                Rule::exists('niveles', 'id')->where(
                    fn ($query) => $query->where('activo', true)
                ),
            ];
        }

        $datos = $request->validate(
            $reglas,
            [
                'codigo_modular.unique' =>
                    'Ya existe otra institución educativa con ese código modular.',

                'nombre.required' =>
                    'El nombre de la institución educativa es obligatorio.',

                'niveles.*.exists' =>
                    'Uno de los niveles seleccionados no es válido.',
            ]
        );

        DB::transaction(function () use (
            $datos,
            $institucion,
            $puedeAsignarNiveles
        ) {
            $institucion->update([
                'codigo_modular' =>
                    $this->normalizarCodigoModular(
                        $datos['codigo_modular'] ?? null
                    ),

                'nombre' =>
                    trim($datos['nombre']),

                'updated_by' =>
                    Auth::id(),
            ]);

            if ($puedeAsignarNiveles) {
                $nivelesSeleccionados = collect(
                    $datos['niveles'] ?? []
                )
                    ->map(
                        fn ($id) => (int) $id
                    )
                    ->unique()
                    ->values()
                    ->all();

                /*
                |--------------------------------------------------------------------------
                | Desactivar relaciones que ya no fueron seleccionadas
                |--------------------------------------------------------------------------
                */
                $relacionesActivas = InstitucionNivel::where(
                    'institucion_educativa_id',
                    $institucion->id
                )
                    ->where('activo', true);

                if (count($nivelesSeleccionados) > 0) {
                    $relacionesActivas->whereNotIn(
                        'nivel_id',
                        $nivelesSeleccionados
                    );
                }

                $relacionesActivas->update([
                    'activo' => false,
                    'updated_by' => Auth::id(),
                    'updated_at' => now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Crear o reactivar niveles seleccionados
                |--------------------------------------------------------------------------
                */
                foreach ($nivelesSeleccionados as $nivelId) {
                    $relacion = InstitucionNivel::firstOrNew([
                        'institucion_educativa_id' =>
                            $institucion->id,

                        'nivel_id' =>
                            $nivelId,
                    ]);

                    if (!$relacion->exists) {
                        $relacion->created_by = Auth::id();
                    }

                    $relacion->activo = true;
                    $relacion->updated_by = Auth::id();

                    $relacion->save();
                }
            }
        });

        return redirect()
            ->route('instituciones.index')
            ->with(
                'success',
                'Institución educativa actualizada correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Activar o desactivar institución
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(
        InstitucionEducativa $institucion
    ) {
        $institucion->update([
            'activo' => !$institucion->activo,
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            $institucion->activo
                ? 'Institución educativa activada correctamente.'
                : 'Institución educativa desactivada correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Normalizar código modular
    |--------------------------------------------------------------------------
    */
    private function normalizarCodigoModular(
        ?string $codigo
    ): ?string {
        if ($codigo === null) {
            return null;
        }

        $codigo = trim($codigo);

        return $codigo === ''
            ? null
            : $codigo;
    }
    }