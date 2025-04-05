<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text font-weight-light">NovavenraCaroLTE</span>
    </a>
    <div class="sidebar">
        <!-- Permisos por rol -->
        @if($roles->contains('Admin'))
            @include('admin.sidebar')
        @endif

        @if($roles->contains('Cliente'))
            @include('cliente.sidebar')
        @endif

        @if($roles->contains('Superadmin'))
            @include('superadmin.sidebar')
        @endif

       {{--  <!-- Botón de logout -->
        <div class="mt-3">
            <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center">
                @csrf
                
                <button type="submit" class="btn btn-danger rounded-circle" style="width: 50px; height: 50px;">
                    <i class="fas fa-power-off"></i>
                </button>
            </form>
        </div> --}}
    </div>
</aside>
