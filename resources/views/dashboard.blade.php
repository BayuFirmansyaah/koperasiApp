<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-5" style="color: #1e293b;">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container-xl">
            <div class="card">
                <div class="card-body" style="color: #1e293b;">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
