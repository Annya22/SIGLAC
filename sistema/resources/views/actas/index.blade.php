@extends('layouts.app')

@section('title', 'Actas históricas | SIGLAC')

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

    .location-text {
        font-size: 13px;
        line-height: 1.45;
    }

    .pagination-container {
        margin-top: 20px;
    }

    .year-badge {
        display: inline-block;
        padding: 4px 9px;
        border-radius: 12px;
        background: #eef4ff;
        color: #3538cd;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<div class="page-header">

    <div>
        <h2>Actas históricas</h2>

        <p>
            Registre y consulte los datos necesarios para identificar y localizar las actas históricas.
        </p>
    </div>

    @if (auth()->user()->hasPermission('actas.crear'))

        <a
            href="{{ route('actas.create') }}"
            class="btn btn-primary"
        >
            + Nueva acta
        </a>

    @endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('actas.index') }}"
        class="search-bar"
    >

        <input
            type="text"
            name="buscar"
            class="search-input"
            value="{{ $buscar }}"
            placeholder="Buscar por acta, año, institución, nivel, grado o ubicación..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Buscar
        </button>

        @if ($buscar !== '')

            <a
                href="{{ route('actas.index') }}"
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
                    <th>Acta</th>
                    <th>Año</th>
                    <th>Institución educativa</th>
                    <th>Nivel / grado</th>
                    <th>Tipo</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($actas as $acta)

                    <tr>

                        <td>
                            @if ($acta->numero_acta)
                                <strong>{{ $acta->numero_acta }}</strong>
                            @else
                                <span class="empty-text">
                                    Sin número
                                </span>
                            @endif

                            @if ($acta->seccion)
                                <div class="empty-text">
                                    Sección: {{ $acta->seccion }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <span class="year-badge">
                                {{ $acta->anio }}
                            </span>
                        </td>

                        <td>
                            <strong>
                                {{ $acta->institucionEducativa->nombre }}
                            </strong>

                            @if ($acta->institucionEducativa->codigo_modular)
                                <div class="empty-text">
                                    Cód. {{ $acta->institucionEducativa->codigo_modular }}
                                </div>
                            @endif
                        </td>

                        <td>
                            {{ $acta->nivel->nombre }}

                            @if ($acta->grado)
                                <div class="empty-text">
                                    {{ $acta->grado->nombre }}
                                </div>
                            @else
                                <div class="empty-text">
                                    Grado no especificado
                                </div>
                            @endif
                        </td>

                        <td>
                            {{ $acta->tipoActa->nombre }}
                        </td>

                        <td>

                            @if ($acta->ubicacion)

                                <div class="location-text">
                                    <strong>
                                        {{ $acta->ubicacion->ambiente }}
                                    </strong>

                                    @if ($acta->ubicacion->estante)
                                        <div>
                                            Estante: {{ $acta->ubicacion->estante }}
                                        </div>
                                    @endif

                                    @if ($acta->ubicacion->caja)
                                        <div>
                                            Caja: {{ $acta->ubicacion->caja }}
                                        </div>
                                    @endif
                                </div>

                            @else

                                <span class="empty-text">
                                    Sin ubicación asignada
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($acta->activo)

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
                                        ->hasPermission('actas.asignar_estudiantes')
                                )

                                    <a
                                        href="{{ route(
                                            'actas.estudiantes.index',
                                            $acta
                                        ) }}"
                                        class="btn btn-primary"
                                    >
                                        Estudiantes
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('actas.editar')
                                )

                                    <a
                                        href="{{ route('actas.edit', $acta) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (
                                    auth()->user()
                                        ->hasPermission('actas.cambiar_estado')
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('actas.cambiar-estado', $acta) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($acta->activo)

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
                        <td colspan="8">

                            @if ($buscar !== '')

                                No se encontraron actas para la búsqueda realizada.

                            @else

                                No existen actas históricas registradas.

                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($actas->hasPages())

        <div class="pagination-container">
            {{ $actas->links() }}
        </div>

    @endif

</div>

@endsection