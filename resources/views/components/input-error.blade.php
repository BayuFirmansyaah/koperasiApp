@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => '']) }}>
        @foreach ((array) $messages as $message)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @endforeach
    </div>
@endif
