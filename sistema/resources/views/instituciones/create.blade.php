@extends('layouts.app')

@section('title', 'Nueva institución educativa | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 760px;
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
    }

    .form-control:focus {
        border-color: #17365d;
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

    .levels-box {
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        padding: 15px;
    }

    .level-option {
        display: flex !important;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px !important;
        font-weight: normal !important;
        cursor: pointer;
    }

    .level-option:last-child {
        margin-bottom: 0 !important;
    }

    .level-option input[type="checkbox"] {
        width: auto;
        margin: 0;
        flex-shrink: 0;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
        flex-wrap: wrap;
    }
</style>

<div class="page-header">

    <div>
        <h2>Nueva institución educativa</h2>

        <p>
            Registre una institución educativa para su posterior
            asociación con actas y documentación histórica.
        </p>
    </div>

</div>

<div class="card form-card">

    <form
        method="POST"
        action="{{ route('instituciones.store') }}"
    >

        @csrf

        <div class="form-group">

            <label for="codigo_modular">
                Código modular
            </label>

            <input
                type="text"
                id="codigo_modular"
                name="codigo_modular"
                class="form-control"
                value="{{ old('codigo_modular') }}"
                maxlength="20"
                placeholder="Ejemplo: 1234567"
            >

            <div class="help-text">
                Opcional. Puede dejarse vacío si no se conoce el código
                modular de una institución histórica.
            </div>

            @error('codigo_modular')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="nombre">
                Nombre de la institución educativa
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                value="{{ old('nombre') }}"
                maxlength="255"
                required
                autofocus
                placeholder="Ingrese el nombre de la institución"
            >

            @error('nombre')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        @if ($puedeAsignarNiveles)

            <div class="form-group">

                <label>
                    Niveles educativos
                </label>

                <div class="levels-box">

                    @forelse ($niveles as $nivel)

                        <label class="level-option">

                            <input
                                type="checkbox"
                                name="niveles[]"
                                value="{{ $nivel->id }}"
                                {{
                                    in_array(
                                        $nivel->id,
                                        old('niveles', [])
                                    )
                                        ? 'checked'
                                        : ''
                                }}
                            >

                            <span>
                                <strong>{{ $nivel->nombre }}</strong>
                            </span>

                        </label>

                    @empty

                        <p style="margin: 0; color: #667085;">
                            No existen niveles educativos activos disponibles.
                        </p>

                    @endforelse

                </div>

                <div class="help-text">
                    Puede seleccionar uno o varios niveles educativos.
                </div>

                @error('niveles')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

                @error('niveles.*')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        @endif

        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar institución
            </button>

            <a
                href="{{ route('instituciones.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection
