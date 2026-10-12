<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GradoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado y búsqueda de grados
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('buscar'));
        $nivelId = $request->input('nivel_id');

        $niveles = Nivel::orderBy('orden')
            ->orderBy('nombre')
            ->get();

        $grados = Grado::query()
            ->with('nivel')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(
                    'nombre',
                    'like',
                    "%{$busqueda}%"
                );
            })
            ->when($nivelId, function ($query) use ($nivelId) {
                $query->where('nivel_id', $nivelId);
            })
            ->orderBy(
                Nivel::select('orden')
                    ->whereColumn('niveles.id', 'grados.nivel_id')
            )
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view(
            'grados.index',
            compact(
                'grados',
                'niveles',
                'busqueda',
                'nivelId'
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
        $niveles = Nivel::where('activo', true)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view(
            'grados.create',
            compact('niveles')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar grado
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $datos = $request->validate(
            [
                'nivel_id' => [
                    'required',
                    'integer',

                    Rule::exists('niveles', 'id')->where(
                        fn ($query) => $query->where('activo', true)
                    ),
                ],

                'nombre' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'orden' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:999',
                ],
            ],
            [
                'nivel_id.required' =>
                    'Debe seleccionar un nivel educativo.',

                'nivel_id.exists' =>
                    'El nivel educativo seleccionado no es válido o está inactivo.',

                'nombre.required' =>
                    'El nombre del grado es obligatorio.',

                'orden.required' =>
                    'El orden del grado es obligatorio.',

                'orden.integer' =>
                    'El orden debe ser un número entero.',

                'orden.min' =>
                    'El orden debe ser mayor o igual a 1.',
            ]
        );

        $nombre = trim($datos['nombre']);

        $existe = Grado::where(
            'nivel_id',
            $datos['nivel_id']
        )
            ->where(
                'nombre',
                $nombre
            )
            ->exists();

        if ($existe) {
            return back()
                ->withErrors([
                    'nombre' =>
                        'Ya existe un grado con ese nombre dentro del nivel seleccionado.',
                ])
                ->withInput();
        }

        Grado::create([
            'nivel_id' => $datos['nivel_id'],
            'nombre' => $nombre,
            'orden' => $datos['orden'],
            'activo' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('grados.index')
            ->with(
                'success',
                'Grado registrado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario de edición
    |--------------------------------------------------------------------------
    */
    public function edit(Grado $grado)
    {
        $niveles = Nivel::query()
            ->where(function ($query) use ($grado) {
                $query->where('activo', true)
                    ->orWhere('id', $grado->nivel_id);
            })
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view(
            'grados.edit',
            compact(
                'grado',
                'niveles'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar grado
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Grado $grado
    ) {
        $datos = $request->validate(
            [
                'nivel_id' => [
                    'required',
                    'integer',
                    'exists:niveles,id',
                ],

                'nombre' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'orden' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:999',
                ],
            ],
            [
                'nivel_id.required' =>
                    'Debe seleccionar un nivel educativo.',

                'nivel_id.exists' =>
                    'El nivel educativo seleccionado no es válido.',

                'nombre.required' =>
                    'El nombre del grado es obligatorio.',

                'orden.required' =>
                    'El orden del grado es obligatorio.',

                'orden.integer' =>
                    'El orden debe ser un número entero.',

                'orden.min' =>
                    'El orden debe ser mayor o igual a 1.',
            ]
        );

        $nombre = trim($datos['nombre']);

        $existe = Grado::where(
            'nivel_id',
            $datos['nivel_id']
        )
            ->where(
                'nombre',
                $nombre
            )
            ->where(
                'id',
                '<>',
                $grado->id
            )
            ->exists();

        if ($existe) {
            return back()
                ->withErrors([
                    'nombre' =>
                        'Ya existe otro grado con ese nombre dentro del nivel seleccionado.',
                ])
                ->withInput();
        }

        $grado->update([
            'nivel_id' => $datos['nivel_id'],
            'nombre' => $nombre,
            'orden' => $datos['orden'],
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('grados.index')
            ->with(
                'success',
                'Grado actualizado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Activar o desactivar grado
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Grado $grado)
    {
        $grado->update([
            'activo' => !$grado->activo,
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            $grado->activo
                ? 'Grado activado correctamente.'
                : 'Grado desactivado correctamente.'
        );
    }
}