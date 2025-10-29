
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
        <li class="nav-item">
            <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>Empresas</p>
            </a>
        </li>

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

        {{-- Configuraciones del Sistema --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.configuraciones.index') }}" class="nav-link {{ request()->routeIs('superadmin.configuraciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>Configuraciones del Sistema</p>
            </a>
        </li>

        {{-- Gestión de Migraciones --}}
        <li class="nav-item">
            <a href="{{ route('superadmin.migrations.index') }}" class="nav-link {{ request()->routeIs('superadmin.migrations.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>Gestión de Migraciones</p>
            </a>
        </li>

        {{-- Configuraciones Funcionales --}}
        <li class="nav-header text-warning mt-2">
            <i class="fas fa-sliders-h"></i> Configuraciones Funcionales
        </li>

        {{-- Mensaje de Bienvenida --}}
        <li class="nav-item">
            <a href="{{ route('mensaje-de-bienvenidas.index') }}" class="nav-link {{ request()->routeIs('mensaje-de-bienvenidas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-comments"></i>
                <p>Bienvenida</p>
            </a>
        </li>

        {{-- Gestión Geográfica --}}
        <li class="nav-item">
            <a href="{{ route('departamentos.index') }}" class="nav-link {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Departamentos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ciudades.index') }}" class="nav-link {{ request()->routeIs('ciudades.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-city"></i>
                <p>Ciudades</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sectores.index') }}" class="nav-link {{ request()->routeIs('sectores.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-industry"></i>
                <p>Sectores</p>
            </a>
        </li>

        {{-- Configuraciones de Empresa y Documentos --}}
        <li class="nav-item">
            <a href="{{ route('tipos-empresas.index') }}" class="nav-link {{ request()->routeIs('tipos-empresas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>Tipos de Empresas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tipo-documentos.index') }}" class="nav-link {{ request()->routeIs('tipo-documentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-tag"></i>
                <p>Tipo Documentos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('paths-documentos.index') }}" class="nav-link {{ request()->routeIs('paths-documentos.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>Rutas de Documentos</p>
            </a>
        </li>
        @endrole

        {{-- Dashboard Paseador - Visible para Superadmin y Paseador --}}
        @role('Superadmin|Paseador')
        <li class="nav-header text-info mt-2">
            <i class="fas fa-walking"></i> Dashboard Paseador
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link {{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('razas.index') }}" class="nav-link {{ request()->routeIs('razas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-paw"></i>
                <p>Razas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('barrios.index') }}" class="nav-link {{ request()->routeIs('barrios.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>Barrios</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link {{ request()->routeIs('vacunas_certificaciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-syringe"></i>
                <p>Vacunas y Certificaciones</p>
            </a>
        </li>
        @endrole

        {{-- Dashboard Cliente - Visible para Superadmin y Cliente --}}
        @role('Superadmin|Cliente')
        <li class="nav-header text-info mt-2">
            <i class="fas fa-user"></i> Dashboard Cliente
        </li>
        <li class="nav-item">
            <a href="{{ route('mascotas.index') }}" class="nav-link {{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-dog"></i>
                <p>Mis Mascotas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vacunas_certificaciones.index') }}" class="nav-link {{ request()->routeIs('vacunas_certificaciones.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-syringe"></i>
                <p>Vacunas y Certificaciones</p>
            </a>
        </li>
        @endrole

        {{-- Utilidades Comunes - Visibles para todos los usuarios --}}
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
    </ul>
</nav>
