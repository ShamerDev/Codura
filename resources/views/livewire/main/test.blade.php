<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <h1 class="text-2xl mt-3 ml-7 font-bold text-blue-600">Hello from Volt component!</h1>

    {{-- Tailwind: if styled correctly, this text should appear blue and large --}}
    <x-button class="bg-indigo-500 text-white px-4 py-2 ml-7 rounded hover:bg-indigo-600">
        Tailwind Test Button
    </x-button>

    {{-- WireUI test --}}
    <x-button label="WireUI Test Button" class="mt-5 ml-7" />
</div>
