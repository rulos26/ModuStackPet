<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-header">Configuraciones del Sistema</li>

        <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('mensaje-de-bienvenidas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-comments"></i>
                <p>Dashboard Mensaje de Bienvenida</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>Usuarios</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tipo-documentos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-tag"></i>
                <p>Tipo Documentos</p>
            </a>
        </li>
        @role('Superadmin|Admin')
        <li class="nav-item">
            <a href="{{ route('usuarios.roles.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>Asignar Roles</p>
            </a>
        </li>
        @endrole

        <li class="nav-header">Gestión de Mascotas</li>

        <li class="nav-item">
            <a href="{{ route('barrios.index') }}" class="nav-link">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Barrios</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('razas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-paw"></i>
                <p>Razas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pdf.generar') }}" class="nav-link">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>PDF</p>
            </a>
        </li>

    </ul>
</nav>