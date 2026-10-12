<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NivelController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado de niveles
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('buscar'));

        $niveles = Nivel::query()
            ->withCount([
                'grados',
                'institucionesEducativas',
            ])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(
                    'nombre',
                    'like',
                    "%{$busqueda}%"
                );
            })
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view(
            'niveles.index',
            compact(
                'niveles',
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
        $siguienteOrden = (
            (int) Nivel::max('orden')
        ) + 1;

        return view(
            'niveles.create',
            compact('siguienteOrden')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar nivel
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $datos = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:niveles,nombre',
                ],

                'orden' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:999',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del nivel educativo es obligatorio.',

                'nombre.unique' =>
                    'Ya existe un nivel educativo con ese nombre.',

                'orden.required' =>
                    'El orden del nivel educativo es obligatorio.',

                'orden.integer' =>
                    'El orden debe ser un número entero.',

                'orden.min' =>
                    'El orden debe ser mayor o igual a 1.',
            ]
        );

        Nivel::create([
            'nombre' => trim($datos['nombre']),
            'orden' => $datos['orden'],
            'activo' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('niveles.index')
            ->with(
                'success',
                'Nivel educativo registrado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario de edición
    |--------------------------------------------------------------------------
    */
    public function edit(Nivel $nivel)
    {
        return view(
            'niveles.edit',
            compact('nivel')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar nivel
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Nivel $nivel
    ) {
        $datos = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:100',

                    Rule::unique(
                        'niveles',
                        'nombre'
                    )->ignore($nivel->id),
                ],

                'orden' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:999',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del nivel educativo es obligatorio.',

                'nombre.unique' =>
                    'Ya existe otro nivel educativo con ese nombre.',

                'orden.required' =>
                    'El orden del nivel educativo es obligatorio.',

                'orden.integer' =>
                    'El orden debe ser un número entero.',

                'orden.min' =>
                    'El orden debe ser mayor o igual a 1.',
            ]
        );

        $nivel->update([
            'nombre' => trim($datos['nombre']),
            'orden' => $datos['orden'],
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('niveles.index')
            ->with(
                'success',
                'Nivel educativo actualizado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Activar o desactivar nivel
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Nivel $nivel)
    {
        $nivel->update([
            'activo' => !$nivel->activo,
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            $nivel->activo
                ? 'Nivel educativo activado correctamente.'
                : 'Nivel educativo desactivado correctamente.'
        );
    }
}