<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\CursoDesaprobado;
use App\Models\Subsanacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SubsanacionController extends Controller
{
    /**
     * Mostrar el listado de subsanaciones.
     */
    public function index(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('subsanaciones.ver'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $subsanaciones = Subsanacion::query()
            ->with([
                'cursoDesaprobado.asignatura',
                'cursoDesaprobado.actaEstudiante.estudiante',
                'cursoDesaprobado.actaEstudiante.acta.institucionEducativa',
                'cursoDesaprobado.actaEstudiante.acta.nivel',
                'cursoDesaprobado.actaEstudiante.acta.grado',
                'actaSubsanacion.tipoActa',
            ])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {

                    $subquery
                        ->where(
                            'resultado',
                            'like',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'calificacion',
                            'like',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'observacion',
                            'like',
                            "%{$buscar}%"
                        )
                        ->orWhereHas(
                            'cursoDesaprobado.asignatura',
                            function ($q) use ($buscar) {
                                $q->where(
                                    'nombre',
                                    'like',
                                    "%{$buscar}%"
                                );
                            }
                        )
                        ->orWhereHas(
                            'cursoDesaprobado.actaEstudiante.estudiante',
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
                            'cursoDesaprobado.actaEstudiante.acta',
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
                            'actaSubsanacion',
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
                        );
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'subsanaciones.index',
            compact(
                'subsanaciones',
                'buscar'
            )
        );
    }

    /**
     * Mostrar el formulario de creación.
     */
    public function create()
    {
        abort_unless(
            Auth::user()?->hasPermission('subsanaciones.crear'),
            403
        );

        $catalogos = $this->cargarCatalogos();

        return view(
            'subsanaciones.create',
            $catalogos
        );
    }

    /**
     * Registrar una nueva subsanación.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('subsanaciones.crear'),
            403
        );

        $datos = $this->validarDatos($request);

        $this->validarRelaciones($datos);

        $datos = $this->normalizarDatos($datos);

        $datos['activo'] = true;
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();

        Subsanacion::create($datos);

        return redirect()
            ->route('subsanaciones.index')
            ->with(
                'success',
                'Subsanación registrada correctamente.'
            );
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Subsanacion $subsanacion)
    {
        abort_unless(
            Auth::user()?->hasPermission('subsanaciones.editar'),
            403
        );

        $subsanacion->load([
            'cursoDesaprobado.asignatura',
            'cursoDesaprobado.actaEstudiante.estudiante',
            'cursoDesaprobado.actaEstudiante.acta',
            'actaSubsanacion.tipoActa',
        ]);

        $catalogos = $this->cargarCatalogos(
            $subsanacion
        );

        return view(
            'subsanaciones.edit',
            array_merge(
                $catalogos,
                [
                    'subsanacion' => $subsanacion,
                ]
            )
        );
    }

    /**
     * Actualizar una subsanación.
     */
    public function update(
        Request $request,
        Subsanacion $subsanacion
    ) {
        abort_unless(
            Auth::user()?->hasPermission('subsanaciones.editar'),
            403
        );

        $datos = $this->validarDatos($request);

        $this->validarRelaciones(
            $datos,
            $subsanacion
        );

        $datos = $this->normalizarDatos($datos);

        $datos['updated_by'] = Auth::id();

        $subsanacion->update($datos);

        return redirect()
            ->route('subsanaciones.index')
            ->with(
                'success',
                'Subsanación actualizada correctamente.'
            );
    }

    /**
     * Activar o desactivar una subsanación.
     */
    public function cambiarEstado(
        Subsanacion $subsanacion
    ) {
        abort_unless(
            Auth::user()?->hasPermission(
                'subsanaciones.cambiar_estado'
            ),
            403
        );

        if (! $subsanacion->activo) {

            $subsanacion->load([
                'cursoDesaprobado.actaEstudiante.acta',
                'cursoDesaprobado.actaEstudiante.estudiante',
                'cursoDesaprobado.asignatura',
                'actaSubsanacion.tipoActa',
            ]);

            $errores = [];

            if (! $subsanacion->cursoDesaprobado->activo) {
                $errores['estado'] =
                    'No puede reactivarse porque el curso desaprobado se encuentra inactivo.';
            }

            if (
                ! $subsanacion
                    ->cursoDesaprobado
                    ->actaEstudiante
                    ->activo
            ) {
                $errores['estado'] =
                    'No puede reactivarse porque la relación entre el estudiante y el acta histórica está inactiva.';
            }

            if (
                ! $subsanacion
                    ->cursoDesaprobado
                    ->actaEstudiante
                    ->estudiante
                    ->activo
            ) {
                $errores['estado'] =
                    'No puede reactivarse porque el estudiante se encuentra inactivo.';
            }

            if (
                ! $subsanacion
                    ->cursoDesaprobado
                    ->asignatura
                    ->activo
            ) {
                $errores['estado'] =
                    'No puede reactivarse porque la asignatura se encuentra inactiva.';
            }

            if (! $subsanacion->actaSubsanacion->activo) {
                $errores['estado'] =
                    'No puede reactivarse porque el acta de subsanación se encuentra inactiva.';
            }

            if (
                ! $subsanacion->actaSubsanacion->tipoActa ||
                mb_strtolower(
                    trim(
                        $subsanacion
                            ->actaSubsanacion
                            ->tipoActa
                            ->nombre
                    )
                ) !== 'acta de subsanación'
            ) {
                $errores['estado'] =
                    'El documento asociado ya no corresponde al tipo Acta de subsanación.';
            }

            if ($errores !== []) {
                throw ValidationException::withMessages(
                    $errores
                );
            }
        }

        $subsanacion->update([
            'activo' => ! $subsanacion->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $subsanacion->activo
            ? 'Subsanación activada correctamente.'
            : 'Subsanación desactivada correctamente.';

        return redirect()
            ->route('subsanaciones.index')
            ->with(
                'success',
                $mensaje
            );
    }

    /**
     * Validar los datos enviados.
     */
    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'curso_desaprobado_id' => [
                'required',
                'integer',
                'exists:cursos_desaprobados,id',
            ],

            'acta_subsanacion_id' => [
                'required',
                'integer',
                'exists:actas,id',
            ],

            'fecha_subsanacion' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'resultado' => [
                'required',
                'string',
                Rule::in([
                    'Aprobado',
                    'Desaprobado',
                    'Pendiente',
                ]),
            ],

            'calificacion' => [
                'nullable',
                'string',
                'max:20',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:255',
            ],

        ], [
            'curso_desaprobado_id.required' =>
                'Debe seleccionar un curso desaprobado.',

            'curso_desaprobado_id.exists' =>
                'El curso desaprobado seleccionado no existe.',

            'acta_subsanacion_id.required' =>
                'Debe seleccionar un acta de subsanación.',

            'acta_subsanacion_id.exists' =>
                'El acta de subsanación seleccionada no existe.',

            'fecha_subsanacion.date' =>
                'La fecha de subsanación no es válida.',

            'fecha_subsanacion.before_or_equal' =>
                'La fecha de subsanación no puede ser posterior a la fecha actual.',

            'resultado.required' =>
                'Debe seleccionar el resultado de la subsanación.',

            'resultado.in' =>
                'El resultado seleccionado no es válido.',

            'calificacion.max' =>
                'La calificación no puede superar los 20 caracteres.',

            'observacion.max' =>
                'La observación no puede superar los 255 caracteres.',
        ]);
    }

    /**
     * Validar coherencia entre curso desaprobado
     * y acta de subsanación.
     */
    private function validarRelaciones(
        array $datos,
        ?Subsanacion $subsanacionActual = null
    ): void {
        $errores = [];

        $cursoDesaprobado = CursoDesaprobado::with([
            'actaEstudiante.acta',
            'actaEstudiante.estudiante',
            'asignatura',
        ])->find(
            $datos['curso_desaprobado_id']
        );

        $actaSubsanacion = Acta::with([
            'tipoActa',
        ])->find(
            $datos['acta_subsanacion_id']
        );

        $mismoCursoActual =
            $subsanacionActual !== null &&
            (int) $subsanacionActual->curso_desaprobado_id ===
                (int) $datos['curso_desaprobado_id'];

        $mismaActaActual =
            $subsanacionActual !== null &&
            (int) $subsanacionActual->acta_subsanacion_id ===
                (int) $datos['acta_subsanacion_id'];

        if ($cursoDesaprobado) {

            if (
                ! $cursoDesaprobado->activo &&
                ! $mismoCursoActual
            ) {
                $errores['curso_desaprobado_id'] =
                    'El curso desaprobado seleccionado se encuentra inactivo.';
            }

            if (
                ! $cursoDesaprobado
                    ->actaEstudiante
                    ->activo &&
                ! $mismoCursoActual
            ) {
                $errores['curso_desaprobado_id'] =
                    'La relación entre el estudiante y el acta histórica se encuentra inactiva.';
            }

            if (
                ! $cursoDesaprobado
                    ->actaEstudiante
                    ->estudiante
                    ->activo &&
                ! $mismoCursoActual
            ) {
                $errores['curso_desaprobado_id'] =
                    'El estudiante relacionado se encuentra inactivo.';
            }

            if (
                ! $cursoDesaprobado
                    ->asignatura
                    ->activo &&
                ! $mismoCursoActual
            ) {
                $errores['curso_desaprobado_id'] =
                    'La asignatura relacionada se encuentra inactiva.';
            }
        }

        if ($actaSubsanacion) {

            if (
                ! $actaSubsanacion->activo &&
                ! $mismaActaActual
            ) {
                $errores['acta_subsanacion_id'] =
                    'El acta de subsanación seleccionada se encuentra inactiva.';
            }

            $nombreTipo = $actaSubsanacion->tipoActa
                ? mb_strtolower(
                    trim(
                        $actaSubsanacion
                            ->tipoActa
                            ->nombre
                    )
                )
                : null;

            if ($nombreTipo !== 'acta de subsanación') {
                $errores['acta_subsanacion_id'] =
                    'El acta seleccionada no corresponde al tipo Acta de subsanación.';
            }
        }

        if ($errores !== []) {
            throw ValidationException::withMessages(
                $errores
            );
        }
    }

    /**
     * Normalizar datos opcionales.
     */
    private function normalizarDatos(array $datos): array
    {
        $datos['curso_desaprobado_id'] =
            (int) $datos['curso_desaprobado_id'];

        $datos['acta_subsanacion_id'] =
            (int) $datos['acta_subsanacion_id'];

        $datos['fecha_subsanacion'] =
            isset($datos['fecha_subsanacion']) &&
            trim((string) $datos['fecha_subsanacion']) !== ''
                ? $datos['fecha_subsanacion']
                : null;

        $datos['calificacion'] =
            isset($datos['calificacion']) &&
            trim($datos['calificacion']) !== ''
                ? trim($datos['calificacion'])
                : null;

        $datos['observacion'] =
            isset($datos['observacion']) &&
            trim($datos['observacion']) !== ''
                ? trim($datos['observacion'])
                : null;

        return $datos;
    }

    /**
     * Cargar cursos desaprobados y actas de subsanación
     * disponibles para los formularios.
     */
    private function cargarCatalogos(
        ?Subsanacion $subsanacionActual = null
    ): array {
        $cursosDesaprobados = CursoDesaprobado::query()
            ->with([
                'asignatura',
                'actaEstudiante.estudiante',
                'actaEstudiante.acta.institucionEducativa',
                'actaEstudiante.acta.nivel',
                'actaEstudiante.acta.grado',
            ])
            ->where(function ($query) use ($subsanacionActual) {

                $query->where(function ($subquery) {

                    $subquery
                        ->where('activo', true)
                        ->whereHas(
                            'asignatura',
                            fn ($q) => $q->where(
                                'activo',
                                true
                            )
                        )
                        ->whereHas(
                            'actaEstudiante',
                            fn ($q) => $q->where(
                                'activo',
                                true
                            )
                        )
                        ->whereHas(
                            'actaEstudiante.estudiante',
                            fn ($q) => $q->where(
                                'activo',
                                true
                            )
                        );
                });

                if (
                    $subsanacionActual
                        ?->curso_desaprobado_id
                ) {
                    $query->orWhere(
                        'id',
                        $subsanacionActual
                            ->curso_desaprobado_id
                    );
                }
            })
            ->orderByDesc('id')
            ->get();

        $actasSubsanacion = Acta::query()
            ->with([
                'tipoActa',
                'institucionEducativa',
                'nivel',
                'grado',
            ])
            ->whereHas(
                'tipoActa',
                function ($query) {
                    $query->where(
                        'nombre',
                        'Acta de subsanación'
                    );
                }
            )
            ->where(function ($query) use ($subsanacionActual) {

                $query->where(
                    'activo',
                    true
                );

                if (
                    $subsanacionActual
                        ?->acta_subsanacion_id
                ) {
                    $query->orWhere(
                        'id',
                        $subsanacionActual
                            ->acta_subsanacion_id
                    );
                }
            })
            ->orderByDesc('anio')
            ->orderByDesc('id')
            ->get();

        $resultados = [
            'Aprobado',
            'Desaprobado',
            'Pendiente',
        ];

        return compact(
            'cursosDesaprobados',
            'actasSubsanacion',
            'resultados'
        );
    }
}