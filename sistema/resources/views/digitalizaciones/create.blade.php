@extends('layouts.app')

@section('title', 'Digitalizar documento | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 950px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 14px;
        font-weight: 600;
        color: #344054;
    }

    .form-control {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        background: white;
        outline: none;
    }

    .form-control:focus {
        border-color: #17365d;
    }

    .required {
        color: #b42318;
    }

    .help-text {
        margin-top: 6px;
        color: #667085;
        font-size: 12px;
        line-height: 1.5;
    }

    .error-text {
        margin-top: 6px;
        color: #b42318;
        font-size: 13px;
    }

    .info-box {
        padding: 14px 16px;
        margin-bottom: 24px;
        border: 1px solid #d0d5dd;
        border-radius: 8px;
        background: #f9fafb;
        color: #475467;
        font-size: 13px;
        line-height: 1.6;
    }

    .file-box {
        border: 2px dashed #d0d5dd;
        border-radius: 9px;
        padding: 22px;
        background: #f9fafb;
    }

    .file-box input {
        width: 100%;
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
        <h2>Digitalizar documento</h2>

        <p>
            Cargue un documento histórico en PDF, JPG o PNG para conservarlo en el repositorio de SIGLAC.
        </p>
    </div>

</div>

<div class="card form-card">

    <div class="info-box">
        El archivo será almacenado de forma privada.
        Posteriormente SIGLAC permitirá procesarlo mediante OCR,
        detectar automáticamente sus datos y presentarlos para revisión
        antes de registrarlos definitivamente.
    </div>

    <form
        method="POST"
        action="{{ route('digitalizaciones.store') }}"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="form-group">

            <label for="tipo_documento">
                Tipo de documento
                <span class="required">*</span>
            </label>

            <select
                id="tipo_documento"
                name="tipo_documento"
                class="form-control"
                required
            >

                <option value="">
                    Seleccione un tipo de documento
                </option>

                @foreach ($tiposDocumento as $valor => $nombre)

                    <option
                        value="{{ $valor }}"
                        @selected(old('tipo_documento') === $valor)
                    >
                        {{ $nombre }}
                    </option>

                @endforeach

            </select>

            @error('tipo_documento')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="acta_id">
                Vincular con un acta existente
            </label>

            <select
                id="acta_id"
                name="acta_id"
                class="form-control"
            >

                <option value="">
                    Sin vincular por ahora
                </option>

                @foreach ($actas as $acta)

                    <option
                        value="{{ $acta->id }}"
                        @selected(old('acta_id') == $acta->id)
                    >
                        {{ $acta->numero_acta ?: 'Acta sin número' }}
                        / {{ $acta->anio }}

                        @if ($acta->tipoActa)
                            — {{ $acta->tipoActa->nombre }}
                        @endif

                        @if ($acta->institucionEducativa)
                            — {{ $acta->institucionEducativa->nombre }}
                        @endif
                    </option>

                @endforeach

            </select>

            <div class="help-text">
                Este campo es opcional. Puede cargar primero el documento y vincularlo posteriormente después de revisar los datos extraídos mediante OCR.
            </div>

            @error('acta_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="archivo">
                Documento digital
                <span class="required">*</span>
            </label>

            <div class="file-box">

                <input
                    type="file"
                    id="archivo"
                    name="archivo"
                    accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                    required
                >

                <div class="help-text">
                    Formatos permitidos: PDF, JPG, JPEG y PNG.
                    Tamaño máximo: 20 MB.
                </div>

            </div>

            @error('archivo')
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
                Guardar documento
            </button>

            <a
                href="{{ route('digitalizaciones.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection