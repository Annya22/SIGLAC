@extends('layouts.app')

@section('title', 'Búsqueda y localización | SIGLAC')

@section('content')

<style>
    .search-card {
        margin-bottom: 22px;
    }

    .search-main {
        margin-bottom: 18px;
    }

    .search-main label,
    .filter-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #344054;
    }

    .search-input,
    .filter-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        background: #ffffff;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
    }

    .search-input:focus,
    .filter-control:focus {
        border-color: #17365d;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .search-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .search-help {
        margin-top: 7px;
        color: #667085;
        font-size: 12px;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .results-header h3 {
        margin: 0;
        color: #101828;
    }

    .results-count {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 14px;
        background: #f2f4f7;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
    }

    .result-card {
        border: 1px solid #eaecf0;
        border-radius: 9px;
        margin-bottom: 16px;
        overflow: hidden;
        background: #ffffff;
    }

    .result-top {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-bottom: 1px solid #eaecf0;
        background: #f9fafb;
    }

    .result-title {
        margin: 0 0 5px 0;
        color: #101828;
        font-size: 16px;
    }

    .result-subtitle {
        margin: 0;
        color: #667085;
        font-size: 13px;
    }

    .acta-year {
        min-width: 65px;
        text-align: center;
        align-self: flex-start;
        padding: 6px 10px;
        border-radius: 7px;
        background: #17365d;
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
    }

    .result-content {
        padding: 18px;
    }

    .result-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .section-title {
        margin: 0 0 12px 0;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .data-list {
        display: grid;
        gap: 9px;
    }

    .data-row {
        display: grid;
        grid-template-columns: 130px 1fr;
        gap: 10px;
        font-size: 13px;
    }

    .data-label {
        color: #667085;
        font-weight: 600;
    }

    .data-value {
        color: #101828;
        word-break: break-word;
    }

    .location-box {
        border: 1px solid #b2ddff;
        background: #eff8ff;
        border-radius: 8px;
        padding: 14px;
    }

    .location-box.no-location {
        border-color: #fedf89;
        background: #fffaeb;
    }

    .location-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 12px;
        color: #175cd3;
        font-size: 14px;
        font-weight: 700;
    }

    .no-location .location-title {
        color: #b54708;
    }

    .students-section {
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid #eaecf0;
    }

    .student-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .student-tag {
        display: inline-block;
        padding: 6px 9px;
        border-radius: 14px;
        background: #f2f4f7;
        color: #344054;
        font-size: 12px;
    }

    .empty-state {
        padding: 38px 20px;
        text-align: center;
        border: 1px dashed #d0d5dd;
        border-radius: 8px;
        color: #667085;
        background: #f9fafb;
    }

    .empty-state strong {
        display: block;
        margin-bottom: 6px;
        color: #344054;
    }

    .pagination-container {
        margin-top: 20px;
    }

    @media (max-width: 1000px) {
        .filters-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .result-grid {
            grid-template-columns: 1fr;
        }

        .result-top {
            flex-direction: column;
        }

        .acta-year {
            align-self: flex-start;
        }
    }

    @media (max-width: 600px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }

        .data-row {
            grid-template-columns: 1fr;
            gap: 3px;
        }
    }
</style>

<div class="page-header">

    <div>
        <h2>Búsqueda y localización</h2>

        <p>
            Busque actas históricas por estudiante, institución,
            número de acta, año, nivel o grado y consulte su
            ubicación física.
        </p>
    </div>

</div>

<div class="card search-card">

    <form
        method="GET"
        action="{{ route('busqueda.index') }}"
    >

        <div class="search-main">

            <label for="buscar">
                Búsqueda general
            </label>

            <input
                type="text"
                id="buscar"
                name="buscar"
                class="search-input"
                value="{{ $buscar }}"
                placeholder="Estudiante, DNI, número de acta o institución..."
                autocomplete="off"
            >

            <div class="search-help">
                Puede escribir nombres, apellidos, número de documento,
                número de acta o nombre de la institución educativa.
            </div>

        </div>

        <div class="filters-grid">

            <div class="filter-group">

                <label for="institucion_id">
                    Institución educativa
                </label>

                <select
                    id="institucion_id"
                    name="institucion_id"
                    class="filter-control"
                >
                    <option value="">
                        Todas las instituciones
                    </option>

                    @foreach ($instituciones as $institucion)

                        <option
                            value="{{ $institucion->id }}"
                            @selected((string) $institucionId === (string) $institucion->id)
                        >
                            {{ $institucion->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="filter-group">

                <label for="nivel_id">
                    Nivel
                </label>

                <select
                    id="nivel_id"
                    name="nivel_id"
                    class="filter-control"
                >
                    <option value="">
                        Todos los niveles
                    </option>

                    @foreach ($niveles as $nivel)

                        <option
                            value="{{ $nivel->id }}"
                            @selected((string) $nivelId === (string) $nivel->id)
                        >
                            {{ $nivel->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="filter-group">

                <label for="grado_id">
                    Grado
                </label>

                <select
                    id="grado_id"
                    name="grado_id"
                    class="filter-control"
                >
                    <option value="">
                        Todos los grados
                    </option>

                    @foreach ($grados as $grado)

                        <option
                            value="{{ $grado->id }}"
                            @selected((string) $gradoId === (string) $grado->id)
                        >
                            {{ $grado->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="filter-group">

                <label for="anio">
                    Año
                </label>

                <input
                    type="number"
                    id="anio"
                    name="anio"
                    class="filter-control"
                    value="{{ $anio }}"
                    min="1900"
                    max="{{ now()->year }}"
                    placeholder="Ejemplo: 1990"
                >

            </div>

        </div>

        <div class="search-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Buscar documentos
            </button>

            @if (
                $buscar !== '' ||
                ! empty($institucionId) ||
                ! empty($nivelId) ||
                ! empty($gradoId) ||
                $anio !== ''
            )

                <a
                    href="{{ route('busqueda.index') }}"
                    class="btn btn-secondary"
                >
                    Limpiar filtros
                </a>

            @endif

        </div>

    </form>

</div>

<div class="card">

    <div class="results-header">

        <h3>
            Resultados
        </h3>

        <span class="results-count">
            {{ $actas->total() }}
            {{ $actas->total() === 1 ? 'documento encontrado' : 'documentos encontrados' }}
        </span>

    </div>

    @forelse ($actas as $acta)

        @php
            $ubicacion = $acta->ubicacion;

            $estudiantesActivos = $acta->estudiantes
                ->filter(function ($estudiante) {
                    return (bool) ($estudiante->pivot->activo ?? true);
                });

            $codigoUbicacion = $ubicacion
                ? ($ubicacion->codigo
                    ?? $ubicacion->codigo_ubicacion
                    ?? null)
                : null;
        @endphp

        <div class="result-card">

            <div class="result-top">

                <div>

                    <h4 class="result-title">
                        {{ $acta->numero_acta ?: 'Acta sin número registrado' }}
                    </h4>

                    <p class="result-subtitle">

                        {{ $acta->tipoActa?->nombre ?? 'Tipo de acta no registrado' }}

                        ·

                        {{ $acta->institucionEducativa?->nombre ?? 'Institución no registrada' }}

                    </p>

                </div>

                <div class="acta-year">
                    {{ $acta->anio ?: 'S/A' }}
                </div>

            </div>

            <div class="result-content">

                <div class="result-grid">

                    {{-- INFORMACIÓN DEL DOCUMENTO --}}
                    <div>

                        <h5 class="section-title">
                            Información del acta
                        </h5>

                        <div class="data-list">

                            <div class="data-row">
                                <div class="data-label">
                                    Número:
                                </div>

                                <div class="data-value">
                                    {{ $acta->numero_acta ?: 'No registrado' }}
                                </div>
                            </div>

                            <div class="data-row">
                                <div class="data-label">
                                    Año:
                                </div>

                                <div class="data-value">
                                    {{ $acta->anio ?: 'No registrado' }}
                                </div>
                            </div>

                            <div class="data-row">
                                <div class="data-label">
                                    Institución:
                                </div>

                                <div class="data-value">
                                    {{ $acta->institucionEducativa?->nombre ?? 'No registrada' }}
                                </div>
                            </div>

                            <div class="data-row">
                                <div class="data-label">
                                    Nivel:
                                </div>

                                <div class="data-value">
                                    {{ $acta->nivel?->nombre ?? 'No registrado' }}
                                </div>
                            </div>

                            <div class="data-row">
                                <div class="data-label">
                                    Grado:
                                </div>

                                <div class="data-value">
                                    {{ $acta->grado?->nombre ?? 'No registrado' }}
                                </div>
                            </div>

                            <div class="data-row">
                                <div class="data-label">
                                    Sección:
                                </div>

                                <div class="data-value">
                                    {{ $acta->seccion ?: 'No registrada' }}
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- UBICACIÓN FÍSICA --}}
                    <div>

                        @if ($ubicacion)

                            <div class="location-box">

                                <div class="location-title">
                                    Ubicación física localizada
                                </div>

                                <div class="data-list">

                                    <div class="data-row">
                                        <div class="data-label">
                                            Código:
                                        </div>

                                        <div class="data-value">
                                            {{ $codigoUbicacion ?: 'No registrado' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Sede:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->sede ?: 'No registrada' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Ambiente:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->ambiente ?: 'No registrado' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Archivo:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->archivo ?: 'No registrado' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Estante:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->estante ?: 'No registrado' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Caja:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->caja ?: 'No registrada' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Libro / tomo:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->libro_tomo ?: 'No registrado' }}
                                        </div>
                                    </div>

                                    <div class="data-row">
                                        <div class="data-label">
                                            Referencia:
                                        </div>

                                        <div class="data-value">
                                            {{ $ubicacion->referencia ?: 'No registrada' }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="location-box no-location">

                                <div class="location-title">
                                    Ubicación física pendiente
                                </div>

                                <div class="data-value">
                                    Esta acta todavía no tiene una ubicación
                                    física confirmada en SIGLAC.
                                </div>

                            </div>

                        @endif

                    </div>

                </div>

                {{-- ESTUDIANTES --}}
                <div class="students-section">

                    <h5 class="section-title">
                        Estudiantes asociados
                    </h5>

                    @if ($estudiantesActivos->isNotEmpty())

                        <div class="student-tags">

                            @foreach ($estudiantesActivos as $estudiante)

                                <span class="student-tag">

                                    {{ $estudiante->apellido_paterno }}

                                    @if ($estudiante->apellido_materno)
                                        {{ $estudiante->apellido_materno }}
                                    @endif

                                    {{ $estudiante->nombres }}

                                </span>

                            @endforeach

                        </div>

                    @else

                        <span class="data-value">
                            No hay estudiantes activos asociados a esta acta.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    @empty

        <div class="empty-state">

            <strong>
                No se encontraron documentos.
            </strong>

            Revise los criterios ingresados e intente nuevamente.

        </div>

    @endforelse

    @if ($actas->hasPages())

        <div class="pagination-container">
            {{ $actas->links() }}
        </div>

    @endif

</div>

@endsection