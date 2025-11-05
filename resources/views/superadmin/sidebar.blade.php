
@php
    // Cargar todos los módulos activos una sola vez al inicio para optimizar consultas
    $modulesCache = \App\Models\Module::where('status', true)->pluck('status', 'slug')->toArray();
    $isModuleActive = function($slug) use ($modulesCache) {
        return isset($modulesCache[$slug]);
    };
@endphp

<nav class="mt-2">
    {{-- Lista principal de navegación con soporte para árbol de menús --}}
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        {{-- Inicio - Dashboard --}}
        <li class="nav-header text-primary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </li>
        <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>


        {{-- Sección exclusiva para Superadmin y Admin --}}
        @role('Superadmin|Admin')
        <li class="nav-header text-success mt-2">
            <i class="fas fa-cogs"></i> Configuración del Sistema
        </li>

        {{-- Gestión de Empresas --}}
        @if($isModuleActive('empresas'))
        <li class="nav-item">
            <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>Empresas</p>
            </a>
        </li>
        @endif

        {{-- Menú desplegable de Usuarios con control de roles --}}
        <li class="nav-item has-treeview {{ request()->routeIs('usuarios.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Usuarios
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                {{-- Lista de Usuarios --}}
                @role('Superadmin')
                <li class="nav-item">
                    <a href="{{ route('superadmin.usuarios.index') }}" class="nav-link {{ request()->routeIs('superadmin.usuarios.index') ? 'active' : '' }}">
                        <i class="fas fa-list nav-icon"></i>
                        <p>Lista de Usuarios</p>
                    </a>
                </li>
                @endrole

                {{-- Crear Paseador (Solo Superadmin) --}}
                @role('Superadmin')
                <li class="nav-item">
                    <a href="{{ route('superadmin.usuarios.create') }}" class="nav-link {{ request()->routeIs('superadmin.usuarios.create') ? 'active' : '' }}">
                        <i class="fas fa-user-plus nav-icon"></i>
                        <p>Crear Paseador</p>
                    </a>
                </li>
                @endrole

                {{-- Opción visible solo para Superadmin --}}
                @role('Superadmin')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-shield nav-icon"></i>
                        <p>Superadmin</p>
                    </a>
                </li>
                @endrole

                {{-- Opción visible para Superadmin y Admin --}}
                @role('Superadmin|Admin')
                <li class="nav-item">
                    <a href="{{ route('superadmin.users.show') }}" class="nav-link">
                        <i class="fas fa-user-cog nav-icon"></i>
                        <p>Administrador</p>
                    </a>
                </li>
                @endrole

                {{-- Opción visible para Superadmin y Cliente --}}
                @role('Superadmin|Cliente')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user nav-icon"></i>
                        <p>Cliente</p>
                    </a>
                </li>
                @endrole

                {{-- Opción visible para Superadmin y Paseador --}}
                @role('Superadmin|Paseador')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-walking nav-icon"></i>
                        <p>Paseador</p>
                    </a>
                </li>
                @endrole
            </ul>
        </li>

        {{-- Gestión de Roles --}}
        <li class="nav-item">
            <a href="{{ route('usuarios.roles.index') }}" class="nav-link {{ request()->routeIs('usuarios.roles.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>Asignar Roles</p>
            </a>
        </li>

        {{-- Gestión de Proveedores OAuth --}}
        @if($isModuleActive('oauth-providers'))
        <li class="nav-item">
            <a href="{{ route('superadmin.oauth-providers.index') }}" class="nav-link {{ request()->routeIs('superadmin.oauth-providers.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-key"></i>
                <p>Proveedores OAuth</p>
            </a>
        </li>
        @endif

        {{-- Configuración de Base de Datos --}}
        @if($isModuleActive('database-config'))
        <li class="nav-item">
            <a href="{{ route('superadmin.database-configs.index') }}" class="nav-link {{ request()->routeIs('superadmin.database-configs.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>Configuración BD</p>
            </a>
        </li>
        @endif

        {{-- Configuración de Correo Electrónico --}}
        @if($isModuleActive('email-config'))
        <li class="nav-item">
            <a href="{{ route('superadmin.email-configs.index') }}" class="nav-link {{ request()->routeIs('superadmin.email-configs.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-envelope"></i>
                <p>Configuración Email</p>
            </a>
        </li>
        @endif

        {{-- Backup de Base de Datos --}}
        @if($isModuleActive('backup-config'))
        <li class="nav-item">
            <a href="{{ route('superadmin.backup-configs.index') }}" class="nav-link {{ request()->routeIs('superadmin.backup-configs.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>Backup BD</p>
            </a>
        </li>
        @endif

        {{-- Menú desplegable de Avisos Legales --}}
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-newspaper"></i>
                <p>
                    Avisos Legales
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Protección de datos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>Términos y Condiciones</p>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Variables o Tiempo de Sesión --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.configuraciones.index') }}" class="nav-link {{ request()->routeIs('superadmin.configuraciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock"></i>
                <p>Variables o Tiempo de Sesión</p>
            </a>
        </li>

        {{-- Gestión de Migraciones --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.migrations.index') }}" class="nav-link {{ request()->routeIs('superadmin.migrations.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>Gestión de Migraciones</p>
            </a>
        </li>

        {{-- AutoClean - Limpieza del Sistema --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.clean.index') }}" class="nav-link {{ request()->routeIs('superadmin.clean.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-broom"></i>
                <p>AutoClean</p>
            </a>
        </li>

        {{-- Gestión de Seeders --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.seeders.index') }}" class="nav-link {{ request()->routeIs('superadmin.seeders.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-seedling"></i>
                <p>Seeders</p>
            </a>
        </li>

        {{-- Administrador de Módulos --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.modules.index') }}" class="nav-link {{ request()->routeIs('superadmin.modules.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-puzzle-piece"></i>
                <p>Módulos del Sistema</p>
            </a>
        </li>

        {{-- Menú dinámico por módulos activos y permisos --}}
        <li class="nav-header text-primary mt-2">
            <i class="fas fa-puzzle-piece"></i> Módulos Activos
        </li>
        <li class="nav-item">
            <div class="px-2 py-1">
                <livewire:menu.modules-menu />
            </div>
        </li>

        {{-- Configuraciones Funcionales --}}
        <li class="nav-header text-warning mt-2">
            <i class="fas fa-sliders-h"></i> Configuraciones Funcionales
        </li>

        {{-- Mensaje de Bienvenida --}}
        @if($isModuleActive('bienvenida'))
        <li class="nav-item">
            <a href="{{ route('mensaje-de-bienvenidas.index') }}" class="nav-link {{ request()->routeIs('mensaje-de-bienvenidas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-comments"></i>
                <p>Bienvenida</p>
            </a>
        </li>
        @endif

        {{-- Gestión Geográfica --}}
        @if($isModuleActive('departamentos'))
        <li class="nav-item">
            <a href="{{ route('departamentos.index') }}" class="nav-link {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Departamentos</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('ciudades'))
        <li class="nav-item">
            <a href="{{ route('ciudades.index') }}" class="nav-link {{ request()->routeIs('ciudades.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-city"></i>
                <p>Ciudades</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('sectores'))
        <li class="nav-item">
            <a href="{{ route('sectores.index') }}" class="nav-link {{ request()->routeIs('sectores.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-industry"></i>
                <p>Sectores</p>
            </a>
        </li>
        @endif

        {{-- Configuraciones de Empresa y Documentos --}}
        @if($isModuleActive('tipos-empresas'))
        <li class="nav-item">
            <a href="{{ route('tipos-empresas.index') }}" class="nav-link {{ request()->routeIs('tipos-empresas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>Tipos de Empresas</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('tipo-documentos'))
        <li class="nav-item">
            <a href="{{ route('tipo-documentos.index') }}" class="nav-link {{ request()->routeIs('tipo-documentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-tag"></i>
                <p>Tipo Documentos</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('paths-documentos'))
        <li class="nav-item">
            <a href="{{ route('paths-documentos.index') }}" class="nav-link {{ request()->routeIs('paths-documentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>Rutas de Documentos</p>
            </a>
        </li>
        @endif

        {{-- Módulos de Documentos de Mascotas --}}
        @if($isModuleActive('requisitos-documentales'))
        <li class="nav-item">
            <a href="{{ route('admin.document-requirements.index') }}" class="nav-link {{ request()->routeIs('admin.document-requirements.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>Requisitos Documentales</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('documentos-mascotas'))
        <li class="nav-item">
            <a href="{{ route('mascota-documents.index') }}" class="nav-link {{ request()->routeIs('mascota-documents.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-upload"></i>
                <p>Documentos de Mascotas</p>
            </a>
        </li>
        @endif
        @endrole

        {{-- Dashboard Paseador - Visible para Superadmin y Paseador --}}
        @role('Superadmin|Paseador')
        <li class="nav-header text-info mt-2">
            <i class="fas fa-walking"></i> Dashboard Paseador
        </li>
        @if($isModuleActive('mascotas'))
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link {{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mascotas</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('razas'))
        <li class="nav-item">
            <a href="{{ route('razas.index') }}" class="nav-link {{ request()->routeIs('razas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-paw"></i>
                <p>Razas</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('barrios'))
        <li class="nav-item">
            <a href="{{ route('barrios.index') }}" class="nav-link {{ request()->routeIs('barrios.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Barrios</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('certificados'))
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link {{ request()->routeIs('vacunas_certificaciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-syringe"></i>
                <p>Vacunas y Certificaciones</p>
            </a>
        </li>
        @endif
        @endrole

        {{-- Dashboard Cliente - Visible para Superadmin y Cliente --}}
        @role('Superadmin|Cliente')
        <li class="nav-header text-info mt-2">
            <i class="fas fa-user"></i> Dashboard Cliente
        </li>
        @if($isModuleActive('mascotas'))
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link {{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mis Mascotas</p>
            </a>
        </li>
        @endif
        @if($isModuleActive('certificados'))
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link {{ request()->routeIs('vacunas_certificaciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-syringe"></i>
                <p>Vacunas y Certificaciones</p>
            </a>
        </li>
        @endif
        @endrole

        {{-- Utilidades Comunes - Visibles para todos los usuarios --}}
        @if($isModuleActive('reportes'))
        <li class="nav-header text-secondary mt-2">
            <i class="fas fa-file-pdf"></i> Utilidades
        </li>
        <li class="nav-item">
            <a href="{{ route('pdf.generar') }}" class="nav-link {{ request()->routeIs('pdf.generar') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>PDF Ejemplo</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pdf.mascota') }}" class="nav-link {{ request()->routeIs('pdf.mascota') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>PDF Mascota</p>
            </a>
        </li>
        @endif
    </ul>
</nav>
