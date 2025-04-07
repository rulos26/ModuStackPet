@if(auth()->check())
    {{-- Código del navbar completo aquí --}}
@else
    {{-- Mostrar solo un navbar genérico --}}
    <nav class="navbar navbar-light bg-light">
        <span class="navbar-brand">Bienvenido invitado</span>
    </nav>
@endif
