<ul class="nav flex-column">
    @foreach($items as $item)
        <li class="nav-item">
            @if($item['route'] !== '#' && \Illuminate\Support\Facades\Route::has($item['route']))
                <a class="nav-link" href="{{ route($item['route']) }}">
                    <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['name'] }}
                </a>
            @else
                <span class="nav-link text-muted" title="Ruta no configurada para este módulo">
                    <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['name'] }}
                    <small class="text-warning">(sin ruta)</small>
                </span>
            @endif
        </li>
    @endforeach
</ul>


