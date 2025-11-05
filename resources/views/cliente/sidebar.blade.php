@php
    // Cargar todos los módulos activos una sola vez al inicio para optimizar consultas
    $modulesCache = \App\Models\Module::where('status', true)->pluck('status', 'slug')->toArray();
    $isModuleActive = function($slug) use ($modulesCache) {
        return isset($modulesCache[$slug]);
    };
@endphp

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        {{-- DASHBOARD GENERAL --}}
        <li class="nav-header text-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </li>
        <li class="nav-item">
            <a href="{{ route('cliente.dashboard') }}" class="nav-link {{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>

        {{-- MI PERFIL --}}
        <li class="nav-header text-success mt-2">
            <i class="fas fa-user"></i> Mi Perfil
        </li>
        <li class="nav-item">
            <a href="{{ route('cliente.perfil.show', auth()->user()->id) }}" class="nav-link {{ request()->routeIs('cliente.perfil.show') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-circle"></i>
                <p>Ver Perfil</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cliente.perfil.edit', auth()->user()->id) }}" class="nav-link {{ request()->routeIs('cliente.perfil.edit') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-edit"></i>
                <p>Editar Perfil</p>
            </a>
        </li>

        {{-- MIS MASCOTAS --}}
        @if($isModuleActive('mascotas'))
        <li class="nav-header text-warning mt-2">
            <i class="fas fa-paw"></i> Mis Mascotas
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link {{ request()->routeIs('mascotas.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Lista de Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.create') }}" class="nav-link {{ request()->routeIs('mascotas.create') ? 'active' : '' }}">
                <i class="nav-icon fas fa-plus"></i>
                <p>Agregar Mascota</p>
            </a>
        </li>
        @endif

        {{-- VACUNAS Y CERTIFICACIONES --}}
        @if($isModuleActive('certificados'))
        <li class="nav-header text-info mt-2">
            <i class="fas fa-syringe"></i> Vacunas y Certificaciones
        </li>
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link {{ request()->routeIs('vacunas_certificaciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-medical"></i>
                <p>Registros</p>
            </a>
        </li>
        @endif

        {{-- ÁRBOL GENEALÓGICO --}}
        @if($isModuleActive('arbol-genealogico'))
        <li class="nav-header text-primary mt-2">
            <i class="fas fa-sitemap"></i> Árbol Genealógico
        </li>
        <li class="nav-item">
            <a href="{{ route('cliente.arbol_genealogico') }}" class="nav-link {{ request()->routeIs('cliente.arbol_genealogico') ? 'active' : '' }}">
                <i class="nav-icon fas fa-project-diagram"></i>
                <p>Mi Familia de Mascotas</p>
            </a>
        </li>
        @endif
    </ul>
</nav>
