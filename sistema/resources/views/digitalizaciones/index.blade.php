@extends('layouts.app')

@section('title', 'Digitalizaciones | SIGLAC')

@section('content')

<style>
    .search-bar {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .search-input {
        width: 100%;
        max-width: 520px;
        padding: 10px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #17365d;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .main-text {
        font-weight: 600;
        color: #101828;
    }

    .secondary-text {
        margin-top: 4px;
        color: #667085;
        font-size: 12px;
        line-height: 1.4;
    }

    .empty-text {
        color: #667085;
        font-size: 13px;
    }

    .badge-warning {
        background: #fffaeb;
        color: #b54708;
    }

    .badge-info {
        background: #eff8ff;
        color: #175cd3;
    }

    .pagination-container {
        margin-top: 20px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Documentos digitalizados</h2>

        <p>
            Repositorio de documentos históricos escaneados y cargados en SIGLAC.
        </p>
    </div>

    @if (auth()->user()->hasPermission('digitalizaciones.crear'))

        <a
            href="{{ route('digitalizaciones.create') }}"
            class="btn btn-primary"
        >
            + Digitalizar documento
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('digitalizaciones.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por archivo, tipo, acta, año, institución o estado..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('digitalizaciones.index') }}"
                class="btn btn-secondary"
            >
                Limpiar
            </a>

        @endif

    </form>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Acta vinculada</th>
                    <th>OCR</th>
                    <th>Revisión</th>
                    <th>Tamaño</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($digitalizaciones as $digitalizacion)

                    @php
                        $tiposDocumento = [
                            'acta_historica' => 'Acta histórica',
                            'acta_subsanacion' => 'Acta de subsanación',
                            'certificado_historico' => 'Certificado histórico',
                            'otro_documento' => 'Otro documento histórico',
                        ];

                        $tipoDocumento =
                            $tiposDocumento[$digitalizacion->tipo_documento]
                            ?? $digitalizacion->tipo_documento;

                        $tamanioMB =
                            $digitalizacion->tamanio_bytes / 1024 / 1024;
                    @endphp

                    <tr>

                        <td>

                            <div class="main-text">
                                {{ $digitalizacion->nombre_original }}
                            </div>

                            <div class="secondary-text">
                                {{ strtoupper(
                                    pathinfo(
                                        $digitalizacion->nombre_original,
                                        PATHINFO_EXTENSION
                                    )
                                ) }}
                            </div>

                            @if ($digitalizacion->createdBy)

                                <div class="secondary-text">
                                    Registrado por:
                                    {{ $digitalizacion->createdBy->name }}
                                </div>

                            @endif

                        </td>

                        <td>
                            {{ $tipoDocumento }}
                        </td>

                        <td>

                            @if ($digitalizacion->acta)

                                <div class="main-text">
                                    {{ $digitalizacion->acta->numero_acta
                                        ?: 'Acta sin número' }}
                                </div>

                                <div class="secondary-text">
                                    Año:
                                    {{ $digitalizacion->acta->anio }}
                                </div>

                                @if ($digitalizacion->acta->institucionEducativa)

                                    <div class="secondary-text">
                                        {{ $digitalizacion
                                            ->acta
                                            ->institucionEducativa
                                            ->nombre }}
                                    </div>

                                @endif

                            @else

                                <span class="empty-text">
                                    Pendiente de vinculación
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($digitalizacion->estado_ocr === 'procesado')

                                <span class="badge badge-success">
                                    Procesado
                                </span>

                            @elseif ($digitalizacion->estado_ocr === 'procesando')

                                <span class="badge badge-info">
                                    Procesando
                                </span>

                            @elseif ($digitalizacion->estado_ocr === 'error')

                                <span class="badge badge-danger">
                                    Error
                                </span>

                            @else

                                <span class="badge badge-warning">
                                    Pendiente
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($digitalizacion->estado_revision === 'validado')

                                <span class="badge badge-success">
                                    Validado
                                </span>

                            @elseif ($digitalizacion->estado_revision === 'en_revision')

                                <span class="badge badge-info">
                                    En revisión
                                </span>

                            @elseif ($digitalizacion->estado_revision === 'rechazado')

                                <span class="badge badge-danger">
                                    Rechazado
                                </span>

                            @else

                                <span class="badge badge-warning">
                                    Pendiente
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ number_format($tamanioMB, 2) }} MB
                        </td>

                        <td>

                            @if ($digitalizacion->activo)

                                <span class="badge badge-success">
                                    Activo
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Inactivo
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="actions">

                                @if (
                                    auth()->user()
                                        ->hasPermission(
                                            'digitalizaciones.descargar'
                                        )
                                )

                                    <a
                                        href="{{ route(
                                            'digitalizaciones.descargar',
                                            $digitalizacion
                                        ) }}"
                                        class="btn btn-secondary"
                                    >
                                        Descargar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission(
                                            'digitalizaciones.cambiar_estado'
                                        )
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'digitalizaciones.cambiar-estado',
                                            $digitalizacion
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($digitalizacion->activo)

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                            >
                                                Desactivar
                                            </button>

                                        @else

                                            <button
                                                type="submit"
                                                class="btn btn-success"
                                            >
                                                Activar
                                            </button>

                                        @endif

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8">

                            @if ($buscar !== '')

                                No se encontraron documentos con ese criterio.

                            @else

                                No existen documentos digitalizados registrados.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($digitalizaciones->hasPages())

        <div class="pagination-container">
            {{ $digitalizaciones->links() }}
        </div>

    @endif

</div>

@endsection