@props([
    'class' => ''
])

<li class="sidebar-item {{ $class }}">
    <form action="{{ route('logout') }}" method="POST" class="w-100">
        @csrf
        <button type="submit" class="sidebar-link w-100 text-start" onclick="return confirm('Yakin ingin logout?')">
            <span class="sidebar-icon">
                <x-icon name="logout" />
            </span>
            <span class="sidebar-text">Logout</span>
        </button>
    </form>
</li>
