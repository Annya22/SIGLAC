@extends('layouts.app')

@section('title', 'Nueva subsanación | SIGLAC')

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
        line-height: 1.5;
    }

    .info-box {
        padding: 12px 14px;
        margin-bottom: 22px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        background: #f9fafb;
        color: #475467;
        font-size: 13px;
        line-height: 1.6;
    }

    .warning-box {
        padding: 12px 14px;
        margin-bottom: 22px;
        border: 1px solid #fecdca;
        border-radius: 7px;
        background: #fef3f2;
        color: #b42318;
        font-size: 13px;
        line-height: 1.6;
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
        <h2>Nueva subsanación</h2>

        <p>
            Registre el resultado de subsanación correspondiente a un curso desaprobado.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        La subsanación debe relacionar un curso desaprobado existente con un documento registrado como
        <strong>Acta de subsanación</strong>.
    </div>

    @if ($cursosDesaprobados->isEmpty())

        <div class="warning-box">
            No existen cursos desaprobados activos disponibles.
            Primero debe registrar un curso desaprobado.
        </div>

    @endif

    @if ($actasSubsanacion->isEmpty())

        <div class="warning-box">
            No existen actas activas del tipo <strong>Acta de subsanación</strong>.
            Primero debe registrar el documento correspondiente desde Gestión documental → Actas históricas.
        </div>

    @endif

    <form
        method="POST"
        action="{{ route('subsanaciones.store') }}"
    >

        @csrf

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

                <option value="">
                    Seleccione un curso desaprobado
                </option>

                @foreach ($cursosDesaprobados as $curso)

                    @php
                        $relacion = $curso->actaEstudiante;
                        $estudiante = $relacion->estudiante;
                        $acta = $relacion->acta;
                    @endphp

                    <option
                        value="{{ $curso->id }}"
                        @selected(
                            old('curso_desaprobado_id') == $curso->id
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

            <div class="help-text">
                Se muestran únicamente cursos desaprobados activos con relaciones académicas activas.
            </div>

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

                <option value="">
                    Seleccione un acta de subsanación
                </option>

                @foreach ($actasSubsanacion as $acta)

                    <option
                        value="{{ $acta->id }}"
                        @selected(
                            old('acta_subsanacion_id') == $acta->id
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

            <div class="help-text">
                Solo aparecen documentos activos cuyo tipo sea “Acta de subsanación”.
            </div>

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
                value="{{ old('fecha_subsanacion') }}"
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

                <option value="">
                    Seleccione un resultado
                </option>

                @foreach ($resultados as $resultado)

                    <option
                        value="{{ $resultado }}"
                        @selected(
                            old('resultado') === $resultado
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
                value="{{ old('calificacion') }}"
                placeholder="Ejemplo: 14"
            >

            <div class="help-text">
                Campo opcional. Se conserva como texto para admitir distintos sistemas históricos de calificación.
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
                placeholder="Información complementaria sobre la subsanación"
            >{{ old('observacion') }}</textarea>

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
                @disabled(
                    $cursosDesaprobados->isEmpty() ||
                    $actasSubsanacion->isEmpty()
                )
            >
                Guardar subsanación
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