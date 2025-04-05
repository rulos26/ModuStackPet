<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Cargar Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos generales */
        .logo-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        body {
            transition: background-color 0.3s, color 0.3s;
        }

        /* Modo claro */
        body.light {
            background-color: #f8f9fa; /* Color de fondo claro */
            color: #212529; /* Color de texto oscuro */
        }

        .card.light {
            background-color: #ffffff; /* Fondo de la tarjeta en modo claro */
            color: #212529;
        }

        /* Modo oscuro */
        body.dark {
            background-color: #121212; /* Color de fondo oscuro */
            color: #e0e0e0; /* Color de texto claro */
        }

        .card.dark {
            background-color: #1e1e1e; /* Fondo de la tarjeta en modo oscuro */
            color: #e0e0e0;
        }

        /* Transición para el cambio de tema */
        .card {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <!-- Logo -->
                <div class="logo-container text-center mb-4">
                    <img src="{{ asset('storage/img/logo.jpg') }}" alt="Logo">
                </div>

                <!-- Card de Login -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Iniciar Sesión</h1>

                        <!-- Mostrar errores -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Formulario -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        👁️
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>

                        <!-- Enlaces -->
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('register') }}" class="text-decoration-none">¿No tienes una cuenta? Regístrate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para mostrar/ocultar contraseña -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        // Detectar el esquema de color del sistema y aplicar el tema
        function applyTheme() {
            const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
            const body = document.body;
            const card = document.querySelector('.card');

            if (prefersDarkScheme) {
                body.classList.remove('light');
                body.classList.add('dark');
                card.classList.remove('light');
                card.classList.add('dark');
            } else {
                body.classList.remove('dark');
                body.classList.add('light');
                card.classList.remove('dark');
                card.classList.add('light');
            }
        }

        // Aplicar el tema al cargar la página
        applyTheme();

        // Escuchar cambios en el esquema de color del sistema
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", applyTheme);
    </script>

    <!-- Cargar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
