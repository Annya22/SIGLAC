@extends('layouts.app')

@section('title', 'Editar nivel educativo | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 700px;
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

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .status-box {
        padding: 12px 14px;
        margin-bottom: 20px;
        border: 1px solid #eaecf0;
        border-radius: 7px;
        background: #f9fafb;
    }
</style>

<div class="page-header">

    <div>
        <h2>Editar nivel educativo</h2>

        <p>
            Actualice la información del nivel educativo seleccionado.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="status-box">

        <strong>Estado actual:</strong>

        @if ($nivel->activo)

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
        action="{{ route('niveles.update', $nivel) }}"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="nombre">
                Nombre del nivel educativo
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                value="{{ old('nombre', $nivel->nombre) }}"
                maxlength="100"
                required
                autofocus
                placeholder="Ingrese el nombre del nivel"
            >

            @error('nombre')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="orden">
                Orden
                <span class="required">*</span>
            </label>

            <input
                type="number"
                id="orden"
                name="orden"
                class="form-control"
                value="{{ old('orden', $nivel->orden) }}"
                min="1"
                max="999"
                step="1"
                required
            >

            <div class="help-text">
                Determina la posición en la que aparecerá este nivel
                dentro de los listados y formularios del sistema.
            </div>

            @error('orden')
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
                href="{{ route('niveles.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection