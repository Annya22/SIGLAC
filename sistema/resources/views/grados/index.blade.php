@extends('layouts.app')

@section('title', 'Grados | SIGLAC')

@section('content')

<style>
    .filters-bar {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .search-input,
    .filter-select {
        padding: 10px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
        background: #ffffff;
    }

    .search-input {
        width: 100%;
        max-width: 330px;
    }

    .filter-select {
        min-width: 200px;
    }

    .search-input:focus,
    .filter-select:focus {
        border-color: #17365d;
    }

    .level-badge {
        display: inline-block;
        padding: 4px 9px;
        border-radius: 12px;
        background: #f2f4f7;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
    }

    .order-badge {
        display: inline-block;
        min-width: 28px;
        padding: 4px 8px;
        border-radius: 12px;
        background: #f9fafb;
        border: 1px solid #eaecf0;
        color: #475467;
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
        <h2>Grados</h2>

        <p>
            Administre los grados correspondientes a cada nivel educativo.
        </p>
    </div>

    @if (auth()->user()->hasPermission('grados.crear'))

        <a
            href="{{ route('grados.create') }}"
            class="btn btn-primary"
        >
            + Nuevo grado
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('grados.index') }}"
        class="filters-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $busqueda }}"
            placeholder="Buscar grado..."
        >

        <select
            name="nivel_id"
            class="filter-select"
        >
            <option value="">
                Todos los niveles
            </option>

            @foreach ($niveles as $nivel)

                <option
                    value="{{ $nivel->id }}"
                    {{
                        (string) $nivelId === (string) $nivel->id
                            ? 'selected'
                            : ''
                    }}
                >
                    {{ $nivel->nombre }}
                </option>

            @endforeach

        </select>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Filtrar
        </button>

        @if ($busqueda !== '' || $nivelId)

            <a
                href="{{ route('grados.index') }}"
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
                    <th>Nivel educativo</th>
                    <th>Orden</th>
                    <th>Grado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($grados as $grado)

                    <tr>

                        <td>
                            <span class="level-badge">
                                {{ $grado->nivel->nombre }}
                            </span>
                        </td>

                        <td>
                            <span class="order-badge">
                                {{ $grado->orden }}
                            </span>
                        </td>

                        <td>
                            <strong>
                                {{ $grado->nombre }}
                            </strong>
                        </td>

                        <td>

                            @if ($grado->activo)

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
                                        ->hasPermission('grados.editar')
                                )

                                    <a
                                        href="{{ route('grados.edit', $grado) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('grados.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('grados.toggle-status', $grado) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($grado->activo)

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

                            @if ($busqueda !== '' || $nivelId)

                                No se encontraron grados con los filtros
                                seleccionados.

                            @else

                                No existen grados registrados.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($grados->hasPages())

        <div class="pagination-container">
            {{ $grados->links() }}
        </div>

    @endif

</div>

@endsection