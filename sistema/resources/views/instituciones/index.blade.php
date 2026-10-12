@extends('layouts.app')

@section('title', 'Instituciones educativas | SIGLAC')

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
        max-width: 420px;
        padding: 10px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #17365d;
    }

    .levels-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .level-badge {
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
        <h2>Instituciones educativas</h2>

        <p>
            Consulte y administre las instituciones educativas registradas en SIGLAC.
        </p>
    </div>

    @if (auth()->user()->hasPermission('instituciones.crear'))

        <a
            href="{{ route('instituciones.create') }}"
            class="btn btn-primary"
        >
            + Nueva institución
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('instituciones.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $busqueda }}"
            placeholder="Buscar por nombre o código modular..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($busqueda !== '')

            <a
                href="{{ route('instituciones.index') }}"
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
                    <th>Código modular</th>
                    <th>Institución educativa</th>
                    <th>Niveles</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($instituciones as $institucion)

                    <tr>

                        <td>
                            {{ $institucion->codigo_modular ?? '—' }}
                        </td>

                        <td>
                            <strong>
                                {{ $institucion->nombre }}
                            </strong>
                        </td>

                        <td>

                            @if ($institucion->niveles->isNotEmpty())

                                <div class="levels-list">

                                    @foreach ($institucion->niveles as $nivel)

                                        <span class="level-badge">
                                            {{ $nivel->nombre }}
                                        </span>

                                    @endforeach

                                </div>

                            @else

                                <span class="empty-text">
                                    Sin niveles asignados
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($institucion->activo)

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
                                        ->hasPermission('instituciones.editar')
                                )

                                    <a
                                        href="{{ route('instituciones.edit', $institucion) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('instituciones.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('instituciones.toggle-status', $institucion) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($institucion->activo)

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

                                No se encontraron instituciones educativas
                                para la búsqueda realizada.

                            @else

                                No existen instituciones educativas registradas.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($instituciones->hasPages())

        <div class="pagination-container">
            {{ $instituciones->links() }}
        </div>

    @endif

</div>

@endsection