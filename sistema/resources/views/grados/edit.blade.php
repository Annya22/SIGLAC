@extends('layouts.app')

@section('title', 'Editar grado | SIGLAC')

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
</style>

<div class="page-header">

    <div>
        <h2>Editar grado</h2>

        <p>
            Actualice la información del grado seleccionado.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="status-box">

        <strong>Estado actual:</strong>

        @if ($grado->activo)

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
        action="{{ route('grados.update', $grado) }}"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="nivel_id">
                Nivel educativo
                <span class="required">*</span>
            </label>

            <select
                id="nivel_id"
                name="nivel_id"
                class="form-control"
                required
                autofocus
            >

                @foreach ($niveles as $nivel)

                    <option
                        value="{{ $nivel->id }}"
                        {{
                            (string) old('nivel_id', $grado->nivel_id)
                                === (string) $nivel->id
                                    ? 'selected'
                                    : ''
                        }}
                    >
                        {{ $nivel->nombre }}
                    </option>

                @endforeach

            </select>

            @error('nivel_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="nombre">
                Nombre del grado
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                value="{{ old('nombre', $grado->nombre) }}"
                maxlength="50"
                required
                placeholder="Ejemplo: 1.º"
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
                value="{{ old('orden', $grado->orden) }}"
                min="1"
                max="999"
                step="1"
                required
            >

            <div class="help-text">
                Determina la posición del grado dentro del nivel educativo.
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
                href="{{ route('grados.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection