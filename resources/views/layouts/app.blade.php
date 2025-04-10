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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

    <!-- Cargar archivo app.css de manera estática -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

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

    <!-- Detectar el esquema de color del sistema -->
    <script>
        const userPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        const theme = userPrefersDark ? "dark" : "light";
        document.documentElement.setAttribute("data-theme", theme);

        // Cambiar el tema automáticamente si el usuario cambia su preferencia
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", e => {
            const newTheme = e.matches ? "dark" : "light";
            document.documentElement.setAttribute("data-theme", newTheme);
        });
    </script>

    <!-- Cargar archivo app.js de manera estática -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>