@extends('layouts.app')

@section('title', 'Dashboard | SIGLAC')

@section('content')

<style>
    .welcome-card {
        background: white;
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
    }

    .welcome-card h2 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #17365d;
    }

    .welcome-card p {
        margin-bottom: 0;
        color: #667085;
        line-height: 1.5;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
    }

    .dashboard-card {
        display: block;
        background: white;
        border-radius: 10px;
        padding: 22px;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(0,0,0,.08);
    }

    .dashboard-card h3 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #17365d;
        font-size: 17px;
    }

    .dashboard-card p {
        margin: 0;
        color: #667085;
        font-size: 14px;
        line-height: 1.5;
    }

    .dashboard-card.disabled {
        cursor: default;
        opacity: .65;
    }

    .dashboard-card.disabled:hover {
        transform: none;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
    }

    .status-text {
        display: inline-block;
        margin-top: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #667085;
    }
</style>

<div class="welcome-card">

    <h2>
        Bienvenido(a), {{ auth()->user()->name }}
    </h2>

    <p>
        Has iniciado sesión correctamente en el Sistema Web de Gestión
        y Localización de Actas y Certificados Históricos.
    </p>

</div>

<div class="dashboard-grid">

    @if (auth()->user()->hasPermission('usuarios.ver'))

        <a
            href="{{ route('usuarios.index') }}"
            class="dashboard-card"
        >
            <h3>Usuarios</h3>

            <p>
                Administración de usuarios autorizados del sistema.
            </p>
        </a>

    @endif

    @if (
        auth()->user()->hasPermission('roles.ver') ||
        auth()->user()->hasPermission('permisos.ver')
    )

        @if (auth()->user()->hasPermission('roles.ver'))

            <a
                href="{{ route('roles.index') }}"
                class="dashboard-card"
            >
                <h3>Roles y permisos</h3>

                <p>
                    Configuración de roles y accesos del sistema.
                </p>
            </a>

        @elseif (auth()->user()->hasPermission('permisos.ver'))

            <a
                href="{{ route('permisos.index') }}"
                class="dashboard-card"
            >
                <h3>Permisos</h3>

                <p>
                    Consulta de los permisos disponibles en SIGLAC.
                </p>
            </a>

        @endif

    @endif

    <div class="dashboard-card disabled">

        <h3>Documentación histórica</h3>

        <p>
            Gestión de actas, ubicaciones y documentación académica.
        </p>

        <span class="status-text">
            Módulo pendiente de implementación
        </span>

    </div>

    <div class="dashboard-card disabled">

        <h3>Búsqueda y localización</h3>

        <p>
            Consulta de documentación mediante múltiples criterios.
        </p>

        <span class="status-text">
            Módulo pendiente de implementación
        </span>

    </div>

</div>

@endsection