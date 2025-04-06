<nav class="main-header navbar navbar-expand navbar-light bg-white shadow-sm">
    <!-- Botón para mostrar/ocultar el menú lateral -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="offcanvas" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Información del usuario -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <!-- Botón desplegable con imagen y nombre del usuario -->
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- Imagen del usuario -->
                <img src="{{ auth()->user()->profile_picture_url ? asset('storage/' . auth()->user()->profile_picture_url) : asset('public/storage/img/desfault.png') }}"
                    alt="Imagen del usuario" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                <span class="ms-2">{{ auth()->user()->name }}</span>
            </a>

            <!-- Menú desplegable -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <!-- Información del usuario -->
                <li class="dropdown-item text-center">
                    <strong>{{ auth()->user()->email }}</strong>
                    <br>
                    <small class="text-muted">
                        <!-- Mostrar el rol del usuario -->
                        {{ auth()->user()->roles->pluck('name')->first() }}
                    </small>
                </li>
                <li><hr class="dropdown-divider"></li>

                <!-- Opción de perfil -->
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user me-2"></i> Perfil
                    </a>
                </li>

                <!-- Opción de cerrar sesión -->
                <li>
                    <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                        <i class="fas fa-power-off me-2"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>