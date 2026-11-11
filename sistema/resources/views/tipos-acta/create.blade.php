@extends('layouts.app')

@section('title', 'Nuevo tipo de acta | SIGLAC')

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

    .info-box {
        padding: 12px 14px;
        margin-bottom: 20px;
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
</style>

<div class="page-header">

    <div>
        <h2>Nuevo tipo de acta</h2>

        <p>
            Registre un tipo de acta utilizado para clasificar la documentación histórica.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        El nombre identifica el tipo de documento dentro de SIGLAC.
        La descripción es opcional y puede utilizarse para precisar su finalidad.
    </div>

    <form
        method="POST"
        action="{{ route('tipos-acta.store') }}"
    >

        @csrf

        <div class="form-group">

            <label for="nombre">
                Nombre
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                value="{{ old('nombre') }}"
                maxlength="100"
                required
                autofocus
                placeholder="Ejemplo: Acta histórica"
            >

            @error('nombre')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

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
                placeholder="Ingrese una descripción del tipo de acta"
            >{{ old('descripcion') }}</textarea>

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
                Guardar tipo de acta
            </button>

            <a
                href="{{ route('tipos-acta.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection