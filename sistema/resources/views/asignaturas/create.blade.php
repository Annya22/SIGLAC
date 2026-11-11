@extends('layouts.app')

@section('title', 'Nueva asignatura | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 750px;
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
</style>

<div class="page-header">

    <div>
        <h2>Nueva asignatura</h2>

        <p>
            Registre una asignatura que podrá utilizarse posteriormente en los procesos de subsanación.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        Registre el nombre oficial de la asignatura. Evite crear nombres duplicados para conservar la consistencia de la información.
    </div>

    <form
        method="POST"
        action="{{ route('asignaturas.store') }}"
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
                maxlength="150"
                required
                placeholder="Ejemplo: Matemática"
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
                placeholder="Descripción opcional de la asignatura"
            >{{ old('descripcion') }}</textarea>

            <div class="help-text">
                Este campo es opcional.
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
                Guardar asignatura
            </button>

            <a
                href="{{ route('asignaturas.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection