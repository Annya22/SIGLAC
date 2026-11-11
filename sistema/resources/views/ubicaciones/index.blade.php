@extends('layouts.app')

@section('title', 'Ubicaciones | SIGLAC')

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

    .location-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        background: #f2f4f7;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
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
        <h2>Ubicaciones físicas</h2>

        <p>
            Consulte y administre las ubicaciones físicas utilizadas para localizar la documentación histórica.
        </p>
    </div>

    @if (auth()->user()->hasPermission('ubicaciones.crear'))

        <a
            href="{{ route('ubicaciones.create') }}"
            class="btn btn-primary"
        >
            + Nueva ubicación
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('ubicaciones.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por ambiente, estante, caja o descripción..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('ubicaciones.index') }}"
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
                    <th>Ambiente</th>
                    <th>Estante</th>
                    <th>Caja</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($ubicaciones as $ubicacion)

                    <tr>

                        <td>
                            <strong>
                                {{ $ubicacion->ambiente }}
                            </strong>
                        </td>

                        <td>

                            @if ($ubicacion->estante)

                                <span class="location-badge">
                                    {{ $ubicacion->estante }}
                                </span>

                            @else

                                <span class="empty-text">
                                    Sin estante
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($ubicacion->caja)

                                <span class="location-badge">
                                    {{ $ubicacion->caja }}
                                </span>

                            @else

                                <span class="empty-text">
                                    Sin caja
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($ubicacion->descripcion)

                                <span class="description-text">
                                    {{ $ubicacion->descripcion }}
                                </span>

                            @else

                                <span class="empty-text">
                                    Sin descripción
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($ubicacion->activo)

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
                                        ->hasPermission('ubicaciones.editar')
                                )

                                    <a
                                        href="{{ route('ubicaciones.edit', $ubicacion) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('ubicaciones.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('ubicaciones.cambiar-estado', $ubicacion) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($ubicacion->activo)

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
                        <td colspan="6">

                            @if ($buscar !== '')

                                No se encontraron ubicaciones para la búsqueda realizada.

                            @else

                                No existen ubicaciones registradas.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($ubicaciones->hasPages())

        <div class="pagination-container">
            {{ $ubicaciones->links() }}
        </div>

    @endif

</div>

@endsection