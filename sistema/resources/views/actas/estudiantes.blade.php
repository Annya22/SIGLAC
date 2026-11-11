@extends('layouts.app')

@section('title', 'Estudiantes del acta | SIGLAC')

@section('content')

<style>
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .summary-item {
        padding: 14px;
        border: 1px solid #eaecf0;
        border-radius: 8px;
        background: #f9fafb;
    }

    .summary-label {
        display: block;
        margin-bottom: 5px;
        color: #667085;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .summary-value {
        color: #101828;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
    }

    .section-title {
        margin: 0 0 16px;
        color: #17365d;
        font-size: 18px;
    }

    .section-description {
        margin-top: -8px;
        margin-bottom: 18px;
        color: #667085;
        font-size: 13px;
    }

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

    .empty-text {
        color: #667085;
        font-size: 13px;
    }

    .document-text {
        color: #475467;
        font-size: 13px;
    }

    .status-note {
        display: inline-block;
        margin-top: 4px;
        color: #667085;
        font-size: 12px;
    }

    .pagination-container {
        margin-top: 20px;
    }

    .card + .card {
        margin-top: 24px;
    }

    .warning-box {
        padding: 12px 14px;
        margin-bottom: 20px;
        border: 1px solid #fecdca;
        border-radius: 7px;
        background: #fef3f2;
        color: #b42318;
        font-size: 13px;
        line-height: 1.5;
    }

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">

    <div>
        <h2>Estudiantes asociados al acta</h2>

        <p>
            Administre los estudiantes que figuran en esta acta histórica.
        </p>
    </div>

    <a
        href="{{ route('actas.index') }}"
        class="btn btn-secondary"
    >
        Volver a actas
    </a>

</div>

<div class="card">

    <h3 class="section-title">
        Datos del acta
    </h3>

    <div class="summary-grid">

        <div class="summary-item">

            <span class="summary-label">
                Acta
            </span>

            <span class="summary-value">
                {{ $acta->numero_acta ?: 'Sin número' }}
            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Año
            </span>

            <span class="summary-value">
                {{ $acta->anio }}
            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Tipo
            </span>

            <span class="summary-value">
                {{ $acta->tipoActa->nombre }}
            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Estado
            </span>

            <span class="summary-value">

                @if ($acta->activo)

                    <span class="badge badge-success">
                        Activo
                    </span>

                @else

                    <span class="badge badge-danger">
                        Inactivo
                    </span>

                @endif

            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Institución educativa
            </span>

            <span class="summary-value">
                {{ $acta->institucionEducativa->nombre }}
            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Nivel
            </span>

            <span class="summary-value">
                {{ $acta->nivel->nombre }}
            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Grado
            </span>

            <span class="summary-value">
                {{ $acta->grado?->nombre ?? 'No especificado' }}
            </span>

        </div>

        <div class="summary-item">

            <span class="summary-label">
                Sección
            </span>

            <span class="summary-value">
                {{ $acta->seccion ?: 'No especificada' }}
            </span>

        </div>

    </div>

    @if (! $acta->activo)

        <div class="warning-box">
            Esta acta se encuentra inactiva. Puede consultar sus relaciones históricas,
            pero no podrá asociar nuevos estudiantes ni reactivar relaciones mientras
            el acta permanezca inactiva.
        </div>

    @endif

</div>

<div class="card">

    <h3 class="section-title">
        Estudiantes actualmente relacionados
    </h3>

    <p class="section-description">
        Las relaciones desactivadas se mantienen registradas para conservar la trazabilidad.
    </p>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Documento</th>
                    <th>Estado de la relación</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($relaciones as $relacion)

                    <tr>

                        <td>

                            <strong>
                                {{ $relacion->estudiante->apellido_paterno }}
                                {{ $relacion->estudiante->apellido_materno }}
                                {{ $relacion->estudiante->nombres }}
                            </strong>

                            @if (! $relacion->estudiante->activo)
                                <div class="status-note">
                                    El estudiante se encuentra inactivo en el catálogo.
                                </div>
                            @endif

                        </td>

                        <td>

                            @if ($relacion->estudiante->numero_documento)

                                <div class="document-text">

                                    @if ($relacion->estudiante->tipo_documento)
                                        {{ $relacion->estudiante->tipo_documento }}:
                                    @endif

                                    {{ $relacion->estudiante->numero_documento }}

                                </div>

                            @else

                                <span class="empty-text">
                                    Sin documento registrado
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($relacion->activo)

                                <span class="badge badge-success">
                                    Asociado
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Retirado
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($relacion->activo)

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'actas.estudiantes.cambiar-estado',
                                        [$acta, $relacion]
                                    ) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Retirar
                                    </button>

                                </form>

                            @elseif (
                                $acta->activo &&
                                $relacion->estudiante->activo
                            )

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'actas.estudiantes.cambiar-estado',
                                        [$acta, $relacion]
                                    ) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                    >
                                        Reactivar
                                    </button>

                                </form>

                            @else

                                <span class="empty-text">
                                    Sin acción disponible
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">
                            Esta acta todavía no tiene estudiantes asociados.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="card">

    <h3 class="section-title">
        Buscar estudiante
    </h3>

    <p class="section-description">
        Busque un estudiante previamente registrado en SIGLAC y asócielo al acta.
    </p>

    <form
        method="GET"
        action="{{ route('actas.estudiantes.index', $acta) }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por nombres, apellidos o número de documento..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('actas.estudiantes.index', $acta) }}"
                class="btn btn-secondary"
            >
                Limpiar
            </a>

        @endif

    </form>

    @error('estudiante_id')

        <div class="alert alert-error">
            {{ $message }}
        </div>

    @enderror

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Documento</th>
                    <th>Situación en esta acta</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($estudiantes as $estudiante)

                    @php
                        $relacionExistente = $relaciones
                            ->firstWhere(
                                'estudiante_id',
                                $estudiante->id
                            );
                    @endphp

                    <tr>

                        <td>
                            <strong>
                                {{ $estudiante->apellido_paterno }}
                                {{ $estudiante->apellido_materno }}
                                {{ $estudiante->nombres }}
                            </strong>
                        </td>

                        <td>

                            @if ($estudiante->numero_documento)

                                <div class="document-text">

                                    @if ($estudiante->tipo_documento)
                                        {{ $estudiante->tipo_documento }}:
                                    @endif

                                    {{ $estudiante->numero_documento }}

                                </div>

                            @else

                                <span class="empty-text">
                                    Sin documento registrado
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($relacionExistente?->activo)

                                <span class="badge badge-success">
                                    Ya asociado
                                </span>

                            @elseif ($relacionExistente)

                                <span class="badge badge-danger">
                                    Relación retirada
                                </span>

                            @else

                                <span class="empty-text">
                                    No asociado
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($relacionExistente?->activo)

                                <span class="empty-text">
                                    Ya pertenece al acta
                                </span>

                            @elseif ($relacionExistente)

                                <span class="empty-text">
                                    Use “Reactivar” en la tabla superior
                                </span>

                            @elseif ($acta->activo)

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'actas.estudiantes.store',
                                        $acta
                                    ) }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="estudiante_id"
                                        value="{{ $estudiante->id }}"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Asociar
                                    </button>

                                </form>

                            @else

                                <span class="empty-text">
                                    Acta inactiva
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">

                            @if ($buscar !== '')

                                No se encontraron estudiantes con ese criterio.

                            @else

                                No existen estudiantes activos disponibles para mostrar.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($estudiantes->hasPages())

        <div class="pagination-container">
            {{ $estudiantes->links() }}
        </div>

    @endif

</div>

@endsection