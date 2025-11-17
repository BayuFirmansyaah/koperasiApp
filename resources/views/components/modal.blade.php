@props([
    'id' => 'modal-' . uniqid(),
    'show' => false,
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" @if($show) style="display: block;" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
