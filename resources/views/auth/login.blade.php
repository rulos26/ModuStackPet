<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Cargar Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <!-- Logo -->
                <div class="logo-container text-center mb-4">
                    <!-- Imagen del logo redonda -->
                    <img src="{{ asset('public/storage/img/logo.jpg') }}" alt="Logo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                </div>

                <!-- Card de Login -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Título del formulario -->
                        <h1 class="h4 text-center mb-4">Iniciar Sesión</h1>

                        <!-- Mostrar errores de validación -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Formulario de inicio de sesión -->
                        <form method="GET" action="{{ route('login') }}">
                            @csrf
                            <!-- Campo de correo electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <!-- Campo de contraseña con botón para mostrar/ocultar -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        👁️
                                    </button>
                                </div>
                            </div>
                            <label>
                                <input type="checkbox" name="remember"> Recordarme
                            </label>
                            <!-- Botón de inicio de sesión -->
                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>

                        <!-- Enlaces adicionales -->
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
        /**
         * Función para alternar entre mostrar y ocultar la contraseña.
         */
        function togglePassword() {
            const passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        /**
         * Detectar el esquema de color del sistema (modo claro/oscuro)
         * y aplicar las clases de Bootstrap correspondientes.
         */
        function applyTheme() {
            const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
            const body = document.body;
            const card = document.querySelector('.card');

            if (prefersDarkScheme) {
                // Aplicar clases de Bootstrap para modo oscuro
                body.classList.add('bg-dark', 'text-light');
                card.classList.add('bg-dark', 'text-light');
            } else {
                // Remover clases de modo oscuro y aplicar modo claro
                body.classList.remove('bg-dark', 'text-light');
                card.classList.remove('bg-dark', 'text-light');
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
