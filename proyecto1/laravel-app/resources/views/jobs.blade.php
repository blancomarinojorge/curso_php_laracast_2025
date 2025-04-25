<x-layout>
    <x-slot:heading>
        Jobs
    </x-slot:heading>

    <ul>
        @foreach($jobs as $job)
            <li>
                <a href="/jobs/{{ $job["id"] }}">
                    <strong>{{ $job["name"] }}:</strong>
                    This jobs pays {{ $job["salary"] }}$ a year
                </a>
            </li>
        @endforeach
    </ul>
</x-layout>
