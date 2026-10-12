@extends('layouts.app')

@section('title', 'Niveles educativos | SIGLAC')

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

    .count-badge {
        display: inline-block;
        min-width: 28px;
        padding: 4px 8px;
        border-radius: 12px;
        background: #f2f4f7;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
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
        <h2>Niveles educativos</h2>

        <p>
            Administre los niveles educativos utilizados en SIGLAC.
        </p>
    </div>

    @if (auth()->user()->hasPermission('niveles.crear'))

        <a
            href="{{ route('niveles.create') }}"
            class="btn btn-primary"
        >
            + Nuevo nivel
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('niveles.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $busqueda }}"
            placeholder="Buscar nivel educativo..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($busqueda !== '')

            <a
                href="{{ route('niveles.index') }}"
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
                    <th>Orden</th>
                    <th>Nivel educativo</th>
                    <th>Grados</th>
                    <th>Instituciones</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($niveles as $nivel)

                    <tr>

                        <td>
                            {{ $nivel->orden }}
                        </td>

                        <td>
                            <strong>
                                {{ $nivel->nombre }}
                            </strong>
                        </td>

                        <td>
                            <span class="count-badge">
                                {{ $nivel->grados_count }}
                            </span>
                        </td>

                        <td>
                            <span class="count-badge">
                                {{ $nivel->instituciones_educativas_count }}
                            </span>
                        </td>

                        <td>

                            @if ($nivel->activo)

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
                                        ->hasPermission('niveles.editar')
                                )

                                    <a
                                        href="{{ route('niveles.edit', $nivel) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('niveles.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('niveles.toggle-status', $nivel) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($nivel->activo)

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

                            @if ($busqueda !== '')

                                No se encontraron niveles educativos
                                para la búsqueda realizada.

                            @else

                                No existen niveles educativos registrados.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($niveles->hasPages())

        <div class="pagination-container">
            {{ $niveles->links() }}
        </div>

    @endif

</div>

@endsection