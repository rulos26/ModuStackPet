<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('template_title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    @yield('css')
    @stack('styles')
</head>

<body
    class="hold-transition sidebar-mini"
    @if (session('success')) data-flash-success="{{ session('success') }}" @endif
    @if (session('error')) data-flash-error="{{ session('error') }}" @endif
>
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('template_title')</h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name') }}</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>

    <!-- Detectar el esquema de color del sistema -->
    <script>
        const userPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        const theme = userPrefersDark ? "dark" : "light";
        document.documentElement.setAttribute("data-theme", theme);

        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", e => {
            const newTheme = e.matches ? "dark" : "light";
            document.documentElement.setAttribute("data-theme", newTheme);
        });
    </script>

    {{-- Scripts de página: no ejecutar hasta que admin.js (módulo) exponga jQuery/Swal --}}
    <template id="deferred-page-scripts">
        @yield('js')
        @stack('scripts')
    </template>
</body>

</html>
