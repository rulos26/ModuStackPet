<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-paw"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ModuStackPet</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gestión de Usuarios
    </div>

    <!-- Nav Item - Usuarios -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('usuarios.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Usuarios</span>
        </a>
    </li>

    <!-- Nav Item - Roles -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="fas fa-fw fa-user-tag"></i>
            <span>Roles</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gestión de Mascotas
    </div>

    <!-- Nav Item - Mascotas -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('mascotas.index') }}">
            <i class="fas fa-fw fa-dog"></i>
            <span>Mascotas</span>
        </a>
    </li>

    <!-- Nav Item - Razas -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('razas.index') }}">
            <i class="fas fa-fw fa-paw"></i>
            <span>Razas</span>
        </a>
    </li>

    <!-- Nav Item - Barrios -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('barrios.index') }}">
            <i class="fas fa-fw fa-map-marker-alt"></i>
            <span>Barrios</span>
        </a>
    </li>

    <!-- Nav Item - Vacunas y Certificaciones -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('vacunas-certificaciones.index') }}">
            <i class="fas fa-fw fa-syringe"></i>
            <span>Vacunas y Certificaciones</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gestión de Servicios
    </div>

    <!-- Nav Item - Paseadores -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('paseadores.index') }}">
            <i class="fas fa-fw fa-walking"></i>
            <span>Paseadores</span>
        </a>
    </li>

    <!-- Nav Item - Cuidadores -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('cuidadores.index') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Cuidadores</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Configuración
    </div>

   {{--   <!-- Nav Item - Configuración -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('configuracion.index') }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Configuración</span>
        </a>
    </li>

    <!-- Nav Item - Rutas de Documentos -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('paths-documentos.index') }}">
            <i class="fas fa-fw fa-folder-open"></i>
            <span>Rutas de Documentos</span>
        </a>
    </li>

    <!-- Nav Item - Tipos de Documentos -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('tipo-documentos.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Tipos de Documentos</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
  --}}
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
