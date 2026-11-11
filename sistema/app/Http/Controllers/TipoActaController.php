<?php

namespace App\Http\Controllers;

use App\Models\TipoActa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TipoActaController extends Controller
{
    /**
     * Mostrar el listado de tipos de acta.
     */
    public function index(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('tipos_acta.ver'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $tiposActa = TipoActa::query()
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

        return view('tipos-acta.index', compact(
            'tiposActa',
            'buscar'
        ));
    }

    /**
     * Mostrar el formulario de creación.
     */
    public function create()
    {
        abort_unless(
            Auth::user()?->hasPermission('tipos_acta.crear'),
            403
        );

        return view('tipos-acta.create');
    }

    /**
     * Registrar un nuevo tipo de acta.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermission('tipos_acta.crear'),
            403
        );

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:tipos_acta,nombre',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'El nombre del tipo de acta es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.unique' => 'Ya existe un tipo de acta con ese nombre.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',
        ]);

        $datos['activo'] = true;
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();

        TipoActa::create($datos);

        return redirect()
            ->route('tipos-acta.index')
            ->with(
                'success',
                'Tipo de acta registrado correctamente.'
            );
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(TipoActa $tipoActa)
    {
        abort_unless(
            Auth::user()?->hasPermission('tipos_acta.editar'),
            403
        );

        return view(
            'tipos-acta.edit',
            compact('tipoActa')
        );
    }

    /**
     * Actualizar un tipo de acta.
     */
    public function update(Request $request, TipoActa $tipoActa)
    {
        abort_unless(
            Auth::user()?->hasPermission('tipos_acta.editar'),
            403
        );

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tipos_acta', 'nombre')
                    ->ignore($tipoActa->id),
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'El nombre del tipo de acta es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.unique' => 'Ya existe un tipo de acta con ese nombre.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',
        ]);

        $datos['updated_by'] = Auth::id();

        $tipoActa->update($datos);

        return redirect()
            ->route('tipos-acta.index')
            ->with(
                'success',
                'Tipo de acta actualizado correctamente.'
            );
    }

    /**
     * Activar o desactivar un tipo de acta.
     */
    public function cambiarEstado(TipoActa $tipoActa)
    {
        abort_unless(
            Auth::user()?->hasPermission('tipos_acta.cambiar_estado'),
            403
        );

        $tipoActa->update([
            'activo' => ! $tipoActa->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $tipoActa->activo
            ? 'Tipo de acta activado correctamente.'
            : 'Tipo de acta desactivado correctamente.';

        return redirect()
            ->route('tipos-acta.index')
            ->with('success', $mensaje);
    }
}