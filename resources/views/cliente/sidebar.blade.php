<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        {{-- DASHBOARD GENERAL --}}
        <li class="nav-header text-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </li>
        <li class="nav-item">
            <a href="{{ route('cliente.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>

        {{-- MI PERFIL --}}
        <li class="nav-header text-success mt-2">
            <i class="fas fa-user"></i> Mi Perfil
        </li>
        <li class="nav-item">
            <a href="{{ route('users.show', auth()->user()->id) }}" class="nav-link">
                <i class="nav-icon fas fa-user-circle"></i>
                <p>Ver Perfil</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.edit', auth()->user()->id) }}" class="nav-link">
                <i class="nav-icon fas fa-user-edit"></i>
                <p>Editar Perfil</p>
            </a>
        </li>

        {{-- MIS MASCOTAS --}}
        <li class="nav-header text-warning mt-2">
            <i class="fas fa-paw"></i> Mis Mascotas
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>Lista de Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.create') }}" class="nav-link">
                <i class="nav-icon fas fa-plus"></i>
                <p>Agregar Mascota</p>
            </a>
        </li>

        {{-- VACUNAS Y CERTIFICACIONES --}}
        <li class="nav-header text-info mt-2">
            <i class="fas fa-syringe"></i> Vacunas y Certificaciones
        </li>
        <li class="nav-item">
            <a href="{{ route('vacunas-certificaciones.index') }}" class="nav-link">
                <i class="nav-icon fas fa-file-medical"></i>
                <p>Registros</p>
            </a>
        </li>
    </ul>
</nav>
