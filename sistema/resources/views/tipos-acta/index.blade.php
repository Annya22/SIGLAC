@extends('layouts.app')

@section('title', 'Tipos de acta | SIGLAC')

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

    .description-text {
        color: #475467;
        font-size: 13px;
        line-height: 1.4;
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
        <h2>Tipos de acta</h2>

        <p>
            Consulte y administre los tipos de acta utilizados en la documentación histórica.
        </p>
    </div>

    @if (auth()->user()->hasPermission('tipos_acta.crear'))

        <a
            href="{{ route('tipos-acta.create') }}"
            class="btn btn-primary"
        >
            + Nuevo tipo de acta
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('tipos-acta.index') }}"
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
                href="{{ route('tipos-acta.index') }}"
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
                    <th>Tipo de acta</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($tiposActa as $tipoActa)

                    <tr>

                        <td>
                            <strong>
                                {{ $tipoActa->nombre }}
                            </strong>
                        </td>

                        <td>

                            @if ($tipoActa->descripcion)

                                <span class="description-text">
                                    {{ $tipoActa->descripcion }}
                                </span>

                            @else

                                <span class="empty-text">
                                    Sin descripción
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($tipoActa->activo)

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
                                        ->hasPermission('tipos_acta.editar')
                                )

                                    <a
                                        href="{{ route('tipos-acta.edit', $tipoActa) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('tipos_acta.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('tipos-acta.cambiar-estado', $tipoActa) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($tipoActa->activo)

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

                                No se encontraron tipos de acta para la búsqueda realizada.

                            @else

                                No existen tipos de acta registrados.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($tiposActa->hasPages())

        <div class="pagination-container">
            {{ $tiposActa->links() }}
        </div>

    @endif

</div>

@endsection