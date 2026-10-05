<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <!-- Cargar Bootstrap 5 (misma versión que el login) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100 py-4">
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-3">Recuperar contraseña</h1>
                        <p class="text-center text-muted small">
                            Escribe tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                        </p>

                        <!-- Mensaje de estado (enlace enviado) -->
                        @if (session('status'))
                            <div class="alert alert-success" role="status">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Errores de validación -->
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       autocomplete="email" spellcheck="false" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar enlace de recuperación</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">Volver a iniciar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
