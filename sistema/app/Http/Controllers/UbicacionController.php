<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UbicacionController extends Controller
{
    /**
     * Mostrar el listado de ubicaciones físicas.
     */
    public function index(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('ubicaciones.ver'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $ubicaciones = Ubicacion::query()
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('ambiente', 'like', "%{$buscar}%")
                        ->orWhere('estante', 'like', "%{$buscar}%")
                        ->orWhere('caja', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('ambiente')
            ->orderBy('estante')
            ->orderBy('caja')
            ->paginate(10)
            ->withQueryString();

        return view('ubicaciones.index', compact(
            'ubicaciones',
            'buscar'
        ));
    }

    /**
     * Mostrar el formulario de creación.
     */
    public function create()
    {
        abort_unless(
            Auth::user()?->hasPermission('ubicaciones.crear'),
            403
        );

        return view('ubicaciones.create');
    }

    /**
     * Registrar una nueva ubicación física.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('ubicaciones.crear'),
            403
        );

        $datos = $request->validate([
            'ambiente' => [
                'required',
                'string',
                'max:100',
            ],
            'estante' => [
                'nullable',
                'string',
                'max:50',
            ],
            'caja' => [
                'nullable',
                'string',
                'max:50',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'ambiente.required' =>
                'El ambiente es obligatorio.',
            'ambiente.max' =>
                'El ambiente no puede superar los 100 caracteres.',
            'estante.max' =>
                'El estante no puede superar los 50 caracteres.',
            'caja.max' =>
                'La caja no puede superar los 50 caracteres.',
            'descripcion.max' =>
                'La descripción no puede superar los 255 caracteres.',
        ]);

        $datos = $this->normalizarDatos($datos);

        if ($this->ubicacionDuplicada($datos)) {
            throw ValidationException::withMessages([
                'ambiente' =>
                    'Ya existe una ubicación con el mismo ambiente, estante y caja.',
            ]);
        }

        $datos['activo'] = true;
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();

        Ubicacion::create($datos);

        return redirect()
            ->route('ubicaciones.index')
            ->with(
                'success',
                'Ubicación registrada correctamente.'
            );
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Ubicacion $ubicacion)
    {
        abort_unless(
            Auth::user()?->hasPermission('ubicaciones.editar'),
            403
        );

        return view(
            'ubicaciones.edit',
            compact('ubicacion')
        );
    }

    /**
     * Actualizar una ubicación física.
     */
    public function update(
        Request $request,
        Ubicacion $ubicacion
    ) {
        abort_unless(
            Auth::user()?->hasPermission('ubicaciones.editar'),
            403
        );

        $datos = $request->validate([
            'ambiente' => [
                'required',
                'string',
                'max:100',
            ],
            'estante' => [
                'nullable',
                'string',
                'max:50',
            ],
            'caja' => [
                'nullable',
                'string',
                'max:50',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'ambiente.required' =>
                'El ambiente es obligatorio.',
            'ambiente.max' =>
                'El ambiente no puede superar los 100 caracteres.',
            'estante.max' =>
                'El estante no puede superar los 50 caracteres.',
            'caja.max' =>
                'La caja no puede superar los 50 caracteres.',
            'descripcion.max' =>
                'La descripción no puede superar los 255 caracteres.',
        ]);

        $datos = $this->normalizarDatos($datos);

        if ($this->ubicacionDuplicada(
            $datos,
            $ubicacion->id
        )) {
            throw ValidationException::withMessages([
                'ambiente' =>
                    'Ya existe una ubicación con el mismo ambiente, estante y caja.',
            ]);
        }

        $datos['updated_by'] = Auth::id();

        $ubicacion->update($datos);

        return redirect()
            ->route('ubicaciones.index')
            ->with(
                'success',
                'Ubicación actualizada correctamente.'
            );
    }

    /**
     * Activar o desactivar una ubicación física.
     */
    public function cambiarEstado(Ubicacion $ubicacion)
    {
        abort_unless(
            Auth::user()?->hasPermission(
                'ubicaciones.cambiar_estado'
            ),
            403
        );

        $ubicacion->update([
            'activo' => ! $ubicacion->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $ubicacion->activo
            ? 'Ubicación activada correctamente.'
            : 'Ubicación desactivada correctamente.';

        return redirect()
            ->route('ubicaciones.index')
            ->with('success', $mensaje);
    }

    /**
     * Normalizar valores opcionales antes de guardarlos.
     */
    private function normalizarDatos(array $datos): array
    {
        $datos['ambiente'] = trim($datos['ambiente']);

        $datos['estante'] = isset($datos['estante'])
            && trim($datos['estante']) !== ''
                ? trim($datos['estante'])
                : null;

        $datos['caja'] = isset($datos['caja'])
            && trim($datos['caja']) !== ''
                ? trim($datos['caja'])
                : null;

        $datos['descripcion'] =
            isset($datos['descripcion'])
            && trim($datos['descripcion']) !== ''
                ? trim($datos['descripcion'])
                : null;

        return $datos;
    }

    /**
     * Comprobar si ya existe la misma ubicación física.
     */
    private function ubicacionDuplicada(
        array $datos,
        ?int $ignorarId = null
    ): bool {
        $query = Ubicacion::query()
            ->where('ambiente', $datos['ambiente']);

        if ($datos['estante'] === null) {
            $query->whereNull('estante');
        } else {
            $query->where('estante', $datos['estante']);
        }

        if ($datos['caja'] === null) {
            $query->whereNull('caja');
        } else {
            $query->where('caja', $datos['caja']);
        }

        if ($ignorarId !== null) {
            $query->where('id', '!=', $ignorarId);
        }

        return $query->exists();
    }
}