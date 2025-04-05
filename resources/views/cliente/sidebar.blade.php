<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-header">Configuraciones del Sistema</li>
    
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pruebas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-flask"></i>
                <p>Pruebas</p>
            </a>
        </li>
    </ul>
    <!-- Incluir el menú desde admin/sidebar.blade.php -->

</nav>