<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        :root[data-theme="light"] {
            --background-color: #f4f7fc;
            --container-bg: #ffffff;
            --text-color: #333;
            --input-bg: #fafafa;
            --input-border: #ccc;
            --button-bg: #007bff;
            --button-hover-bg: #0056b3;
            --link-color: #007bff;
        }

        :root[data-theme="dark"] {
            --background-color: #121212;
            --container-bg: #1e1e1e;
            --text-color: #e0e0e0;
            --input-bg: #333;
            --input-border: #555;
            --button-bg: #0056b3;
            --button-hover-bg: #007bff;
            --link-color: #66ccff;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: background-color 0.3s, color 0.3s;
        }

        .login-container {
            background-color: var(--container-bg);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: background-color 0.3s;
        }

        h1 {
            margin-bottom: 30px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 1rem;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--input-border);
            border-radius: 4px;
            font-size: 1rem;
            background-color: var(--input-bg);
            color: var(--text-color);
            transition: background-color 0.3s, border-color 0.3s;
        }

        input:focus {
            border-color: var(--button-bg);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--button-bg);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--button-hover-bg);
        }

        .links a {
            color: var(--link-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="links">
            <p><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></p>
            <p><a href="{{ route('register') }}">¿No tienes una cuenta? Regístrate</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const passwordToggle = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordToggle.textContent = "🙈";
            } else {
                passwordField.type = "password";
                passwordToggle.textContent = "👁️";
            }
        }

        // Detectar el esquema de color del sistema
        const userPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        const theme = userPrefersDark ? "dark" : "light";
        document.documentElement.setAttribute("data-theme", theme);

        // Cambiar el tema automáticamente si el usuario cambia su preferencia
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", e => {
            const newTheme = e.matches ? "dark" : "light";
            document.documentElement.setAttribute("data-theme", newTheme);
        });
    </script>
</body>
</html>
