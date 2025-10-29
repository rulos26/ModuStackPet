<ul class="nav flex-column">
    @foreach($items as $item)
        <li class="nav-item">
            <a class="nav-link" href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}">
                <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['name'] }}
            </a>
        </li>
    @endforeach
</ul>


