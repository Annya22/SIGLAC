<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado y búsqueda
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('buscar'));

        $estudiantes = Estudiante::query()
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where(
                        'numero_documento',
                        'like',
                        "%{$busqueda}%"
                    )
                    ->orWhere(
                        'apellido_paterno',
                        'like',
                        "%{$busqueda}%"
                    )
                    ->orWhere(
                        'apellido_materno',
                        'like',
                        "%{$busqueda}%"
                    )
                    ->orWhere(
                        'nombres',
                        'like',
                        "%{$busqueda}%"
                    )
                    ->orWhereRaw(
                        "CONCAT_WS(' ', apellido_paterno, apellido_materno, nombres) LIKE ?",
                        ["%{$busqueda}%"]
                    );
                });
            })
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->paginate(15)
            ->withQueryString();

        return view(
            'estudiantes.index',
            compact(
                'estudiantes',
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
        return view('estudiantes.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar estudiante
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $datos = $request->validate(
            [
                'tipo_documento' => [
                    'nullable',
                    'string',
                    'max:30',
                    'required_with:numero_documento',
                ],

                'numero_documento' => [
                    'nullable',
                    'string',
                    'max:30',
                    'required_with:tipo_documento',
                ],

                'apellido_paterno' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'apellido_materno' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'nombres' => [
                    'required',
                    'string',
                    'max:150',
                ],
            ],
            [
                'tipo_documento.required_with' =>
                    'Debe indicar el tipo de documento cuando registra un número de documento.',

                'numero_documento.required_with' =>
                    'Debe indicar el número de documento cuando selecciona un tipo de documento.',

                'apellido_paterno.required' =>
                    'El apellido paterno es obligatorio.',

                'nombres.required' =>
                    'Los nombres del estudiante son obligatorios.',
            ]
        );

        $tipoDocumento = $this->normalizarTexto(
            $datos['tipo_documento'] ?? null
        );

        $numeroDocumento = $this->normalizarTexto(
            $datos['numero_documento'] ?? null
        );

        if ($tipoDocumento !== null && $numeroDocumento !== null) {
            $existe = Estudiante::where(
                'tipo_documento',
                $tipoDocumento
            )
                ->where(
                    'numero_documento',
                    $numeroDocumento
                )
                ->exists();

            if ($existe) {
                return back()
                    ->withErrors([
                        'numero_documento' =>
                            'Ya existe un estudiante registrado con ese tipo y número de documento.',
                    ])
                    ->withInput();
            }
        }

        Estudiante::create([
            'tipo_documento' => $tipoDocumento,
            'numero_documento' => $numeroDocumento,

            'apellido_paterno' =>
                trim($datos['apellido_paterno']),

            'apellido_materno' =>
                $this->normalizarTexto(
                    $datos['apellido_materno'] ?? null
                ),

            'nombres' =>
                trim($datos['nombres']),

            'activo' => true,

            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('estudiantes.index')
            ->with(
                'success',
                'Estudiante registrado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario de edición
    |--------------------------------------------------------------------------
    */
    public function edit(Estudiante $estudiante)
    {
        return view(
            'estudiantes.edit',
            compact('estudiante')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar estudiante
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Estudiante $estudiante
    ) {
        $datos = $request->validate(
            [
                'tipo_documento' => [
                    'nullable',
                    'string',
                    'max:30',
                    'required_with:numero_documento',
                ],

                'numero_documento' => [
                    'nullable',
                    'string',
                    'max:30',
                    'required_with:tipo_documento',
                ],

                'apellido_paterno' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'apellido_materno' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'nombres' => [
                    'required',
                    'string',
                    'max:150',
                ],
            ],
            [
                'tipo_documento.required_with' =>
                    'Debe indicar el tipo de documento cuando registra un número de documento.',

                'numero_documento.required_with' =>
                    'Debe indicar el número de documento cuando selecciona un tipo de documento.',

                'apellido_paterno.required' =>
                    'El apellido paterno es obligatorio.',

                'nombres.required' =>
                    'Los nombres del estudiante son obligatorios.',
            ]
        );

        $tipoDocumento = $this->normalizarTexto(
            $datos['tipo_documento'] ?? null
        );

        $numeroDocumento = $this->normalizarTexto(
            $datos['numero_documento'] ?? null
        );

        if ($tipoDocumento !== null && $numeroDocumento !== null) {
            $existe = Estudiante::where(
                'tipo_documento',
                $tipoDocumento
            )
                ->where(
                    'numero_documento',
                    $numeroDocumento
                )
                ->where(
                    'id',
                    '<>',
                    $estudiante->id
                )
                ->exists();

            if ($existe) {
                return back()
                    ->withErrors([
                        'numero_documento' =>
                            'Ya existe otro estudiante registrado con ese tipo y número de documento.',
                    ])
                    ->withInput();
            }
        }

        $estudiante->update([
            'tipo_documento' => $tipoDocumento,
            'numero_documento' => $numeroDocumento,

            'apellido_paterno' =>
                trim($datos['apellido_paterno']),

            'apellido_materno' =>
                $this->normalizarTexto(
                    $datos['apellido_materno'] ?? null
                ),

            'nombres' =>
                trim($datos['nombres']),

            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('estudiantes.index')
            ->with(
                'success',
                'Estudiante actualizado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Activar o desactivar estudiante
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Estudiante $estudiante)
    {
        $estudiante->update([
            'activo' => !$estudiante->activo,
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            $estudiante->activo
                ? 'Estudiante activado correctamente.'
                : 'Estudiante desactivado correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Normalizar campos opcionales
    |--------------------------------------------------------------------------
    */
    private function normalizarTexto(
        ?string $valor
    ): ?string {
        if ($valor === null) {
            return null;
        }

        $valor = trim($valor);

        return $valor === ''
            ? null
            : $valor;
    }
}