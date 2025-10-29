<nav class="main-header navbar navbar-expand navbar-light bg-white shadow-sm">
    <!-- Botón para mostrar/ocultar el menú lateral -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Menú del lado derecho -->
    <ul class="navbar-nav ms-auto me-3">
        <!-- Icono de notificaciones -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button">
                <i class="far fa-bell"></i>
                @if(isset($notificaciones) && $notificaciones->count())
                    <span class="badge bg-danger">{{ $notificaciones->count() }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                @forelse($notificaciones as $notificacion)
                    <li class="dropdown-item">
                        {{ $notificacion->data['message'] ?? 'Tienes una nueva notificación' }}
                    </li>
                @empty
                    <li class="dropdown-item text-muted">Sin notificaciones</li>
                @endforelse

                {{-- Opción para marcar todas como leídas --}}
                @if(isset($notificaciones) && $notificaciones->count())
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="dropdown-item text-center">
                        <form action="{{ route('notificaciones.marcar.leidas') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link">Marcar todas como leídas</button>
                        </form>
                    </li>
                @endif
            </ul>
        </li>

        <!-- Información del usuario -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @php($currentUser = auth()->user())
                @php($profileUrl = $currentUser && $currentUser->profile_picture_url ? asset('storage/' . $currentUser->profile_picture_url) : asset('public/storage/img/desfault.png'))
                <img src="{{ $profileUrl }}"
                    alt="Imagen del usuario" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                <span class="ms-2">{{ $currentUser?->name ?? 'Invitado' }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li class="dropdown-item text-center">
                    <strong>{{ $currentUser?->email ?? 'No autenticado' }}</strong>
                    <br>
                    <small class="text-muted">{{ $currentUser?->roles?->pluck('name')->first() ?? '' }}</small>
                </li>
                <li><hr class="dropdown-divider"></li>

                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user me-2"></i> Perfil
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                        <i class="fas fa-power-off me-2"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
