@php
    $notificationCount =  $notificationCount ; // Aquí debes obtener el recuento real de notificaciones
    $notifications = [
        ['icon' => 'fas fa-envelope', 'text' => '4 new messages', 'time' => '3 mins'],
        ['icon' => 'fas fa-users', 'text' => '8 friend requests', 'time' => '12 hours'],
        ['icon' => 'fas fa-file', 'text' => '3 new reports', 'time' => '2 days'],
    ]; // Aquí debes obtener las notificaciones reales
@endphp

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger navbar-badge">{{ $notificationCount }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header"> Tiene {{ $notificationCount }} nueva </span>
        <div class="dropdown-divider"></div>
        @if($notificationCount >= 1)
        <a href="{{ route('verificaciones-checklists.index') }} " class="dropdown-item">
            <i class=" fas fa-file mr-2"></i> tienes {{ $notificationCount }} reporte para revisiòn
            <span class="float-right text-muted text-sm"></span>
        </a>
        @else
            
        @endif
      
        {{-- @foreach ($notifications as $notification)
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <i class="{{ $notification['icon'] }} mr-2"></i> hola -{{ $notification['text'] }}
                <span class="float-right text-muted text-sm">{{ $notification['time'] }}</span>
            </a>
        @endforeach --}}
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
</li>
