@props([
    'route',
    'icon',
    'label' => null,
    'badge' => null,
    'badgeColor' => 'primary',
    'can' => null
])

@if(!$can || auth()->user()?->can($can))
<li class="sidebar-item {{ request()->routeIs($route) ? 'active' : '' }}">
    <a href="{{ route($route) }}" class="sidebar-link">
        <span class="sidebar-icon">
            {{ $icon }}
        </span>
        <span class="sidebar-text">{{ $label ?? $slot }}</span>
        @if($badge)
            <span class="sidebar-badge badge-{{ $badgeColor }}">{{ $badge }}</span>
        @endif
    </a>
</li>
@endif
