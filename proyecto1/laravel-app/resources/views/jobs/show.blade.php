<x-layout>
    <x-slot:heading>
        Job {{ $job["id"] }}
    </x-slot:heading>

    <h2 class="font-bold text-lg">{{ $job["name"] }}</h2>
    <p>This jobs pays {{ $job["salary"] }}$ a year</p>

    <div>
        <x-button href="/jobs/{{ $job->id }}/edit">Edit job</x-button>
    </div>
</x-layout>
