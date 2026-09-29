{{-- estoe s un copia de menu original  --}}
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        {{-- DASHBOARD GENERAL --}}
        <li class="nav-header text-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </li>
        <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('mensaje-de-bienvenidas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-comments"></i>
                <p>Bienvenida</p>
            </a>
        </li>

        {{-- SECCIÓN SUPERADMIN Y ADMIN --}}
        @role('Superadmin|Admin')
        <li class="nav-header text-success mt-2">
            <i class="fas fa-cogs"></i> Configuración del Sistema
        </li>
        <li class="nav-item">
            <a href="{{ route('empresas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-building"></i>
                <p>Empresas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>Usuarios</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('usuarios.roles.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>Asignar Roles</p>
            </a>
        </li>
        <li class="nav-header text-warning mt-2">
            <i class="fas fa-sliders-h"></i> Configuraciones Funcionales
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
            <a href="{{ route('paths-documentos.index') }}" class="nav-link {{ request()->routeIs('paths-documentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>{{ __('Rutas de Documentos') }}</p>
            </a>
        </li>
        @endrole

        {{-- DASHBOARD PASEADOR --}}
        @role('Paseador')
        <li class="nav-header text-info mt-2">
            <i class="fas fa-walking"></i> Dashboard Paseador
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('razas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-paw"></i>
                <p>Razas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('barrios.index') }}" class="nav-link">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Barrios</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link">
                <i class="nav-icon fas fa-syringe"></i>
                <p>Vacunas y Certificaciones</p>
            </a>
        </li>
        @endrole

        {{-- DASHBOARD CLIENTE --}}
        @role('Cliente')
        <li class="nav-header text-info mt-2">
            <i class="fas fa-user"></i> Dashboard Cliente
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mis Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link">
                <i class="nav-icon fas fa-syringe"></i>
                <p>Vacunas y Certificaciones</p>
            </a>
        </li>
        @endrole

        {{-- PDF Y OTROS (VISIBLES PARA TODOS) --}}
        <li class="nav-header text-secondary mt-2">
            <i class="fas fa-file-pdf"></i> Utilidades
        </li>
        <li class="nav-item">
            <a href="{{ route('pdf.generar') }}" class="nav-link">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>PDF Ejemplo</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pdf.mascota') }}" class="nav-link">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>PDF Mascota</p>
            </a>
        </li>
    </ul>
</nav>
