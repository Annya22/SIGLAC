@extends('layouts.app')

@section('title', 'Nueva acta histórica | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 900px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section-title {
        margin: 0 0 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eaecf0;
        color: #17365d;
        font-size: 16px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #344054;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
        background: #ffffff;
    }

    .form-control:focus {
        border-color: #17365d;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .required {
        color: #b42318;
    }

    .error-text {
        color: #b42318;
        font-size: 13px;
        margin-top: 6px;
    }

    .help-text {
        color: #667085;
        font-size: 12px;
        margin-top: 6px;
    }

    .info-box {
        padding: 12px 14px;
        margin-bottom: 22px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        background: #f9fafb;
        color: #475467;
        font-size: 13px;
        line-height: 1.5;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">

    <div>
        <h2>Nueva acta histórica</h2>

        <p>
            Registre los datos de identificación, contexto académico y ubicación física del acta.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        Los campos marcados con * son obligatorios.
        El número de acta, grado, fecha, sección y ubicación física pueden completarse posteriormente cuando la documentación histórica no permita identificarlos inicialmente.
    </div>

    <form
        method="POST"
        action="{{ route('actas.store') }}"
    >

        @csrf

        <div class="form-section">

            <h3 class="form-section-title">
                Identificación del acta
            </h3>

            <div class="form-grid">

                <div class="form-group">

                    <label for="tipo_acta_id">
                        Tipo de acta
                        <span class="required">*</span>
                    </label>

                    <select
                        id="tipo_acta_id"
                        name="tipo_acta_id"
                        class="form-control"
                        required
                    >
                        <option value="">
                            Seleccione un tipo de acta
                        </option>

                        @foreach ($tiposActa as $tipoActa)

                            <option
                                value="{{ $tipoActa->id }}"
                                @selected(old('tipo_acta_id') == $tipoActa->id)
                            >
                                {{ $tipoActa->nombre }}
                            </option>

                        @endforeach
                    </select>

                    @error('tipo_acta_id')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="numero_acta">
                        Número o código del acta
                    </label>

                    <input
                        type="text"
                        id="numero_acta"
                        name="numero_acta"
                        class="form-control"
                        value="{{ old('numero_acta') }}"
                        maxlength="50"
                        placeholder="Ejemplo: ACT-025"
                    >

                    <div class="help-text">
                        Opcional si el documento no presenta un número o código identificable.
                    </div>

                    @error('numero_acta')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="form-grid">

                <div class="form-group">

                    <label for="anio">
                        Año académico
                        <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="anio"
                        name="anio"
                        class="form-control"
                        value="{{ old('anio') }}"
                        min="1000"
                        max="{{ date('Y') }}"
                        required
                        placeholder="Ejemplo: 1998"
                    >

                    @error('anio')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="fecha_acta">
                        Fecha del acta
                    </label>

                    <input
                        type="date"
                        id="fecha_acta"
                        name="fecha_acta"
                        class="form-control"
                        value="{{ old('fecha_acta') }}"
                        max="{{ date('Y-m-d') }}"
                    >

                    @error('fecha_acta')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>

        <div class="form-section">

            <h3 class="form-section-title">
                Datos académicos
            </h3>

            <div class="form-group">

                <label for="institucion_educativa_id">
                    Institución educativa
                    <span class="required">*</span>
                </label>

                <select
                    id="institucion_educativa_id"
                    name="institucion_educativa_id"
                    class="form-control"
                    required
                >
                    <option value="">
                        Seleccione una institución educativa
                    </option>

                    @foreach ($instituciones as $institucion)

                        <option
                            value="{{ $institucion->id }}"
                            @selected(
                                old('institucion_educativa_id') == $institucion->id
                            )
                        >
                            {{ $institucion->nombre }}

                            @if ($institucion->codigo_modular)
                                — {{ $institucion->codigo_modular }}
                            @endif
                        </option>

                    @endforeach
                </select>

                @error('institucion_educativa_id')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-grid">

                <div class="form-group">

                    <label for="nivel_id">
                        Nivel
                        <span class="required">*</span>
                    </label>

                    <select
                        id="nivel_id"
                        name="nivel_id"
                        class="form-control"
                        required
                    >
                        <option value="">
                            Seleccione un nivel
                        </option>

                        @foreach ($niveles as $nivel)

                            <option
                                value="{{ $nivel->id }}"
                                @selected(old('nivel_id') == $nivel->id)
                            >
                                {{ $nivel->nombre }}
                            </option>

                        @endforeach
                    </select>

                    <div class="help-text">
                        El sistema verificará que el nivel corresponda a la institución seleccionada.
                    </div>

                    @error('nivel_id')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="grado_id">
                        Grado
                    </label>

                    <select
                        id="grado_id"
                        name="grado_id"
                        class="form-control"
                    >
                        <option value="">
                            Sin grado especificado
                        </option>

                        @foreach ($grados as $grado)

                            <option
                                value="{{ $grado->id }}"
                                data-nivel="{{ $grado->nivel_id }}"
                                @selected(old('grado_id') == $grado->id)
                            >
                                {{ $grado->nombre }}
                                — {{ $grado->nivel->nombre }}
                            </option>

                        @endforeach
                    </select>

                    <div class="help-text">
                        Opcional cuando el grado no pueda identificarse en el documento histórico.
                    </div>

                    @error('grado_id')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="form-group">

                <label for="seccion">
                    Sección
                </label>

                <input
                    type="text"
                    id="seccion"
                    name="seccion"
                    class="form-control"
                    value="{{ old('seccion') }}"
                    maxlength="30"
                    placeholder="Ejemplo: A"
                >

                @error('seccion')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="form-section">

            <h3 class="form-section-title">
                Localización física
            </h3>

            <div class="form-group">

                <label for="ubicacion_id">
                    Ubicación
                </label>

                <select
                    id="ubicacion_id"
                    name="ubicacion_id"
                    class="form-control"
                >
                    <option value="">
                        Sin ubicación asignada
                    </option>

                    @foreach ($ubicaciones as $ubicacion)

                        <option
                            value="{{ $ubicacion->id }}"
                            @selected(old('ubicacion_id') == $ubicacion->id)
                        >
                            {{ $ubicacion->ambiente }}

                            @if ($ubicacion->estante)
                                — Estante {{ $ubicacion->estante }}
                            @endif

                            @if ($ubicacion->caja)
                                — Caja {{ $ubicacion->caja }}
                            @endif
                        </option>

                    @endforeach
                </select>

                <div class="help-text">
                    Puede dejarse pendiente y completarse cuando el documento sea localizado físicamente.
                </div>

                @error('ubicacion_id')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label for="observaciones">
                    Observaciones
                </label>

                <textarea
                    id="observaciones"
                    name="observaciones"
                    class="form-control"
                    placeholder="Registre precisiones relevantes sobre el acta"
                >{{ old('observaciones') }}</textarea>

                @error('observaciones')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar acta
            </button>

            <a
                href="{{ route('actas.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

<script>
    const nivelSelect = document.getElementById('nivel_id');
    const gradoSelect = document.getElementById('grado_id');

    function filtrarGrados() {
        const nivelId = nivelSelect.value;
        const valorActual = gradoSelect.value;

        Array.from(gradoSelect.options).forEach(function (option) {
            if (option.value === '') {
                option.hidden = false;
                return;
            }

            const pertenece =
                nivelId !== '' &&
                option.dataset.nivel === nivelId;

            option.hidden = !pertenece;
        });

        const opcionActual =
            gradoSelect.options[gradoSelect.selectedIndex];

        if (
            opcionActual &&
            opcionActual.value !== '' &&
            opcionActual.hidden
        ) {
            gradoSelect.value = '';
        }

        if (
            valorActual !== '' &&
            Array.from(gradoSelect.options).some(function (option) {
                return option.value === valorActual && !option.hidden;
            })
        ) {
            gradoSelect.value = valorActual;
        }
    }

    nivelSelect.addEventListener('change', filtrarGrados);

    filtrarGrados();
</script>

@endsection