<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Update network profile')"
            :intro="__('Enter the email address stored in your network profile. If it is registered, we will send you a personal link — valid for 48 hours — to update your profile.')"/>

    <x-layout.section>
        @if(session('status'))
            <p class="mt-6 max-w-2xl rounded-panel bg-gray-400/10 px-4 py-3 text-gray-800 ring-1 ring-gray-400/20 ring-inset">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ localized_route('network.request.store') }}" class="mt-6 max-w-md">
            @csrf

            <label for="email" class="block text-sm font-medium text-gray-800">{{ __('Email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">

            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="mt-4">
                <x-ui.button :label="__('Request link')"/>
            </div>
        </form>
    </x-layout.section>
</x-app-layout>
