@props([
    'route',
    'label' => null
])

<li class="sidebar-subitem {{ request()->routeIs($route) ? 'active' : '' }}">
    <a href="{{ route($route) }}" class="sidebar-sublink">
        <span class="sidebar-bullet"></span>
        <span>{{ $label ?? $slot }}</span>
    </a>
</li>
