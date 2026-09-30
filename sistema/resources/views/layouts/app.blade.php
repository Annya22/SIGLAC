<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SIGLAC')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f6fa;
            color: #1f2937;
        }

        .topbar {
            height: 70px;
            background: #17365d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .brand h1 {
            margin: 0;
            font-size: 22px;
        }

        .brand span {
            font-size: 12px;
            opacity: 0.8;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-info {
            text-align: right;
            font-size: 13px;
        }

        .user-info strong {
            display: block;
        }

        .logout {
            background: transparent;
            border: 1px solid rgba(255,255,255,.6);
            color: white;
            padding: 8px 13px;
            border-radius: 6px;
            cursor: pointer;
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        .sidebar {
            width: 240px;
            background: #ffffff;
            padding: 25px 15px;
            border-right: 1px solid #e5e7eb;
        }

        .sidebar-title {
            font-size: 12px;
            color: #9ca3af;
            margin: 0 12px 12px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 7px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #edf2f7;
            color: #17365d;
        }

        .sidebar a.active {
            background: #17365d;
            color: white;
        }

        .content {
            flex: 1;
            padding: 32px;
            overflow-x: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-header h2 {
            margin: 0;
            color: #17365d;
        }

        .page-header p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 7px;
            padding: 10px 15px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #17365d;
            color: white;
        }

        .btn-primary:hover {
            background: #102a4d;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-danger {
            background: #b42318;
            color: white;
        }

        .btn-success {
            background: #027a48;
            color: white;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf3;
            color: #027a48;
        }

        .alert-error {
            background: #fef3f2;
            color: #b42318;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #475467;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background: #ecfdf3;
            color: #027a48;
        }

        .badge-danger {
            background: #fef3f2;
            color: #b42318;
        }

        .badge-role {
            background: #eef4ff;
            color: #3538cd;
            margin: 2px;
        }

        .actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input {
            flex: 1;
            max-width: 400px;
            padding: 10px 12px;
            border: 1px solid #d0d5dd;
            border-radius: 7px;
        }

        @media (max-width: 800px) {
            .sidebar {
                display: none;
            }

            .content {
                padding: 20px;
            }

            .user-info {
                display: none;
            }
        }
    </style>
</head>

<body>

<header class="topbar">

    <div class="brand">
        <h1>SIGLAC</h1>
        <span>UGEL N.° 08 – Cañete</span>
    </div>

    <div class="user-area">

        <div class="user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->email }}</span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="logout" type="submit">
                Cerrar sesión
            </button>
        </form>

    </div>

</header>

<div class="layout">

    <aside class="sidebar">

        <p class="sidebar-title">Principal</p>

        <a
            href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            Inicio
        </a>

@if (
    auth()->user()->hasPermission('usuarios.ver') ||
    auth()->user()->hasPermission('roles.ver') ||
    auth()->user()->hasPermission('permisos.ver')
)

    <p class="sidebar-title" style="margin-top: 25px;">
        Seguridad
    </p>

    @if (auth()->user()->hasPermission('usuarios.ver'))

        <a
            href="{{ route('usuarios.index') }}"
            class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
        >
            Usuarios
        </a>

    @endif

    @if (auth()->user()->hasPermission('roles.ver'))

        <a
            href="{{ route('roles.index') }}"
            class="{{ request()->routeIs('roles.*') ? 'active' : '' }}"
        >
            Roles
        </a>

    @endif

    @if (auth()->user()->hasPermission('permisos.ver'))

        <a
            href="{{ route('permisos.index') }}"
            class="{{ request()->routeIs('permisos.*') ? 'active' : '' }}"
        >
            Permisos
        </a>

    @endif

@endif

    </aside>

    <main class="content">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

</div>

</body>
</html>