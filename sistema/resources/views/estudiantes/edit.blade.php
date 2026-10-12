@extends('layouts.app')

@section('title', 'Editar estudiante | SIGLAC')

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
        background: #ffffff;
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

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .status-box {
        padding: 12px 14px;
        margin-bottom: 20px;
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

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">

    <div>
        <h2>Editar estudiante</h2>

        <p>
            Actualice los datos de identificación del estudiante.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="status-box">

        <strong>Estado actual:</strong>

        @if ($estudiante->activo)

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
        action="{{ route('estudiantes.update', $estudiante) }}"
    >

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label for="tipo_documento">
                    Tipo de documento
                </label>

                <input
                    type="text"
                    id="tipo_documento"
                    name="tipo_documento"
                    class="form-control"
                    value="{{ old('tipo_documento', $estudiante->tipo_documento) }}"
                    maxlength="30"
                    placeholder="Ejemplo: DNI"
                >

                <div class="help-text">
                    Puede dejarse vacío si no se conoce el documento.
                </div>

                @error('tipo_documento')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label for="numero_documento">
                    Número de documento
                </label>

                <input
                    type="text"
                    id="numero_documento"
                    name="numero_documento"
                    class="form-control"
                    value="{{ old('numero_documento', $estudiante->numero_documento) }}"
                    maxlength="30"
                    placeholder="Ejemplo: 72845612"
                >

                @error('numero_documento')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="form-grid">

            <div class="form-group">

                <label for="apellido_paterno">
                    Apellido paterno
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="apellido_paterno"
                    name="apellido_paterno"
                    class="form-control"
                    value="{{ old('apellido_paterno', $estudiante->apellido_paterno) }}"
                    maxlength="100"
                    required
                    autofocus
                >

                @error('apellido_paterno')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label for="apellido_materno">
                    Apellido materno
                </label>

                <input
                    type="text"
                    id="apellido_materno"
                    name="apellido_materno"
                    class="form-control"
                    value="{{ old('apellido_materno', $estudiante->apellido_materno) }}"
                    maxlength="100"
                >

                <div class="help-text">
                    Opcional si no figura en la documentación histórica.
                </div>

                @error('apellido_materno')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="form-group">

            <label for="nombres">
                Nombres
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="nombres"
                name="nombres"
                class="form-control"
                value="{{ old('nombres', $estudiante->nombres) }}"
                maxlength="150"
                required
            >

            @error('nombres')
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
                href="{{ route('estudiantes.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection