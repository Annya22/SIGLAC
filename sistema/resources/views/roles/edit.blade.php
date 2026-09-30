@extends('layouts.app')

@section('title', 'Editar rol | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 850px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #344054;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 14px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 90px;
    }

    .required {
        color: #b42318;
    }

    .error-text {
        color: #b42318;
        font-size: 13px;
        margin-top: 6px;
    }

    .permissions-container {
        display: grid;
        gap: 18px;
    }

    .permission-module {
        border: 1px solid #e4e7ec;
        border-radius: 8px;
        padding: 18px;
    }

    .permission-module h3 {
        margin: 0 0 14px;
        color: #17365d;
        font-size: 16px;
    }

    .permission-option {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-bottom: 11px;
        font-size: 14px;
    }

    .permission-option:last-child {
        margin-bottom: 0;
    }

    .permission-description {
        color: #667085;
        font-size: 12px;
        margin-top: 2px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Editar rol</h2>

        <p>
            Modifique los datos y permisos asignados al rol.
        </p>
    </div>

</div>

<div class="card form-card">

    <form method="POST" action="{{ route('roles.update', $role) }}">

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="nombre">
                Nombre del rol
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                value="{{ old('nombre', $role->nombre) }}"
                required
            >

            @error('nombre')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="descripcion">
                Descripción
            </label>

            <textarea
                id="descripcion"
                name="descripcion"
                class="form-control"
            >{{ old('descripcion', $role->descripcion) }}</textarea>

            @error('descripcion')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label>
                Permisos del rol
            </label>

            <div class="permissions-container">

                @forelse ($permisos as $modulo => $listaPermisos)

                    <div class="permission-module">

                        <h3>{{ $modulo }}</h3>

                        @foreach ($listaPermisos as $permiso)

                            <label class="permission-option">

                                <input
                                    type="checkbox"
                                    name="permisos[]"
                                    value="{{ $permiso->id }}"
                                    {{
                                        in_array(
                                            $permiso->id,
                                            old(
                                                'permisos',
                                                $role->permissions->pluck('id')->toArray()
                                            )
                                        )
                                        ? 'checked'
                                        : ''
                                    }}
                                >

                                <span>

                                    <strong>
                                        {{ $permiso->nombre }}
                                    </strong>

                                    @if ($permiso->descripcion)
                                        <div class="permission-description">
                                            {{ $permiso->descripcion }}
                                        </div>
                                    @endif

                                </span>

                            </label>

                        @endforeach

                    </div>

                @empty

                    <p>
                        No existen permisos activos registrados.
                    </p>

                @endforelse

            </div>

            @error('permisos')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

            @error('permisos.*')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-actions">

            <button type="submit" class="btn btn-primary">
                Guardar cambios
            </button>

            <a
                href="{{ route('roles.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection