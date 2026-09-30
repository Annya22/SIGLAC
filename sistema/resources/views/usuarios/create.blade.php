@extends('layouts.app')

@section('title', 'Nuevo usuario | SIGLAC')

@section('content')

<style>
    .form-card {
        max-width: 760px;
    }

    .form-group {
        margin-bottom: 20px;
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
        outline: none;
    }

    .form-control:focus {
        border-color: #17365d;
    }

    .required {
        color: #b42318;
    }

    .error-text {
        color: #b42318;
        font-size: 13px;
        margin-top: 6px;
    }

    .help-text {
        color: #667085;
        font-size: 12px;
        margin-top: 6px;
    }

    .roles-box {
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        padding: 15px;
    }

    .role-option {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .role-option:last-child {
        margin-bottom: 0;
    }

    .role-option input[type="radio"] {
        width: auto;
        margin: 0;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }
</style>

<div class="page-header">

    <div>
        <h2>Nuevo usuario</h2>

        <p>
            Registre un nuevo usuario autorizado para acceder a SIGLAC.
        </p>
    </div>

</div>

<div class="card form-card">

    <form method="POST" action="{{ route('usuarios.store') }}">

        @csrf

        <div class="form-group">

            <label for="name">
                Nombre completo
                <span class="required">*</span>
            </label>

            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                required
                autofocus
            >

            @error('name')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="email">
                Correo electrónico
                <span class="required">*</span>
            </label>

            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
            >

            @error('email')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="password">
                Contraseña
                <span class="required">*</span>
            </label>

            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                required
            >

            <div class="help-text">
                Mínimo 8 caracteres, incluyendo mayúscula, minúscula,
                número y símbolo.
            </div>

            @error('password')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="password_confirmation">
                Confirmar contraseña
                <span class="required">*</span>
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                required
            >

            <label
                style="display: flex; align-items: center; gap: 8px; margin-top: 10px; font-weight: normal;"
            >
                <input
                    type="checkbox"
                    id="mostrarPasswords"
                    style="width: auto;"
                >

                Mostrar contraseñas
            </label>

        </div>

        <div class="form-group">

            <label>
                Rol del usuario
                <span class="required">*</span>
            </label>

            <div class="roles-box">

                @forelse ($roles as $rol)

                    <label class="role-option">

                        <input
                            type="radio"
                            name="role_id"
                            value="{{ $rol->id }}"
                            {{ old('role_id') == $rol->id ? 'checked' : '' }}
                        >

                        <span>
                            <strong>{{ $rol->nombre }}</strong>

                            @if ($rol->descripcion)
                                — {{ $rol->descripcion }}
                            @endif
                        </span>

                    </label>

                @empty

                    <p>
                        No existen roles activos disponibles.
                    </p>

                @endforelse

            </div>

            @error('role_id')
                <div class="error-text">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-actions">

            <button type="submit" class="btn btn-primary">
                Guardar usuario
            </button>

            <a
                href="{{ route('usuarios.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

<script>
    const mostrarPasswords = document.getElementById('mostrarPasswords');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    mostrarPasswords.addEventListener('change', function () {
        const tipo = this.checked ? 'text' : 'password';

        password.type = tipo;
        passwordConfirmation.type = tipo;
    });
</script>

@endsection