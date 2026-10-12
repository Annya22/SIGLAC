@extends('layouts.app')

@section('title', 'Nuevo grado | SIGLAC')

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
        <h2>Nuevo grado</h2>

        <p>
            Registre un grado y asócielo al nivel educativo
            correspondiente.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        Un mismo nombre de grado puede existir en niveles diferentes.
        Por ejemplo, puede existir <strong>1.º</strong> en Primaria
        y también <strong>1.º</strong> en Secundaria.
    </div>

    <form
        method="POST"
        action="{{ route('grados.store') }}"
    >

        @csrf

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

                <option value="">
                    Seleccione un nivel educativo
                </option>

                @foreach ($niveles as $nivel)

                    <option
                        value="{{ $nivel->id }}"
                        {{
                            (string) old('nivel_id')
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
                value="{{ old('nombre') }}"
                maxlength="50"
                required
                placeholder="Ejemplo: 1.º"
            >

            <div class="help-text">
                El nombre debe identificar el grado dentro del nivel
                seleccionado.
            </div>

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
                value="{{ old('orden') }}"
                min="1"
                max="999"
                step="1"
                required
                placeholder="Ejemplo: 1"
            >

            <div class="help-text">
                Determina la posición del grado dentro de su nivel.
                Por ejemplo: 1.º = orden 1, 2.º = orden 2.
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
                Guardar grado
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