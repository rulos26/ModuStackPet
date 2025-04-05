<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- AdminLTE CSS desde CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap (opcional, para mayor compatibilidad) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Cargar el archivo app.css generado por Laravel Mix -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Script para detectar inactividad -->
    <script>
        // Tiempo en milisegundos antes de considerar inactividad (ejemplo: 10 minutos)
        const inactivityTime = 10 * 60 * 1000;

        let inactivityTimer;

        // Función para redirigir al usuario a la raíz
        function logoutDueToInactivity() {
            alert('Has sido desconectado por inactividad.');
            window.location.href = "{{ route('logout') }}"; // Cambia a tu ruta de cierre de sesión
        }

        // Resetear el temporizador de inactividad
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(logoutDueToInactivity, inactivityTime);
        }

        // Escuchar eventos que indican actividad del usuario
        window.onload = resetInactivityTimer;
        document.onmousemove = resetInactivityTimer;
        document.onkeydown = resetInactivityTimer;
        document.onscroll = resetInactivityTimer;
        document.onclick = resetInactivityTimer;
    </script>

    <!-- Script para detectar el tema del sistema y aplicar el tema correspondiente -->
    <script>
        // Detecta el tema preferido del sistema
        function applySystemTheme() {
            const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

            // Verifica si el sistema tiene configurado el tema oscuro
            if (prefersDark) {
                document.documentElement.classList.add('dark-theme');
                // O puedes guardar esta preferencia en localStorage para usarla luego
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark-theme');
                localStorage.setItem('theme', 'light');
            }
        }

        // Aplica el tema al cargar la página
        window.addEventListener('load', () => {
            // Verifica si hay un tema guardado en localStorage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark-theme');
                } else {
                    document.documentElement.classList.remove('dark-theme');
                }
            } else {
                // Si no hay un tema guardado, aplica el tema del sistema
                applySystemTheme();
            }

            // Escucha los cambios en el tema del sistema (si el usuario cambia la configuración)
            window.matchMedia("(prefers-color-scheme: dark)").addEventListener('change', applySystemTheme);
        });
    </script>

    <!-- Cargar el archivo app.js -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
