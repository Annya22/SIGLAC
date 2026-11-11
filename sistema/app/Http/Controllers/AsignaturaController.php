<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AsignaturaController extends Controller
{
    /**
     * Mostrar el listado de asignaturas.
     */
    public function index(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('asignaturas.ver'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $asignaturas = Asignatura::query()
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('asignaturas.index', compact(
            'asignaturas',
            'buscar'
        ));
    }

    /**
     * Mostrar el formulario para crear una asignatura.
     */
    public function create()
    {
        abort_unless(
            Auth::user()?->hasPermission('asignaturas.crear'),
            403
        );

        return view('asignaturas.create');
    }

    /**
     * Registrar una nueva asignatura.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('asignaturas.crear'),
            403
        );

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:150',
                'unique:asignaturas,nombre',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' =>
                'El nombre de la asignatura es obligatorio.',
            'nombre.max' =>
                'El nombre no puede superar los 150 caracteres.',
            'nombre.unique' =>
                'Ya existe una asignatura registrada con ese nombre.',
            'descripcion.max' =>
                'La descripción no puede superar los 255 caracteres.',
        ]);

        $datos['nombre'] = trim($datos['nombre']);

        $datos['descripcion'] =
            isset($datos['descripcion']) &&
            trim($datos['descripcion']) !== ''
                ? trim($datos['descripcion'])
                : null;

        $datos['activo'] = true;
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();

        Asignatura::create($datos);

        return redirect()
            ->route('asignaturas.index')
            ->with(
                'success',
                'Asignatura registrada correctamente.'
            );
    }

    /**
     * Mostrar el formulario para editar una asignatura.
     */
    public function edit(Asignatura $asignatura)
    {
        abort_unless(
            Auth::user()?->hasPermission('asignaturas.editar'),
            403
        );

        return view(
            'asignaturas.edit',
            compact('asignatura')
        );
    }

    /**
     * Actualizar una asignatura existente.
     */
    public function update(
        Request $request,
        Asignatura $asignatura
    ) {
        abort_unless(
            Auth::user()?->hasPermission('asignaturas.editar'),
            403
        );

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique(
                    'asignaturas',
                    'nombre'
                )->ignore($asignatura->id),
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' =>
                'El nombre de la asignatura es obligatorio.',
            'nombre.max' =>
                'El nombre no puede superar los 150 caracteres.',
            'nombre.unique' =>
                'Ya existe una asignatura registrada con ese nombre.',
            'descripcion.max' =>
                'La descripción no puede superar los 255 caracteres.',
        ]);

        $datos['nombre'] = trim($datos['nombre']);

        $datos['descripcion'] =
            isset($datos['descripcion']) &&
            trim($datos['descripcion']) !== ''
                ? trim($datos['descripcion'])
                : null;

        $datos['updated_by'] = Auth::id();

        $asignatura->update($datos);

        return redirect()
            ->route('asignaturas.index')
            ->with(
                'success',
                'Asignatura actualizada correctamente.'
            );
    }

    /**
     * Activar o desactivar una asignatura.
     */
    public function cambiarEstado(Asignatura $asignatura)
    {
        abort_unless(
            Auth::user()?->hasPermission(
                'asignaturas.cambiar_estado'
            ),
            403
        );

        $asignatura->update([
            'activo' => ! $asignatura->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $asignatura->activo
            ? 'Asignatura activada correctamente.'
            : 'Asignatura desactivada correctamente.';

        return redirect()
            ->route('asignaturas.index')
            ->with('success', $mensaje);
    }
}