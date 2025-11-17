@props([
    'label',
    'icon',
    'routes' => [],
    'id'
])

@php
    $isActive = false;
    foreach($routes as $route) {
        if(request()->is($route)) {
            $isActive = true;
            break;
        }
    }
@endphp

<li class="sidebar-item has-submenu {{ $isActive ? 'active open' : '' }}">
    <a href="#{{ $id }}" class="sidebar-link" data-bs-toggle="collapse" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
        <span class="sidebar-icon">
            {{ $icon }}
        </span>
        <span class="sidebar-text">{{ $label }}</span>
        <span class="sidebar-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </a>
    <ul class="sidebar-submenu collapse {{ $isActive ? 'show' : '' }}" id="{{ $id }}">
        {{ $slot }}
    </ul>
</li>
