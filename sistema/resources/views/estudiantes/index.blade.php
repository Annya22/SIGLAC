@extends('layouts.app')

@section('title', 'Estudiantes | SIGLAC')

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
        max-width: 450px;
        padding: 10px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #17365d;
    }

    .document-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        background: #f2f4f7;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-text {
        color: #667085;
        font-size: 13px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .pagination-container {
        margin-top: 20px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Estudiantes</h2>

        <p>
            Consulte y administre los estudiantes registrados en SIGLAC.
        </p>
    </div>

    @if (auth()->user()->hasPermission('estudiantes.crear'))

        <a
            href="{{ route('estudiantes.create') }}"
            class="btn btn-primary"
        >
            + Nuevo estudiante
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('estudiantes.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $busqueda }}"
            placeholder="Buscar por nombres, apellidos o documento..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($busqueda !== '')

            <a
                href="{{ route('estudiantes.index') }}"
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
                    <th>Tipo documento</th>
                    <th>Número documento</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($estudiantes as $estudiante)

                    <tr>

                        <td>
                            <strong>
                                {{ $estudiante->apellido_paterno }}

                                @if ($estudiante->apellido_materno)
                                    {{ $estudiante->apellido_materno }}
                                @endif

                                {{ $estudiante->nombres }}
                            </strong>
                        </td>

                        <td>

                            @if ($estudiante->tipo_documento)

                                <span class="document-badge">
                                    {{ $estudiante->tipo_documento }}
                                </span>

                            @else

                                <span class="empty-text">
                                    Sin documento
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($estudiante->numero_documento)

                                {{ $estudiante->numero_documento }}

                            @else

                                <span class="empty-text">
                                    —
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($estudiante->activo)

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
                                        ->hasPermission('estudiantes.editar')
                                )

                                    <a
                                        href="{{ route('estudiantes.edit', $estudiante) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('estudiantes.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('estudiantes.toggle-status', $estudiante) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($estudiante->activo)

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
                        <td colspan="5">

                            @if ($busqueda !== '')

                                No se encontraron estudiantes para la
                                búsqueda realizada.

                            @else

                                No existen estudiantes registrados.

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