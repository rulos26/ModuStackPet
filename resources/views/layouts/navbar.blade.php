@php
    $notificaciones = auth()->user()->unreadNotifications;
@endphp

<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
        <i class="far fa-bell"></i>
        @if ($notificaciones->count() > 0)
            <span class="badge bg-danger">{{ $notificaciones->count() }}</span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header">Notificaciones</li>
        @forelse ($notificaciones as $notificacion)
            <li>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope me-2"></i> {{ $notificacion->data['mensaje'] ?? 'Tienes una nueva notificación' }}
                    <span class="float-end text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>
                </a>
            </li>
        @empty
            <li><a href="#" class="dropdown-item text-muted">No hay notificaciones</a></li>
        @endforelse
        <li><hr class="dropdown-divider"></li>
        <li>
            <form action="{{ route('notificaciones.marcar.leidas') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-center text-primary">Marcar todas como leídas</button>
            </form>
        </li>
    </ul>
</li>
