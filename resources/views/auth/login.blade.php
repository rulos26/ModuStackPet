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

                        <!-- Botones de autenticación social -->
                        <div class="mb-4">
                            <p class="text-center text-muted small mb-3">O inicia sesión con:</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('social.redirect', 'google') }}" 
                                   class="btn btn-danger d-flex align-items-center justify-content-center">
                                    <svg width="20" height="20" class="me-2" viewBox="0 0 24 24" fill="white">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                    </svg>
                                    Continuar con Google
                                </a>
                                <a href="{{ route('social.redirect', 'facebook') }}" 
                                   class="btn btn-primary d-flex align-items-center justify-content-center" style="background-color: #1877f2; border-color: #1877f2;">
                                    <svg width="20" height="20" class="me-2" viewBox="0 0 24 24" fill="white">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor"/>
                                    </svg>
                                    Continuar con Facebook
                                </a>
                            </div>
                        </div>

                        <div class="text-center mb-3">
                            <span class="text-muted small bg-white px-2">O</span>
                            <hr class="mt-0">
                        </div>

                        <!-- Mostrar mensajes de sesión -->
                        @if (session('message'))
                            <div class="alert alert-info alert-dismissible fade show">
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                        @endif

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
                        <form method="POST" action="{{ route('login') }}">
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
                            <!-- Checkbox "Recordarme" -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>
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

    <!-- Script para mostrar/ocultar contraseña y debugging -->
    <script>
        console.log('Login Form: Script cargado');

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

        // Debugging del formulario
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Login Form: DOM cargado');

            const form = document.querySelector('form[action="{{ route('login') }}"]');
            if (form) {
                console.log('Login Form: Formulario encontrado', {
                    method: form.method,
                    action: form.action
                });

                form.addEventListener('submit', function(e) {
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;

                    console.log('Login Form: Enviando formulario', {
                        email: email,
                        passwordLength: password.length,
                        method: form.method,
                        action: form.action,
                        hasCSRF: document.querySelector('input[name="_token"]') !== null
                    });

                    if (!email || !password) {
                        console.error('Login Form: Faltan campos requeridos');
                        return;
                    }

                    if (form.method.toUpperCase() !== 'POST') {
                        console.error('Login Form: ERROR - El método debe ser POST, actual:', form.method);
                        e.preventDefault();
                        alert('Error: El formulario debe usar método POST. Por favor, contacta al administrador.');
                        return;
                    }
                });
            } else {
                console.error('Login Form: Formulario NO encontrado');
            }
        });

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
