@extends('layouts.app')

@section('title', 'Subsanaciones | SIGLAC')

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

    .pagination-container {
        margin-top: 20px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Subsanaciones</h2>

        <p>
            Gestione los resultados de subsanación asociados a cursos desaprobados y actas de subsanación.
        </p>
    </div>

    @if (auth()->user()->hasPermission('subsanaciones.crear'))

        <a
            href="{{ route('subsanaciones.create') }}"
            class="btn btn-primary"
        >
            + Nueva subsanación
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('subsanaciones.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por estudiante, asignatura, acta, resultado o calificación..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('subsanaciones.index') }}"
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
                    <th>Estudiante</th>
                    <th>Curso desaprobado</th>
                    <th>Acta histórica</th>
                    <th>Acta de subsanación</th>
                    <th>Resultado</th>
                    <th>Calificación</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($subsanaciones as $subsanacion)

                    @php
                        $curso = $subsanacion->cursoDesaprobado;
                        $relacion = $curso->actaEstudiante;
                        $estudiante = $relacion->estudiante;
                        $actaOrigen = $relacion->acta;
                        $actaSubsanacion = $subsanacion->actaSubsanacion;
                    @endphp

                    <tr>

                        <td>

                            <div class="main-text">
                                {{ $estudiante->apellido_paterno }}
                                {{ $estudiante->apellido_materno }}
                                {{ $estudiante->nombres }}
                            </div>

                            @if ($estudiante->numero_documento)

                                <div class="secondary-text">
                                    {{ $estudiante->numero_documento }}
                                </div>

                            @endif

                        </td>

                        <td>
                            <div class="main-text">
                                {{ $curso->asignatura->nombre }}
                            </div>
                        </td>

                        <td>

                            <div class="main-text">
                                {{ $actaOrigen->numero_acta ?: 'Sin número' }}
                            </div>

                            <div class="secondary-text">
                                Año: {{ $actaOrigen->anio }}
                            </div>

                            <div class="secondary-text">
                                {{ $actaOrigen->institucionEducativa->nombre }}
                            </div>

                        </td>

                        <td>

                            <div class="main-text">
                                {{ $actaSubsanacion->numero_acta ?: 'Sin número' }}
                            </div>

                            <div class="secondary-text">
                                Año: {{ $actaSubsanacion->anio }}
                            </div>

                            @if ($actaSubsanacion->tipoActa)

                                <div class="secondary-text">
                                    {{ $actaSubsanacion->tipoActa->nombre }}
                                </div>

                            @endif

                        </td>

                        <td>

                            @if ($subsanacion->resultado === 'Aprobado')

                                <span class="badge badge-success">
                                    Aprobado
                                </span>

                            @elseif ($subsanacion->resultado === 'Desaprobado')

                                <span class="badge badge-danger">
                                    Desaprobado
                                </span>

                            @else

                                <span class="badge badge-role">
                                    Pendiente
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($subsanacion->calificacion)

                                {{ $subsanacion->calificacion }}

                            @else

                                <span class="empty-text">
                                    Sin calificación
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($subsanacion->fecha_subsanacion)

                                {{ $subsanacion->fecha_subsanacion->format('d/m/Y') }}

                            @else

                                <span class="empty-text">
                                    Sin fecha
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($subsanacion->activo)

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
                                        ->hasPermission('subsanaciones.editar')
                                )

                                    <a
                                        href="{{ route(
                                            'subsanaciones.edit',
                                            $subsanacion
                                        ) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('subsanaciones.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'subsanaciones.cambiar-estado',
                                            $subsanacion
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($subsanacion->activo)

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
                        <td colspan="9">

                            @if ($buscar !== '')

                                No se encontraron subsanaciones con ese criterio.

                            @else

                                No existen subsanaciones registradas.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($subsanaciones->hasPages())

        <div class="pagination-container">
            {{ $subsanaciones->links() }}
        </div>

    @endif

</div>

@endsection