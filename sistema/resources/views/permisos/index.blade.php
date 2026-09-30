@extends('layouts.app')

@section('title', 'Permisos | SIGLAC')

@section('content')

<div class="page-header">

    <div>
        <h2>Permisos del sistema</h2>

        <p>
            Consulte las acciones disponibles que pueden asignarse a los roles de SIGLAC.
        </p>
    </div>

</div>

@forelse ($permisos as $modulo => $listaPermisos)

    <div class="card" style="margin-bottom: 20px;">

        <h3 style="margin-top: 0; color: #17365d;">
            {{ $modulo }}
        </h3>

        <table>

            <thead>
                <tr>
                    <th>Permiso</th>
                    <th>Código interno</th>
                    <th>Descripción</th>
                    <th>Roles asignados</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($listaPermisos as $permiso)

                    <tr>

                        <td>
                            <strong>{{ $permiso->nombre }}</strong>
                        </td>

                        <td>
                            <code>{{ $permiso->slug }}</code>
                        </td>

                        <td>
                            {{ $permiso->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td>
                            {{ $permiso->roles_count }}
                        </td>

                        <td>

                            @if ($permiso->activo)

                                <span class="badge badge-success">
                                    Activo
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Inactivo
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

@empty

    <div class="card">
        No existen permisos registrados.
    </div>

@endforelse

@endsection