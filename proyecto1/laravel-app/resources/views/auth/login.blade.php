<x-layout>
    <x-slot name="heading">Login</x-slot>

    <form method="post" action="/login">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base/7 font-semibold text-gray-900">Profile</h2>
                <p class="mt-1 text-sm/6 text-gray-600">This information will be displayed publicly so be careful what you share.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-form-field>
                        <x-form-label for="email">Email</x-form-label>
                        <div class="mt-2">
                            <div class="mt-2">
                                <x-form-input value="{{ old('email') }}" name="email" id="email" placeholder="a@gmail.com" />
                            </div>
                        </div>
                        <x-form-error name="email"/>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="password">Password</x-form-label>
                        <div class="mt-2">
                            <div class="mt-2">
                                <x-form-input type="password" name="password" id="password" />
                            </div>
                        </div>
                        <x-form-error name="password"/>
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

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
            <x-form-button>Save</x-form-button>
        </div>
    </form>

</x-layout>
