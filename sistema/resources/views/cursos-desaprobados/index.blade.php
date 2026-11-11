@extends('layouts.app')

@section('title', 'Cursos desaprobados | SIGLAC')

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

    .empty-text {
        color: #667085;
        font-size: 13px;
    }

    .student-name {
        font-weight: 600;
        color: #101828;
    }

    .secondary-text {
        margin-top: 4px;
        color: #667085;
        font-size: 12px;
        line-height: 1.4;
    }

    .pagination-container {
        margin-top: 20px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Cursos desaprobados</h2>

        <p>
            Registre las asignaturas desaprobadas de los estudiantes asociados a actas históricas.
        </p>
    </div>

    @if (auth()->user()->hasPermission('cursos_desaprobados.crear'))

        <a
            href="{{ route('cursos-desaprobados.create') }}"
            class="btn btn-primary"
        >
            + Nuevo curso desaprobado
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('cursos-desaprobados.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por estudiante, documento, asignatura, acta, año o institución..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('cursos-desaprobados.index') }}"
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
                    <th>Acta histórica</th>
                    <th>Institución</th>
                    <th>Asignatura</th>
                    <th>Observación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($cursosDesaprobados as $curso)

                    <tr>

                        <td>

                            <div class="student-name">
                                {{ $curso->actaEstudiante->estudiante->apellido_paterno }}
                                {{ $curso->actaEstudiante->estudiante->apellido_materno }}
                                {{ $curso->actaEstudiante->estudiante->nombres }}
                            </div>

                            @if ($curso->actaEstudiante->estudiante->numero_documento)

                                <div class="secondary-text">
                                    @if ($curso->actaEstudiante->estudiante->tipo_documento)
                                        {{ $curso->actaEstudiante->estudiante->tipo_documento }}:
                                    @endif

                                    {{ $curso->actaEstudiante->estudiante->numero_documento }}
                                </div>

                            @endif

                        </td>

                        <td>

                            <strong>
                                {{ $curso->actaEstudiante->acta->numero_acta ?: 'Sin número' }}
                            </strong>

                            <div class="secondary-text">
                                Año: {{ $curso->actaEstudiante->acta->anio }}
                            </div>

                            <div class="secondary-text">
                                {{ $curso->actaEstudiante->acta->nivel->nombre }}

                                @if ($curso->actaEstudiante->acta->grado)
                                    /
                                    {{ $curso->actaEstudiante->acta->grado->nombre }}
                                @endif
                            </div>

                        </td>

                        <td>

                            {{ $curso->actaEstudiante->acta->institucionEducativa->nombre }}

                            @if ($curso->actaEstudiante->acta->institucionEducativa->codigo_modular)

                                <div class="secondary-text">
                                    Cód.
                                    {{ $curso->actaEstudiante->acta->institucionEducativa->codigo_modular }}
                                </div>

                            @endif

                        </td>

                        <td>
                            <strong>
                                {{ $curso->asignatura->nombre }}
                            </strong>
                        </td>

                        <td>

                            @if ($curso->observacion)

                                {{ $curso->observacion }}

                            @else

                                <span class="empty-text">
                                    Sin observación
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($curso->activo)

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
                                        ->hasPermission('cursos_desaprobados.editar')
                                )

                                    <a
                                        href="{{ route(
                                            'cursos-desaprobados.edit',
                                            $curso
                                        ) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('cursos_desaprobados.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'cursos-desaprobados.cambiar-estado',
                                            $curso
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($curso->activo)

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
                        <td colspan="7">

                            @if ($buscar !== '')

                                No se encontraron cursos desaprobados con ese criterio.

                            @else

                                No existen cursos desaprobados registrados.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($cursosDesaprobados->hasPages())

        <div class="pagination-container">
            {{ $cursosDesaprobados->links() }}
        </div>

    @endif

</div>

@endsection