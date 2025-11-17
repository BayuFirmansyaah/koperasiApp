@props(['align' => 'end'])

<div class="dropdown" {{ $attributes }}>
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        {{ $trigger }}
    </a>

    <ul class="dropdown-menu dropdown-menu-{{ $align }}">
        {{ $content }}
    </ul>
</div>
