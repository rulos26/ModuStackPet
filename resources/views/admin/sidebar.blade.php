<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        {{-- DASHBOARD GENERAL --}}
        <li class="nav-header text-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>

        {{-- GESTIÓN DE USUARIOS --}}
        <li class="nav-header text-success mt-2">
            <i class="fas fa-users"></i> Gestión de Usuarios
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>Lista de Usuarios</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.create') }}" class="nav-link">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>Crear Usuario</p>
            </a>
        </li>

        {{-- CONFIGURACIÓN DEL SISTEMA --}}
        <li class="nav-header text-warning mt-2">
            <i class="fas fa-cogs"></i> Configuración del Sistema
        </li>
        <li class="nav-item">
            <a href="{{ route('empresas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-building"></i>
                <p>Empresas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('usuarios.roles.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>Asignar Roles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('departamentos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Departamentos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ciudades.index') }}" class="nav-link">
                <i class="nav-icon fas fa-city"></i>
                <p>Ciudades</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sectores.index') }}" class="nav-link">
                <i class="nav-icon fas fa-industry"></i>
                <p>Sectores</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tipos-empresas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-building"></i>
                <p>Tipos de Empresas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tipo-documentos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-tag"></i>
                <p>Tipo Documentos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('paths-documentos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>Rutas de Documentos</p>
            </a>
        </li>
    </ul>
</nav>
