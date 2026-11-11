<?php

namespace App\Http\Controllers;

use App\Models\ActaEstudiante;
use App\Models\Asignatura;
use App\Models\CursoDesaprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CursoDesaprobadoController extends Controller
{
    /**
     * Mostrar el listado de cursos desaprobados.
     */
    public function index(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('cursos_desaprobados.ver'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $cursosDesaprobados = CursoDesaprobado::query()
            ->with([
                'asignatura',
                'actaEstudiante.estudiante',
                'actaEstudiante.acta.institucionEducativa',
                'actaEstudiante.acta.nivel',
                'actaEstudiante.acta.grado',
            ])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('observacion', 'like', "%{$buscar}%")
                        ->orWhereHas('asignatura', function ($q) use ($buscar) {
                            $q->where(
                                'nombre',
                                'like',
                                "%{$buscar}%"
                            );
                        })
                        ->orWhereHas(
                            'actaEstudiante.estudiante',
                            function ($q) use ($buscar) {
                                $q->where(
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
                                        "CONCAT_WS(' ', apellido_paterno, apellido_materno, nombres) LIKE ?",
                                        ["%{$buscar}%"]
                                    );
                            }
                        )
                        ->orWhereHas(
                            'actaEstudiante.acta',
                            function ($q) use ($buscar) {
                                $q->where(
                                    'numero_acta',
                                    'like',
                                    "%{$buscar}%"
                                )
                                    ->orWhere(
                                        'anio',
                                        'like',
                                        "%{$buscar}%"
                                    );
                            }
                        )
                        ->orWhereHas(
                            'actaEstudiante.acta.institucionEducativa',
                            function ($q) use ($buscar) {
                                $q->where(
                                    'nombre',
                                    'like',
                                    "%{$buscar}%"
                                )
                                    ->orWhere(
                                        'codigo_modular',
                                        'like',
                                        "%{$buscar}%"
                                    );
                            }
                        );
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'cursos-desaprobados.index',
            compact(
                'cursosDesaprobados',
                'buscar'
            )
        );
    }

    /**
     * Mostrar el formulario de registro.
     */
    public function create()
    {
        abort_unless(
            Auth::user()?->hasPermission('cursos_desaprobados.crear'),
            403
        );

        $catalogos = $this->cargarCatalogos();

        return view(
            'cursos-desaprobados.create',
            $catalogos
        );
    }

    /**
     * Registrar un curso desaprobado.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('cursos_desaprobados.crear'),
            403
        );

        $datos = $this->validarDatos($request);

        $this->validarRelaciones($datos);

        $datos = $this->normalizarDatos($datos);

        $datos['activo'] = true;
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();

        CursoDesaprobado::create($datos);

        return redirect()
            ->route('cursos-desaprobados.index')
            ->with(
                'success',
                'Curso desaprobado registrado correctamente.'
            );
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(CursoDesaprobado $cursoDesaprobado)
    {
        abort_unless(
            Auth::user()?->hasPermission('cursos_desaprobados.editar'),
            403
        );

        $cursoDesaprobado->load([
            'actaEstudiante.estudiante',
            'actaEstudiante.acta',
            'asignatura',
        ]);

        $catalogos = $this->cargarCatalogos(
            $cursoDesaprobado
        );

        return view(
            'cursos-desaprobados.edit',
            array_merge(
                $catalogos,
                [
                    'cursoDesaprobado' => $cursoDesaprobado,
                ]
            )
        );
    }

    /**
     * Actualizar un curso desaprobado.
     */
    public function update(
        Request $request,
        CursoDesaprobado $cursoDesaprobado
    ) {
        abort_unless(
            Auth::user()?->hasPermission('cursos_desaprobados.editar'),
            403
        );

        $datos = $this->validarDatos($request);

        $this->validarRelaciones(
            $datos,
            $cursoDesaprobado
        );

        $datos = $this->normalizarDatos($datos);

        $datos['updated_by'] = Auth::id();

        $cursoDesaprobado->update($datos);

        return redirect()
            ->route('cursos-desaprobados.index')
            ->with(
                'success',
                'Curso desaprobado actualizado correctamente.'
            );
    }

    /**
     * Activar o desactivar el registro.
     */
    public function cambiarEstado(
        CursoDesaprobado $cursoDesaprobado
    ) {
        abort_unless(
            Auth::user()?->hasPermission(
                'cursos_desaprobados.cambiar_estado'
            ),
            403
        );

        if (! $cursoDesaprobado->activo) {
            $cursoDesaprobado->load([
                'actaEstudiante.acta',
                'actaEstudiante.estudiante',
                'asignatura',
            ]);

            $errores = [];

            if (! $cursoDesaprobado->actaEstudiante->activo) {
                $errores['estado'] =
                    'No puede reactivarse porque la relación entre el estudiante y el acta está inactiva.';
            }

            if (! $cursoDesaprobado->actaEstudiante->acta->activo) {
                $errores['estado'] =
                    'No puede reactivarse porque el acta histórica está inactiva.';
            }

            if (! $cursoDesaprobado->actaEstudiante->estudiante->activo) {
                $errores['estado'] =
                    'No puede reactivarse porque el estudiante está inactivo.';
            }

            if (! $cursoDesaprobado->asignatura->activo) {
                $errores['estado'] =
                    'No puede reactivarse porque la asignatura está inactiva.';
            }

            if ($errores !== []) {
                throw ValidationException::withMessages(
                    $errores
                );
            }
        }

        $cursoDesaprobado->update([
            'activo' => ! $cursoDesaprobado->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $cursoDesaprobado->activo
            ? 'Curso desaprobado activado correctamente.'
            : 'Curso desaprobado desactivado correctamente.';

        return redirect()
            ->route('cursos-desaprobados.index')
            ->with('success', $mensaje);
    }

    /**
     * Validar los campos enviados.
     */
    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'acta_estudiante_id' => [
                'required',
                'integer',
                'exists:acta_estudiantes,id',
            ],
            'asignatura_id' => [
                'required',
                'integer',
                'exists:asignaturas,id',
            ],
            'observacion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'acta_estudiante_id.required' =>
                'Debe seleccionar un estudiante asociado a un acta.',
            'acta_estudiante_id.exists' =>
                'La relación entre el estudiante y el acta seleccionada no existe.',

            'asignatura_id.required' =>
                'Debe seleccionar una asignatura.',
            'asignatura_id.exists' =>
                'La asignatura seleccionada no existe.',

            'observacion.max' =>
                'La observación no puede superar los 255 caracteres.',
        ]);
    }

    /**
     * Validar coherencia de las relaciones.
     */
    private function validarRelaciones(
        array $datos,
        ?CursoDesaprobado $cursoActual = null
    ): void {
        $errores = [];

        $actaEstudiante = ActaEstudiante::with([
            'acta',
            'estudiante',
        ])->find(
            $datos['acta_estudiante_id']
        );

        $asignatura = Asignatura::find(
            $datos['asignatura_id']
        );

        $mismaRelacionActual =
            $cursoActual !== null &&
            (int) $cursoActual->acta_estudiante_id ===
                (int) $datos['acta_estudiante_id'];

        $mismaAsignaturaActual =
            $cursoActual !== null &&
            (int) $cursoActual->asignatura_id ===
                (int) $datos['asignatura_id'];

        if ($actaEstudiante) {

            if (
                ! $actaEstudiante->activo &&
                ! $mismaRelacionActual
            ) {
                $errores['acta_estudiante_id'] =
                    'La relación entre el estudiante y el acta se encuentra inactiva.';
            }

            if (
                ! $actaEstudiante->acta->activo &&
                ! $mismaRelacionActual
            ) {
                $errores['acta_estudiante_id'] =
                    'El acta histórica seleccionada se encuentra inactiva.';
            }

            if (
                ! $actaEstudiante->estudiante->activo &&
                ! $mismaRelacionActual
            ) {
                $errores['acta_estudiante_id'] =
                    'El estudiante seleccionado se encuentra inactivo.';
            }
        }

        if (
            $asignatura &&
            ! $asignatura->activo &&
            ! $mismaAsignaturaActual
        ) {
            $errores['asignatura_id'] =
                'La asignatura seleccionada se encuentra inactiva.';
        }

        $duplicado = CursoDesaprobado::query()
            ->where(
                'acta_estudiante_id',
                $datos['acta_estudiante_id']
            )
            ->where(
                'asignatura_id',
                $datos['asignatura_id']
            );

        if ($cursoActual) {
            $duplicado->where(
                'id',
                '!=',
                $cursoActual->id
            );
        }

        if ($duplicado->exists()) {
            $errores['asignatura_id'] =
                'Esta asignatura ya fue registrada como desaprobada para el estudiante dentro de esta acta.';
        }

        if ($errores !== []) {
            throw ValidationException::withMessages(
                $errores
            );
        }
    }

    /**
     * Normalizar campos opcionales.
     */
    private function normalizarDatos(array $datos): array
    {
        $datos['acta_estudiante_id'] =
            (int) $datos['acta_estudiante_id'];

        $datos['asignatura_id'] =
            (int) $datos['asignatura_id'];

        $datos['observacion'] =
            isset($datos['observacion']) &&
            trim($datos['observacion']) !== ''
                ? trim($datos['observacion'])
                : null;

        return $datos;
    }

    /**
     * Cargar registros disponibles para los formularios.
     */
    private function cargarCatalogos(
        ?CursoDesaprobado $cursoActual = null
    ): array {
        $actaEstudiantes = ActaEstudiante::query()
            ->with([
                'estudiante',
                'acta.institucionEducativa',
                'acta.nivel',
                'acta.grado',
            ])
            ->where(function ($query) use ($cursoActual) {

                $query->where(function ($subquery) {

                    $subquery
                        ->where('activo', true)
                        ->whereHas(
                            'acta',
                            fn ($q) => $q->where(
                                'activo',
                                true
                            )
                        )
                        ->whereHas(
                            'estudiante',
                            fn ($q) => $q->where(
                                'activo',
                                true
                            )
                        );

                });

                if ($cursoActual?->acta_estudiante_id) {
                    $query->orWhere(
                        'id',
                        $cursoActual->acta_estudiante_id
                    );
                }
            })
            ->orderByDesc('id')
            ->get();

        $asignaturas = Asignatura::query()
            ->where(function ($query) use ($cursoActual) {

                $query->where(
                    'activo',
                    true
                );

                if ($cursoActual?->asignatura_id) {
                    $query->orWhere(
                        'id',
                        $cursoActual->asignatura_id
                    );
                }
            })
            ->orderBy('nombre')
            ->get();

        return compact(
            'actaEstudiantes',
            'asignaturas'
        );
    }
}