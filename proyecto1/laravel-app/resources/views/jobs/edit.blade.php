<x-layout>
    <x-slot name="heading">Editing job {{ $job->id }}</x-slot>

    <form method="post" action="/jobs/{{ $job->id }}">
        @csrf
        @method('PATCH')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base/7 font-semibold text-gray-900">Profile</h2>
                <p class="mt-1 text-sm/6 text-gray-600">This information will be displayed publicly so be careful what you share.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-form-field>
                        <x-form-label for="salary">Job Name</x-form-label>
                        <div class="mt-2">
                            <x-form-input value="{{ old('name', $job->name) }}" name="name" id="name" placeholder="Plumber" />
                        </div>
                        <x-form-error name="name"/>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="salary">Year Salary</x-form-label>
                        <div class="mt-2">
                            <x-form-input value="{{ old('salary', $job->salary) }}" name="salary" id="salary" placeholder="4000" />
                        </div>
                        <x-form-error name="salary"/>
                    </x-form-field>
                </div>
            </div>
            @if($errors->any())
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="mt-6 flex items-center justify-between gap-x-6">
            <div class="flex items-center">
                <button form="delete-form" type="submit" class="text-sm font-semibold text-red">Delete</button>
            </div>
            <div class="flex gap-x-6 items-center">
                <a href="/jobs/{{$job->id}}" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                <x-form-button>Update</x-form-button>
            </div>
        </div>
    </form>

    <form id="delete-form" hidden="hidden" method="post" action="/jobs/{{ $job->id }}">
        @csrf
        @method('DELETE')
    </form>

</x-layout>
