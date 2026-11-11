<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Grado;
use App\Models\InstitucionEducativa;
use App\Models\InstitucionNivel;
use App\Models\Nivel;
use App\Models\TipoActa;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ActaController extends Controller
{
    /**
     * Mostrar el listado de actas históricas.
     */
    public function index(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('actas.ver'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $actas = Acta::query()
            ->with([
                'tipoActa',
                'institucionEducativa',
                'nivel',
                'grado',
                'ubicacion',
            ])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('numero_acta', 'like', "%{$buscar}%")
                        ->orWhere('anio', 'like', "%{$buscar}%")
                        ->orWhere('seccion', 'like', "%{$buscar}%")
                        ->orWhereHas('tipoActa', function ($q) use ($buscar) {
                            $q->where('nombre', 'like', "%{$buscar}%");
                        })
                        ->orWhereHas('institucionEducativa', function ($q) use ($buscar) {
                            $q->where('nombre', 'like', "%{$buscar}%")
                                ->orWhere('codigo_modular', 'like', "%{$buscar}%");
                        })
                        ->orWhereHas('nivel', function ($q) use ($buscar) {
                            $q->where('nombre', 'like', "%{$buscar}%");
                        })
                        ->orWhereHas('grado', function ($q) use ($buscar) {
                            $q->where('nombre', 'like', "%{$buscar}%");
                        })
                        ->orWhereHas('ubicacion', function ($q) use ($buscar) {
                            $q->where('ambiente', 'like', "%{$buscar}%")
                                ->orWhere('estante', 'like', "%{$buscar}%")
                                ->orWhere('caja', 'like', "%{$buscar}%");
                        });
                });
            })
            ->orderByDesc('anio')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('actas.index', compact(
            'actas',
            'buscar'
        ));
    }

    /**
     * Mostrar el formulario para registrar un acta.
     */
    public function create()
    {
        abort_unless(
            Auth::user()?->hasPermission('actas.crear'),
            403
        );

        $catalogos = $this->cargarCatalogos();

        return view(
            'actas.create',
            $catalogos
        );
    }

    /**
     * Registrar un acta histórica.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('actas.crear'),
            403
        );

        $datos = $this->validarDatos($request);

        $this->validarRelaciones($datos);

        $datos = $this->normalizarDatos($datos);

        $datos['activo'] = true;
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();

        Acta::create($datos);

        return redirect()
            ->route('actas.index')
            ->with(
                'success',
                'Acta histórica registrada correctamente.'
            );
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Acta $acta)
    {
        abort_unless(
            Auth::user()?->hasPermission('actas.editar'),
            403
        );

        $catalogos = $this->cargarCatalogos($acta);

        return view(
            'actas.edit',
            array_merge(
                $catalogos,
                ['acta' => $acta]
            )
        );
    }

    /**
     * Actualizar un acta histórica.
     */
    public function update(
        Request $request,
        Acta $acta
    ) {
        abort_unless(
            Auth::user()?->hasPermission('actas.editar'),
            403
        );

        $datos = $this->validarDatos($request);

        $this->validarRelaciones(
            $datos,
            $acta
        );

        $datos = $this->normalizarDatos($datos);

        $datos['updated_by'] = Auth::id();

        $acta->update($datos);

        return redirect()
            ->route('actas.index')
            ->with(
                'success',
                'Acta histórica actualizada correctamente.'
            );
    }

    /**
     * Activar o desactivar un acta.
     */
    public function cambiarEstado(Acta $acta)
    {
        abort_unless(
            Auth::user()?->hasPermission(
                'actas.cambiar_estado'
            ),
            403
        );

        $acta->update([
            'activo' => ! $acta->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $acta->activo
            ? 'Acta activada correctamente.'
            : 'Acta desactivada correctamente.';

        return redirect()
            ->route('actas.index')
            ->with('success', $mensaje);
    }

    /**
     * Validaciones básicas de los campos del acta.
     */
    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'tipo_acta_id' => [
                'required',
                'integer',
                'exists:tipos_acta,id',
            ],
            'institucion_educativa_id' => [
                'required',
                'integer',
                'exists:instituciones_educativas,id',
            ],
            'nivel_id' => [
                'required',
                'integer',
                'exists:niveles,id',
            ],
            'grado_id' => [
                'nullable',
                'integer',
                'exists:grados,id',
            ],
            'ubicacion_id' => [
                'nullable',
                'integer',
                'exists:ubicaciones,id',
            ],
            'numero_acta' => [
                'nullable',
                'string',
                'max:50',
            ],
            'anio' => [
                'required',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],
            'fecha_acta' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'seccion' => [
                'nullable',
                'string',
                'max:30',
            ],
            'observaciones' => [
                'nullable',
                'string',
            ],
        ], [
            'tipo_acta_id.required' =>
                'Debe seleccionar un tipo de acta.',
            'tipo_acta_id.exists' =>
                'El tipo de acta seleccionado no existe.',

            'institucion_educativa_id.required' =>
                'Debe seleccionar una institución educativa.',
            'institucion_educativa_id.exists' =>
                'La institución educativa seleccionada no existe.',

            'nivel_id.required' =>
                'Debe seleccionar un nivel educativo.',
            'nivel_id.exists' =>
                'El nivel seleccionado no existe.',

            'grado_id.exists' =>
                'El grado seleccionado no existe.',

            'ubicacion_id.exists' =>
                'La ubicación seleccionada no existe.',

            'numero_acta.max' =>
                'El número de acta no puede superar los 50 caracteres.',

            'anio.required' =>
                'El año académico es obligatorio.',
            'anio.integer' =>
                'El año académico debe ser un número entero.',
            'anio.min' =>
                'Ingrese un año académico válido.',
            'anio.max' =>
                'El año académico no puede ser posterior al año actual.',

            'fecha_acta.date' =>
                'La fecha del acta no es válida.',
            'fecha_acta.before_or_equal' =>
                'La fecha del acta no puede ser posterior a la fecha actual.',

            'seccion.max' =>
                'La sección no puede superar los 30 caracteres.',
        ]);
    }

    /**
     * Validar coherencia entre los catálogos seleccionados.
     */
    private function validarRelaciones(
        array $datos,
        ?Acta $acta = null
    ): void {
        $errores = [];

        $this->validarCatalogoDisponible(
            TipoActa::class,
            (int) $datos['tipo_acta_id'],
            $acta?->tipo_acta_id,
            'tipo_acta_id',
            'El tipo de acta seleccionado se encuentra inactivo.',
            $errores
        );

        $this->validarCatalogoDisponible(
            InstitucionEducativa::class,
            (int) $datos['institucion_educativa_id'],
            $acta?->institucion_educativa_id,
            'institucion_educativa_id',
            'La institución educativa seleccionada se encuentra inactiva.',
            $errores
        );

        $this->validarCatalogoDisponible(
            Nivel::class,
            (int) $datos['nivel_id'],
            $acta?->nivel_id,
            'nivel_id',
            'El nivel seleccionado se encuentra inactivo.',
            $errores
        );

        if (! empty($datos['grado_id'])) {
            $grado = Grado::find($datos['grado_id']);

            if (
                $grado &&
                (int) $grado->nivel_id !== (int) $datos['nivel_id']
            ) {
                $errores['grado_id'] =
                    'El grado seleccionado no pertenece al nivel indicado.';
            }

            $this->validarCatalogoDisponible(
                Grado::class,
                (int) $datos['grado_id'],
                $acta?->grado_id,
                'grado_id',
                'El grado seleccionado se encuentra inactivo.',
                $errores
            );
        }

        if (! empty($datos['ubicacion_id'])) {
            $this->validarCatalogoDisponible(
                Ubicacion::class,
                (int) $datos['ubicacion_id'],
                $acta?->ubicacion_id,
                'ubicacion_id',
                'La ubicación seleccionada se encuentra inactiva.',
                $errores
            );
        }

        $mismaRelacionActual =
            $acta !== null &&
            (int) $acta->institucion_educativa_id ===
                (int) $datos['institucion_educativa_id'] &&
            (int) $acta->nivel_id ===
                (int) $datos['nivel_id'];

        $relacionInstitucionNivel =
            InstitucionNivel::query()
                ->where(
                    'institucion_educativa_id',
                    $datos['institucion_educativa_id']
                )
                ->where(
                    'nivel_id',
                    $datos['nivel_id']
                );

        if (! $mismaRelacionActual) {
            $relacionInstitucionNivel
                ->where('activo', true);
        }

        if (! $relacionInstitucionNivel->exists()) {
            $errores['nivel_id'] =
                'El nivel seleccionado no está asociado a la institución educativa indicada.';
        }

        if ($errores !== []) {
            throw ValidationException::withMessages(
                $errores
            );
        }
    }

    /**
     * Verificar que un catálogo esté activo.
     *
     * Si el acta ya utilizaba ese registro antes de su
     * desactivación, se permite conservar la relación.
     */
    private function validarCatalogoDisponible(
        string $modelo,
        int $id,
        ?int $idActual,
        string $campo,
        string $mensaje,
        array &$errores
    ): void {
        $registro = $modelo::find($id);

        if (! $registro) {
            return;
        }

        if (
            ! $registro->activo &&
            $id !== $idActual
        ) {
            $errores[$campo] = $mensaje;
        }
    }

    /**
     * Normalizar campos opcionales.
     */
    private function normalizarDatos(array $datos): array
    {
        $datos['grado_id'] =
            ! empty($datos['grado_id'])
                ? (int) $datos['grado_id']
                : null;

        $datos['ubicacion_id'] =
            ! empty($datos['ubicacion_id'])
                ? (int) $datos['ubicacion_id']
                : null;

        $datos['numero_acta'] =
            isset($datos['numero_acta']) &&
            trim($datos['numero_acta']) !== ''
                ? trim($datos['numero_acta'])
                : null;

        $datos['fecha_acta'] =
            ! empty($datos['fecha_acta'])
                ? $datos['fecha_acta']
                : null;

        $datos['seccion'] =
            isset($datos['seccion']) &&
            trim($datos['seccion']) !== ''
                ? trim($datos['seccion'])
                : null;

        $datos['observaciones'] =
            isset($datos['observaciones']) &&
            trim($datos['observaciones']) !== ''
                ? trim($datos['observaciones'])
                : null;

        return $datos;
    }

    /**
     * Cargar catálogos para los formularios.
     */
    private function cargarCatalogos(
        ?Acta $acta = null
    ): array {
        $tiposActa = TipoActa::query()
            ->where(function ($query) use ($acta) {
                $query->where('activo', true);

                if ($acta?->tipo_acta_id) {
                    $query->orWhere(
                        'id',
                        $acta->tipo_acta_id
                    );
                }
            })
            ->orderBy('nombre')
            ->get();

        $instituciones = InstitucionEducativa::query()
            ->where(function ($query) use ($acta) {
                $query->where('activo', true);

                if ($acta?->institucion_educativa_id) {
                    $query->orWhere(
                        'id',
                        $acta->institucion_educativa_id
                    );
                }
            })
            ->orderBy('nombre')
            ->get();

        $niveles = Nivel::query()
            ->where(function ($query) use ($acta) {
                $query->where('activo', true);

                if ($acta?->nivel_id) {
                    $query->orWhere(
                        'id',
                        $acta->nivel_id
                    );
                }
            })
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        $grados = Grado::query()
            ->with('nivel')
            ->where(function ($query) use ($acta) {
                $query->where('activo', true);

                if ($acta?->grado_id) {
                    $query->orWhere(
                        'id',
                        $acta->grado_id
                    );
                }
            })
            ->orderBy('nivel_id')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        $ubicaciones = Ubicacion::query()
            ->where(function ($query) use ($acta) {
                $query->where('activo', true);

                if ($acta?->ubicacion_id) {
                    $query->orWhere(
                        'id',
                        $acta->ubicacion_id
                    );
                }
            })
            ->orderBy('ambiente')
            ->orderBy('estante')
            ->orderBy('caja')
            ->get();

        return compact(
            'tiposActa',
            'instituciones',
            'niveles',
            'grados',
            'ubicaciones'
        );
    }
}