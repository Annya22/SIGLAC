@extends('layouts.app')

@section('title', 'Editar curso desaprobado | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 900px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 14px;
        font-weight: 600;
        color: #344054;
    }

    .form-control {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        background: white;
        outline: none;
    }

    .form-control:focus {
        border-color: #17365d;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .required {
        color: #b42318;
    }

    .error-text {
        margin-top: 6px;
        color: #b42318;
        font-size: 13px;
    }

    .help-text {
        margin-top: 6px;
        color: #667085;
        font-size: 12px;
    }

    .status-box {
        padding: 12px 14px;
        margin-bottom: 22px;
        border: 1px solid #eaecf0;
        border-radius: 7px;
        background: #f9fafb;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Editar curso desaprobado</h2>

        <p>
            Actualice la relación académica registrada.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="status-box">

        <strong>Estado actual:</strong>

        @if ($cursoDesaprobado->activo)

            <span class="badge badge-success">
                Activo
            </span>

        @else

            <span class="badge badge-danger">
                Inactivo
            </span>

        @endif

    </div>

    <form
        method="POST"
        action="{{ route(
            'cursos-desaprobados.update',
            $cursoDesaprobado
        ) }}"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="acta_estudiante_id">
                Estudiante y acta histórica
                <span class="required">*</span>
            </label>

            <select
                id="acta_estudiante_id"
                name="acta_estudiante_id"
                class="form-control"
                required
            >

                @foreach ($actaEstudiantes as $relacion)

                    <option
                        value="{{ $relacion->id }}"
                        @selected(
                            old(
                                'acta_estudiante_id',
                                $cursoDesaprobado->acta_estudiante_id
                            ) == $relacion->id
                        )
                    >
                        {{ $relacion->estudiante->apellido_paterno }}
                        {{ $relacion->estudiante->apellido_materno }}
                        {{ $relacion->estudiante->nombres }}
                        —
                        {{ $relacion->acta->numero_acta ?: 'Acta sin número' }}
                        / {{ $relacion->acta->anio }}
                        —
                        {{ $relacion->acta->institucionEducativa->nombre }}
                        —
                        {{ $relacion->acta->nivel->nombre }}

                        @if ($relacion->acta->grado)
                            / {{ $relacion->acta->grado->nombre }}
                        @endif
                    </option>

                @endforeach

            </select>

            @error('acta_estudiante_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="asignatura_id">
                Asignatura desaprobada
                <span class="required">*</span>
            </label>

            <select
                id="asignatura_id"
                name="asignatura_id"
                class="form-control"
                required
            >

                @foreach ($asignaturas as $asignatura)

                    <option
                        value="{{ $asignatura->id }}"
                        @selected(
                            old(
                                'asignatura_id',
                                $cursoDesaprobado->asignatura_id
                            ) == $asignatura->id
                        )
                    >
                        {{ $asignatura->nombre }}
                    </option>

                @endforeach

            </select>

            @error('asignatura_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="observacion">
                Observación
            </label>

            <textarea
                id="observacion"
                name="observacion"
                class="form-control"
                maxlength="255"
            >{{ old(
                'observacion',
                $cursoDesaprobado->observacion
            ) }}</textarea>

            <div class="help-text">
                Este campo es opcional.
            </div>

            @error('observacion')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar cambios
            </button>

            <a
                href="{{ route('cursos-desaprobados.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection