@extends('layouts.app')

@section('title', 'Usuarios | SIGLAC')

@section('content')

<div class="page-header">

    <div>
        <h2>Gestión de usuarios</h2>

        <p>
            Administre los usuarios autorizados para acceder a SIGLAC.
        </p>
    </div>

@if (
    auth()->user()->hasPermission('usuarios.crear') &&
    auth()->user()->hasPermission('usuarios.asignar_roles')
)

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        + Nuevo usuario
    </a>

@endif

</div>

<div class="card">

    <form
        method="GET"
        action="{{ route('usuarios.index') }}"
        class="search-form"
    >

        <input
            type="text"
            name="buscar"
            value="{{ $busqueda }}"
            placeholder="Buscar por nombre o correo..."
        >

        <button type="submit" class="btn btn-primary">
            Buscar
        </button>

        @if ($busqueda !== '')
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                Limpiar
            </a>
        @endif

    </form>

    <table>

        <thead>

            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

            @forelse ($usuarios as $usuario)

                <tr>

                    <td>
                        {{ $usuario->name }}
                    </td>

                    <td>
                        {{ $usuario->email }}
                    </td>

                    <td>

                        @forelse ($usuario->roles as $rol)

                            <span class="badge badge-role">
                                {{ $rol->nombre }}
                            </span>

                        @empty

                            <span>Sin rol</span>

                        @endforelse

                    </td>

                    <td>

                        @if ($usuario->activo)

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
    auth()->user()->hasPermission('usuarios.editar') &&
    (
        !$usuario->roles->contains('slug', 'administrador') ||
        auth()->user()->hasRole('administrador')
    )
)

    <a
        href="{{ route('usuarios.edit', $usuario) }}"
        class="btn btn-secondary"
    >
        Editar
    </a>

@endif

                    @if (
    auth()->user()->hasPermission('usuarios.cambiar_estado') &&
    (
        !$usuario->roles->contains('slug', 'administrador') ||
        auth()->user()->hasRole('administrador')
    )
)

    <form
        method="POST"
        action="{{ route('usuarios.toggle-status', $usuario) }}"
    >

        @csrf
        @method('PATCH')

        @if ($usuario->activo)

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
                        No se encontraron usuarios.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    <div style="margin-top: 20px;">
        {{ $usuarios->links() }}
    </div>

</div>

@endsection