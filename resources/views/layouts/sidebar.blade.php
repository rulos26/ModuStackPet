<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo y nombre de la empresa -->
    <a href="{{ url('/') }}" class="brand-link d-flex align-items-center">
        <!-- Logo de la empresa -->
        <img src="{{ asset('public/storage/img/logo.jpg') }}" alt="Logo de la empresa" class="brand-image img-circle elevation-3" style="width: 30px; height: 30px; object-fit: cover;">
        <!-- Nombre de la empresa -->
        <span class="brand-text font-weight-light ml-2">ModuStackPetLTE</span>
    </a>
    <div class="sidebar">
        <!-- Permisos por rol -->
        @if(auth()->user()?->hasRole('Admin'))
            {{-- @include('admin.sidebar') --}}
        @endif

        @if(auth()->user()?->hasRole('Cliente'))
          {{-- @include('cliente.sidebar') --}}
        @endif

        @if(auth()->user()?->hasRole('Superadmin'))
            @include('superadmin.sidebar')
        @endif


    </div>
</aside>
