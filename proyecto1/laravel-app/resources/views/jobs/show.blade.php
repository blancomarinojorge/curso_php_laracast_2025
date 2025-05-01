<x-layout>
    <x-slot:heading>
        Job {{ $job["id"] }}
    </x-slot:heading>

    <h2 class="font-bold text-lg">{{ $job["name"] }}</h2>
    <p>This jobs pays {{ $job["salary"] }}$ a year</p>
</x-layout>
