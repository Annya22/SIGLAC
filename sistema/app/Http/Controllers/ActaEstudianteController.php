<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\ActaEstudiante;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ActaEstudianteController extends Controller
{
    /**
     * Mostrar los estudiantes asociados a un acta
     * y permitir buscar estudiantes para vincularlos.
     */
    public function index(Request $request, Acta $acta)
    {
        abort_unless(
            Auth::user()?->hasPermission('actas.asignar_estudiantes'),
            403
        );

        $buscar = trim((string) $request->input('buscar'));

        $acta->load([
            'tipoActa',
            'institucionEducativa',
            'nivel',
            'grado',
        ]);

        $relaciones = ActaEstudiante::query()
            ->with('estudiante')
            ->where('acta_id', $acta->id)
            ->orderByDesc('activo')
            ->orderByDesc('id')
            ->get();

        $estudiantes = Estudiante::query()
            ->where('activo', true)
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('nombres', 'like', "%{$buscar}%")
                        ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                        ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                        ->orWhere('numero_documento', 'like', "%{$buscar}%")
                        ->orWhereRaw(
                            "CONCAT_WS(' ', apellido_paterno, apellido_materno, nombres) LIKE ?",
                            ["%{$buscar}%"]
                        );
                });
            })
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->paginate(10)
            ->withQueryString();

        return view('actas.estudiantes', compact(
            'acta',
            'relaciones',
            'estudiantes',
            'buscar'
        ));
    }

    /**
     * Asociar un estudiante existente al acta.
     */
    public function store(Request $request, Acta $acta)
    {
        abort_unless(
            Auth::user()?->hasPermission('actas.asignar_estudiantes'),
            403
        );

        if (! $acta->activo) {
            throw ValidationException::withMessages([
                'estudiante_id' =>
                    'No se pueden asociar estudiantes a un acta inactiva.',
            ]);
        }

        $datos = $request->validate([
            'estudiante_id' => [
                'required',
                'integer',
                'exists:estudiantes,id',
            ],
        ], [
            'estudiante_id.required' =>
                'Debe seleccionar un estudiante.',
            'estudiante_id.exists' =>
                'El estudiante seleccionado no existe.',
        ]);

        $estudiante = Estudiante::findOrFail(
            $datos['estudiante_id']
        );

        if (! $estudiante->activo) {
            throw ValidationException::withMessages([
                'estudiante_id' =>
                    'El estudiante seleccionado se encuentra inactivo.',
            ]);
        }

        $relacion = ActaEstudiante::query()
            ->where('acta_id', $acta->id)
            ->where('estudiante_id', $estudiante->id)
            ->first();

        if ($relacion) {
            if ($relacion->activo) {
                throw ValidationException::withMessages([
                    'estudiante_id' =>
                        'El estudiante ya se encuentra asociado a esta acta.',
                ]);
            }

            $relacion->update([
                'activo' => true,
                'updated_by' => Auth::id(),
            ]);

            return redirect()
                ->route('actas.estudiantes.index', $acta)
                ->with(
                    'success',
                    'Estudiante asociado nuevamente al acta.'
                );
        }

        ActaEstudiante::create([
            'acta_id' => $acta->id,
            'estudiante_id' => $estudiante->id,
            'activo' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('actas.estudiantes.index', $acta)
            ->with(
                'success',
                'Estudiante asociado al acta correctamente.'
            );
    }

    /**
     * Activar o desactivar la relación entre
     * un estudiante y un acta.
     */
    public function cambiarEstado(
        Acta $acta,
        ActaEstudiante $actaEstudiante
    ) {
        abort_unless(
            Auth::user()?->hasPermission('actas.asignar_estudiantes'),
            403
        );

        if (
            (int) $actaEstudiante->acta_id !==
            (int) $acta->id
        ) {
            abort(404);
        }

        if (
            ! $acta->activo &&
            ! $actaEstudiante->activo
        ) {
            throw ValidationException::withMessages([
                'estudiante_id' =>
                    'No se puede reactivar una relación mientras el acta esté inactiva.',
            ]);
        }

        $actaEstudiante->update([
            'activo' => ! $actaEstudiante->activo,
            'updated_by' => Auth::id(),
        ]);

        $mensaje = $actaEstudiante->activo
            ? 'Estudiante asociado nuevamente al acta.'
            : 'Estudiante retirado de la relación con el acta.';

        return redirect()
            ->route('actas.estudiantes.index', $acta)
            ->with('success', $mensaje);
    }
}
