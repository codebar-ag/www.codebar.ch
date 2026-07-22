<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Manage my profile')"
            :intro="__('This link is valid for 48 hours and only applies to your own profile. For anything else, please contact codebar.')"/>

    <x-layout.section>
        @if(session('status'))
            <p class="mt-6 max-w-2xl rounded-panel bg-gray-400/10 px-4 py-3 text-gray-800 ring-1 ring-gray-400/20 ring-inset">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ request()->fullUrl() }}" enctype="multipart/form-data" class="mt-6 max-w-md">
            @csrf
            @method('PUT')

            <div class="rounded-panel border border-gray-200 p-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        @if($networkUser->avatar)
                            <img src="{{ \App\Support\CloudinaryUrl::src($networkUser->avatar, 96) }}"
                                 alt="{{ $networkUser->name }}"
                                 class="size-12 shrink-0 rounded-full object-cover">
                        @else
                            <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-brand text-sm font-medium text-white">
                                {{ $networkUser->initials() }}
                            </span>
                        @endif
                        <p class="truncate font-bold text-gray-800">{{ $networkUser->name }}</p>
                    </div>

                    <label for="published" class="flex items-center gap-2 text-sm text-muted">
                        <input type="hidden" name="published" value="0">
                        <input type="checkbox" id="published" name="published" value="1"
                               @checked(old('published', $networkUser->published))
                               class="rounded border-gray-300">
                        {{ __('Published') }}
                    </label>
                </div>
                @if($networkUser->role)
                    <p class="text-sm text-muted">{{ $networkUser->role }}</p>
                @endif

                <label for="email" class="mt-4 block text-sm font-medium text-gray-800">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $networkUser->email) }}"
                       class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <label for="linkedin" class="mt-4 block text-sm font-medium text-gray-800">LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $networkUser->linkedin) }}"
                       placeholder="https://www.linkedin.com/in/..."
                       class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                @error('linkedin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <label for="phone" class="mt-4 block text-sm font-medium text-gray-800">{{ __('Phone') }}</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $networkUser->phone) }}"
                       placeholder="+41 ..."
                       class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <label for="avatar" class="mt-4 block text-sm font-medium text-gray-800">{{ __('Avatar') }}</label>
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp"
                       class="mt-1 w-full text-sm text-gray-800 file:mr-3 file:rounded-pill file:border-0 file:bg-brand file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-brand-strong">
                <p class="mt-1 text-sm text-muted">{{ __('JPG, PNG or WebP, max. 2 MB — codebar prepares it for the CDN.') }}</p>
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 rounded-panel border border-gray-200 p-4">
                <div class="flex items-center justify-between gap-4">
                    <p class="font-bold text-gray-800">{{ $network?->name ?? $networkUser->network_key }}</p>
                    <p class="text-sm text-muted">{{ __('Visibility of the company is managed by codebar.') }}</p>
                </div>

                <label for="website" class="mt-4 block text-sm font-medium text-gray-800">{{ __('Website') }}</label>
                <input type="url" id="website" name="website" value="{{ old('website', $network?->website) }}"
                       placeholder="https://..."
                       class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-ui.button :label="__('Save changes')"/>
            </div>
        </form>
    </x-layout.section>
</x-app-layout>
