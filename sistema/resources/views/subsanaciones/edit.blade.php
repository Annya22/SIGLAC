@extends('layouts.app')

@section('title', 'Editar subsanación | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 950px;
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
        <h2>Editar subsanación</h2>

        <p>
            Actualice la información registrada para esta subsanación.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="status-box">

        <strong>Estado actual:</strong>

        @if ($subsanacion->activo)

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
            'subsanaciones.update',
            $subsanacion
        ) }}"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="curso_desaprobado_id">
                Curso desaprobado
                <span class="required">*</span>
            </label>

            <select
                id="curso_desaprobado_id"
                name="curso_desaprobado_id"
                class="form-control"
                required
            >

                @foreach ($cursosDesaprobados as $curso)

                    @php
                        $relacion = $curso->actaEstudiante;
                        $estudiante = $relacion->estudiante;
                        $acta = $relacion->acta;
                    @endphp

                    <option
                        value="{{ $curso->id }}"
                        @selected(
                            old(
                                'curso_desaprobado_id',
                                $subsanacion->curso_desaprobado_id
                            ) == $curso->id
                        )
                    >
                        {{ $estudiante->apellido_paterno }}
                        {{ $estudiante->apellido_materno }}
                        {{ $estudiante->nombres }}
                        —
                        {{ $curso->asignatura->nombre }}
                        —
                        {{ $acta->numero_acta ?: 'Acta sin número' }}
                        / {{ $acta->anio }}
                    </option>

                @endforeach

            </select>

            @error('curso_desaprobado_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="acta_subsanacion_id">
                Acta de subsanación
                <span class="required">*</span>
            </label>

            <select
                id="acta_subsanacion_id"
                name="acta_subsanacion_id"
                class="form-control"
                required
            >

                @foreach ($actasSubsanacion as $acta)

                    <option
                        value="{{ $acta->id }}"
                        @selected(
                            old(
                                'acta_subsanacion_id',
                                $subsanacion->acta_subsanacion_id
                            ) == $acta->id
                        )
                    >
                        {{ $acta->numero_acta ?: 'Acta sin número' }}
                        / {{ $acta->anio }}
                        —
                        {{ $acta->institucionEducativa->nombre }}
                        —
                        {{ $acta->nivel->nombre }}

                        @if ($acta->grado)
                            / {{ $acta->grado->nombre }}
                        @endif
                    </option>

                @endforeach

            </select>

            @error('acta_subsanacion_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="fecha_subsanacion">
                Fecha de subsanación
            </label>

            <input
                type="date"
                id="fecha_subsanacion"
                name="fecha_subsanacion"
                class="form-control"
                value="{{ old(
                    'fecha_subsanacion',
                    $subsanacion->fecha_subsanacion
                        ? $subsanacion->fecha_subsanacion->format('Y-m-d')
                        : ''
                ) }}"
                max="{{ now()->format('Y-m-d') }}"
            >

            @error('fecha_subsanacion')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="resultado">
                Resultado
                <span class="required">*</span>
            </label>

            <select
                id="resultado"
                name="resultado"
                class="form-control"
                required
            >

                @foreach ($resultados as $resultado)

                    <option
                        value="{{ $resultado }}"
                        @selected(
                            old(
                                'resultado',
                                $subsanacion->resultado
                            ) === $resultado
                        )
                    >
                        {{ $resultado }}
                    </option>

                @endforeach

            </select>

            @error('resultado')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="calificacion">
                Calificación
            </label>

            <input
                type="text"
                id="calificacion"
                name="calificacion"
                class="form-control"
                maxlength="20"
                value="{{ old(
                    'calificacion',
                    $subsanacion->calificacion
                ) }}"
            >

            <div class="help-text">
                Campo opcional.
            </div>

            @error('calificacion')
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
                $subsanacion->observacion
            ) }}</textarea>

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
                href="{{ route('subsanaciones.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection