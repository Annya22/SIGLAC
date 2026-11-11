@extends('layouts.app')

@section('title', 'Editar ubicación | SIGLAC')

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

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
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
        <h2>Editar ubicación física</h2>

        <p>
            Actualice los datos utilizados para localizar físicamente la documentación histórica.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="status-box">

        <strong>Estado actual:</strong>

        @if ($ubicacion->activo)

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
        action="{{ route('ubicaciones.update', $ubicacion) }}"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="ambiente">
                Ambiente
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="ambiente"
                name="ambiente"
                class="form-control"
                value="{{ old('ambiente', $ubicacion->ambiente) }}"
                maxlength="100"
                required
                autofocus
            >

            @error('ambiente')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-grid">

            <div class="form-group">

                <label for="estante">
                    Estante
                </label>

                <input
                    type="text"
                    id="estante"
                    name="estante"
                    class="form-control"
                    value="{{ old('estante', $ubicacion->estante) }}"
                    maxlength="50"
                >

                <div class="help-text">
                    Puede dejarse vacío si no existe una identificación de estante.
                </div>

                @error('estante')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label for="caja">
                    Caja
                </label>

                <input
                    type="text"
                    id="caja"
                    name="caja"
                    class="form-control"
                    value="{{ old('caja', $ubicacion->caja) }}"
                    maxlength="50"
                >

                <div class="help-text">
                    Puede dejarse vacío si aún no se conoce la caja.
                </div>

                @error('caja')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="form-group">

            <label for="descripcion">
                Descripción
            </label>

            <textarea
                id="descripcion"
                name="descripcion"
                class="form-control"
                maxlength="255"
            >{{ old('descripcion', $ubicacion->descripcion) }}</textarea>

            <div class="help-text">
                Campo opcional. Máximo 255 caracteres.
            </div>

            @error('descripcion')
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
                href="{{ route('ubicaciones.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection