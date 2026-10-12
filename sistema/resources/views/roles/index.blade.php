@extends('layouts.app')

@section('title', 'Roles | SIGLAC')

@section('content')

<div class="page-header">

    <div>
        <h2>Gestión de roles</h2>

        <p>
            Administre los roles y permisos disponibles en SIGLAC.
        </p>
    </div>

    @if (
        auth()->user()->hasPermission('roles.crear') &&
        auth()->user()->hasPermission('roles.asignar_permisos')
    )

        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            + Nuevo rol
        </a>

    @endif

</div>

<div class="card">

    <table>

        <thead>
            <tr>
                <th>Rol</th>
                <th>Descripción</th>
                <th>Usuarios</th>
                <th>Permisos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($roles as $rol)

                <tr>

                    <td>
                        <strong>{{ $rol->nombre }}</strong>
                    </td>

                    <td>
                        {{ $rol->descripcion ?? 'Sin descripción' }}
                    </td>

                    <td>
                        {{ $rol->users_count }}
                    </td>

                    <td>
                        {{ $rol->permissions_count }}
                    </td>

                    <td>

                        @if ($rol->activo)

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

                        @if ($rol->slug === 'administrador')

                            <span class="badge">
                                Rol protegido
                            </span>

                        @else

                            <div class="actions">

                                @if (
                                    auth()->user()->hasPermission('roles.editar') &&
                                    auth()->user()->hasPermission('roles.asignar_permisos')
                                )

                                    <a
                                        href="{{ route('roles.edit', $rol) }}"
                                        class="btn btn-secondary"
                                    >
                                        Editar
                                    </a>

                                @endif

                                @if (auth()->user()->hasPermission('roles.cambiar_estado'))

                                    <form
                                        method="POST"
                                        action="{{ route('roles.toggle-status', $rol) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if ($rol->activo)

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

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6">
                        No existen roles registrados.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection