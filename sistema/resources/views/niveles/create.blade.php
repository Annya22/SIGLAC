@extends('layouts.app')

@section('title', 'Nuevo nivel educativo | SIGLAC')

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
</style>

<div class="page-header">

    <div>
        <h2>Nuevo nivel educativo</h2>

        <p>
            Registre un nivel educativo que podrá utilizarse
            posteriormente en instituciones, grados y documentación
            histórica.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        SIGLAC ya cuenta con los niveles
        <strong>Primaria</strong> y
        <strong>Secundaria</strong>.
        Utilice este formulario solamente cuando sea necesario
        incorporar un nivel educativo adicional.
    </div>

    <form
        method="POST"
        action="{{ route('niveles.store') }}"
    >

        @csrf

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
                value="{{ old('nombre') }}"
                maxlength="100"
                required
                autofocus
                placeholder="Ejemplo: Inicial"
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
                value="{{ old('orden', $siguienteOrden) }}"
                min="1"
                max="999"
                step="1"
                required
            >

            <div class="help-text">
                Determina la posición en la que aparecerá el nivel
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
                Guardar nivel
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