<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | SIGLAC</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border-radius: 14px;
            padding: 42px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            border-radius: 14px;
            background: #17365d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: bold;
        }

        .header h1 {
            color: #17365d;
            font-size: 26px;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #17365d;
        }

        button {
            width: 100%;
            border: none;
            padding: 13px;
            border-radius: 8px;
            background: #17365d;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #102a4d;
        }

        .error {
            margin-top: 6px;
            color: #b42318;
            font-size: 13px;
        }

        .alert-success {
            background: #ecfdf3;
            color: #027a48;
            padding: 11px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .footer {
            margin-top: 26px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="header">
        <div class="logo">S</div>

        <h1>SIGLAC</h1>

        <p>
            Sistema Web de Gestión y Localización de
            Actas y Certificados Históricos
        </p>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.authenticate') }}">
        @csrf

        <div class="form-group">
            <label for="email">Correo electrónico</label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                autofocus
            >

            @error('email')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>

            <input
            
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
            >

            <label style="display: flex; align-items: center; gap: 8px; margin-top: 10px; font-weight: normal;">
    <input
        type="checkbox"
        id="mostrarPassword"
        style="width: auto;"
    >
    Mostrar contraseña
</label>

            @error('password')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit">
            Iniciar sesión
        </button>
    </form>

    <div class="footer">
        UGEL N.° 08 – Cañete
    </div>

</div>

<script>
    const mostrarPassword = document.getElementById('mostrarPassword');
    const password = document.getElementById('password');

    mostrarPassword.addEventListener('change', function () {
        password.type = this.checked ? 'text' : 'password';
    });
</script>

</body>
</html>