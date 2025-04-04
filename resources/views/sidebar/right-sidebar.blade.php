@section('right-sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-warning">
        AJUSTES DE CUENTA
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('departamentos.index') ? 'active' : '' }}">
                        <i class="fas fa-globe nav-icon text-primary"></i>
                        <p>Departamentos</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('municipios.index') ? 'active' : '' }}">
                        <i class="fa fa-city nav-icon text-warning"></i>
                        <p>Ciudades</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reporte.carta') }}"
                        class="nav-link {{ request()->routeIs('reporte.carta') ? 'active' : '' }}">
                        <i class="fa fa-file-pdf nav-icon text-danger"></i>
                        <p>Reportes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('informes.create') ? 'active' : '' }}">
                        <i class="fa fa-check-circle nav-icon text-success"></i>
                        <p>Fin Reporte</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{#" class="nav-link {{ request()->routeIs('modulos.index') ? 'active' : '' }}">
                        <i class="fas fa-cubes nav-icon text-info"></i>
                        <p>Módulos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('links.index') ? 'active' : '' }}">
                        <i class="fas fa-link nav-icon text-primary"></i>
                        <p>Links Módulos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('filtros-pdfs.index') ? 'active' : '' }}">
                        <i class="fas fa-filter nav-icon text-primary"></i>
                        <p>Filtro Informe final</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon text-primary"></i>
                        <p>Usuarios del Sistema</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin/settings" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon text-warning"></i>
                        <p>Perfil</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin/settings" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-lock nav-icon text-danger"></i>
                        <p>Cambiar Contraseña</p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
@endsection