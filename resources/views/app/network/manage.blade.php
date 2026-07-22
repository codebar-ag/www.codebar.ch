<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Manage my profile')"
            :intro="__('Use this link to update your own profile. For anything else, please contact codebar.')"/>

    <x-layout.section>
        @if(session('status'))
            <p class="mt-6 rounded-panel bg-gray-400/10 px-4 py-3 text-gray-800 ring-1 ring-gray-400/20 ring-inset">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ request()->fullUrl() }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')

            <x-ui.panel class="px-6 py-6">
                <div class="flex items-center gap-3">
                    <img src="{{ $networkUser->avatarDisplayUrl(96) }}"
                         alt="{{ $networkUser->name }}"
                         class="size-12 shrink-0 rounded-full bg-gray-100 object-cover">
                    <div class="min-w-0">
                        <p class="truncate font-bold text-gray-800">{{ $networkUser->name }}</p>
                        @if($networkUser->role)
                            <p class="text-sm text-muted">{{ $networkUser->role }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <p class="block text-sm font-medium text-gray-800">{{ __('Published') }}</p>
                    <label for="published" class="mt-1 inline-flex cursor-pointer items-center gap-2">
                        <input type="hidden" name="published" value="0">
                        <input type="checkbox" id="published" name="published" value="1"
                               @checked(old('published', $networkUser->published))
                               class="rounded border-gray-300 text-brand focus:ring-brand">
                        <x-ui.badge :label="old('published', $networkUser->published) ? __('Published') : __('Not published')"/>
                    </label>
                    <p class="mt-1 text-sm text-muted">{{ __('Visible in the public network directory.') }}</p>
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-800">{{ __('Email') }}</label>
                    <input type="email" id="email" value="{{ $networkUser->email }}" disabled
                           class="mt-1 w-full cursor-not-allowed rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-gray-500">
                    <p class="mt-1 text-sm text-muted">{{ __('Contact codebar to change your email address.') }}</p>
                </div>

                <div class="mt-4">
                    <label for="name" class="block text-sm font-medium text-gray-800">{{ __('Name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $networkUser->name) }}"
                           class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="linkedin" class="block text-sm font-medium text-gray-800">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $networkUser->linkedin) }}"
                           placeholder="https://www.linkedin.com/in/..."
                           class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    @error('linkedin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="phone" class="block text-sm font-medium text-gray-800">{{ __('Phone') }}</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $networkUser->phone) }}"
                           placeholder="+41 ..."
                           class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <p class="mt-6 font-bold text-gray-800">{{ __('Avatar') }}</p>

                <div class="mt-2">
                    <p class="block text-sm font-medium text-gray-800">{{ __('Avatar URL') }}</p>
                    @if($networkUser->avatar_url)
                        <a href="{{ $networkUser->avatar_url }}" target="_blank" rel="noopener noreferrer"
                           class="mt-1 block truncate text-sm text-brand underline">{{ $networkUser->avatar_url }}</a>
                    @else
                        <p class="mt-1 text-sm text-muted">—</p>
                    @endif
                    <p class="mt-1 text-sm text-muted">{{ __('Set by codebar. Empty = Gravatar.') }}</p>
                </div>

                <div class="mt-4">
                    <label for="avatar" class="block text-sm font-medium text-gray-800">{{ __('Upload') }}</label>
                    <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp,image/avif"
                           class="mt-1 w-full text-sm text-gray-800 file:mr-3 file:rounded-pill file:border-0 file:bg-brand file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-brand-strong">
                    <p class="mt-1 text-sm text-muted">
                        {{ __('JPG, PNG, WebP or AVIF, 1:1, max. 2 MB.') }}
                        <a href="{{ asset('images/templates/avatar-template.jpg') }}" download
                           class="text-brand underline">{{ __('Download template (JPG)') }}</a>
                    </p>
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </x-ui.panel>

            <x-ui.panel class="mt-4 px-6 py-6">
                <div class="flex items-center justify-between gap-4">
                    <p class="font-bold text-gray-800">{{ $network?->name ?? $networkUser->network_key }}</p>
                    <p class="text-sm text-muted">{{ __('Visibility of the company is managed by codebar.') }}</p>
                </div>

                <div class="mt-4">
                    <label for="website" class="block text-sm font-medium text-gray-800">{{ __('Website') }}</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $network?->website) }}"
                           placeholder="https://..."
                           class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <p class="mt-6 font-bold text-gray-800">{{ __('Company image') }}</p>

                <div class="mt-2">
                    <p class="block text-sm font-medium text-gray-800">{{ __('Cover URL') }}</p>
                    @if($network?->cover_url)
                        <a href="{{ $network->cover_url }}" target="_blank" rel="noopener noreferrer"
                           class="mt-1 block truncate text-sm text-brand underline">{{ $network->cover_url }}</a>
                    @else
                        <p class="mt-1 text-sm text-muted">—</p>
                    @endif
                    <p class="mt-1 text-sm text-muted">{{ __('Set by codebar.') }}</p>
                </div>

                <div class="mt-4">
                    <label for="cover" class="block text-sm font-medium text-gray-800">{{ __('Upload') }}</label>
                    <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp,image/avif"
                           class="mt-1 w-full text-sm text-gray-800 file:mr-3 file:rounded-pill file:border-0 file:bg-brand file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-brand-strong">
                    <p class="mt-1 text-sm text-muted">
                        {{ __('JPG, PNG, WebP or AVIF, 3:1, max. 4 MB.') }}
                        <a href="{{ asset('images/templates/cover-template.jpg') }}" download
                           class="text-brand underline">{{ __('Download template (JPG)') }}</a>
                    </p>
                    @error('cover')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </x-ui.panel>

            <div class="mt-4 flex justify-end">
                <x-ui.button :label="__('Save changes')"/>
            </div>
        </form>
    </x-layout.section>
</x-app-layout>
