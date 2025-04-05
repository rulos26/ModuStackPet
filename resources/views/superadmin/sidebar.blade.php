<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-header">Configuraciones del Sistema</li>
    
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Inicio</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pruebas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-vial"></i>
                <p>Pruebas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('estados-ciclos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-sync-alt"></i>
                <p>Estados Ciclos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ciclos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-history"></i>
                <p>Ciclos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('clientes.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-friends"></i>
                <p>Clientes</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('productos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-box"></i>
                <p>Productos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('estados-pedidos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-clipboard-check"></i>
                <p>Estados Pedido</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('estados-deudas.index') }}" class="nav-link">
                <i class="nav-icon fas fa-money-bill-wave"></i>
                <p>Estados Deuda</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pedidos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>Pedidos</p>
            </a>
        </li>
        >
    </ul>
</nav>
