@extends('layouts.app')

@section('title', 'Asignaturas | SIGLAC')

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
        max-width: 500px;
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

    .pagination-container {
        margin-top: 20px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Asignaturas</h2>

        <p>
            Administre las asignaturas que podrán relacionarse con cursos desaprobados y procesos de subsanación.
        </p>
    </div>

    @if (auth()->user()->hasPermission('asignaturas.crear'))

        <a
            href="{{ route('asignaturas.create') }}"
            class="btn btn-primary"
        >
            + Nueva asignatura
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('asignaturas.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por nombre o descripción..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('asignaturas.index') }}"
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
                    <th>Asignatura</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($asignaturas as $asignatura)

                    <tr>

                        <td>
                            <strong>
                                {{ $asignatura->nombre }}
                            </strong>
                        </td>

                        <td>
                            @if ($asignatura->descripcion)

                                {{ $asignatura->descripcion }}

                            @else

                                <span class="empty-text">
                                    Sin descripción
                                </span>

                            @endif
                        </td>

                        <td>

                            @if ($asignatura->activo)

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
                                        ->hasPermission('asignaturas.editar')
                                )

                                    <a
                                        href="{{ route('asignaturas.edit', $asignatura) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('asignaturas.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'asignaturas.cambiar-estado',
                                            $asignatura
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($asignatura->activo)

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
                        <td colspan="4">

                            @if ($buscar !== '')

                                No se encontraron asignaturas con ese criterio.

                            @else

                                No existen asignaturas registradas.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($asignaturas->hasPages())

        <div class="pagination-container">
            {{ $asignaturas->links() }}
        </div>

    @endif

</div>

@endsection