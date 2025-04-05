<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Otros elementos del navbar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

    </ul>
    <!-- Información del usuario -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <!-- Imagen del usuario -->
                <img src="{{ auth()->user()->profile_picture_url ? asset('storage/' . auth()->user()->profile_picture_url) : asset('ruta_default_avatar.png') }}"
                    alt="Imagen del usuario" class="img-circle elevation-2" style="width: 30px; height: 30px;">

                <span>{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- Opciones del usuario -->
                <span class="dropdown-item dropdown-header">{{ auth()->user()->email }}</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                    <i class="fas fa-power-off mr-2"></i> Cerrar sesión
                </a>
            </div>
        </li>
    </ul>
</nav>