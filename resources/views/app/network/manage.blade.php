<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Manage my profile')"
            :intro="__('Use this link to update your own profile. For anything else, please contact codebar.')"
            :breadcrumbs="[
                ['label' => __('Network'), 'url' => localized_route('network.index')],
                ['label' => __('Manage my profile')],
            ]"/>

    <x-layout.section>
        @if(session('status'))
            <x-ui.alert>{{ session('status') }}</x-ui.alert>
        @endif

        <p class="mt-section text-sm text-muted" title="{{ __('Contact codebar to change your email address.') }}">
            {{ __('Assigned email') }}: {{ $networkUser->email }}
        </p>

        <form method="POST" action="{{ request()->fullUrl() }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')

            <x-ui.panel class="space-y-6 p-6">
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

                <x-form.field name="published" :label="__('Published')"
                              :help="__('Visible in the public network directory.')">
                    {{-- The whole row is the target, so the 44px comes from the label. --}}
                    <label for="published" class="inline-flex min-h-control cursor-pointer items-center gap-2">
                        <input type="hidden" name="published" value="0">
                        <input type="checkbox" id="published" name="published" value="1"
                               @checked(old('published', $networkUser->published))
                               class="size-5 rounded-pill border-border-strong text-brand focus:ring-brand">
                        <x-ui.badge :label="old('published', $networkUser->published) ? __('Published') : __('Not published')"/>
                    </label>
                </x-form.field>

                <x-form.field name="public_email" :label="__('Public email')"
                              :help="__('Shown publicly on the network page. Leave empty to hide it.')">
                    <x-form.input name="public_email" type="email" :value="$networkUser->public_email"
                                  :placeholder="$networkUser->email" described-by="public_email-help"/>
                </x-form.field>

                <x-form.field name="name" :label="__('Name')">
                    <x-form.input name="name" :value="$networkUser->name"/>
                </x-form.field>

                <x-form.field name="linkedin" label="LinkedIn">
                    <x-form.input name="linkedin" type="url" :value="$networkUser->linkedin"
                                  placeholder="https://www.linkedin.com/in/..."/>
                </x-form.field>

                <x-form.field name="phone" :label="__('Phone')">
                    <x-form.input name="phone" :value="$networkUser->phone" placeholder="+41 ..."/>
                </x-form.field>

                <div class="space-y-6 border-t border-border-soft pt-6">
                    <x-h3 :title="__('Avatar')"/>

                    {{-- Read-only, so no <label>: there is no control to label. --}}
                    <div class="space-y-1.5">
                        <p class="text-sm font-medium text-gray-800">{{ __('Avatar URL') }}</p>
                        @if($networkUser->avatar_url)
                            <x-ui.link :href="$networkUser->avatar_url" target="_blank"
                                       class="block truncate text-sm text-brand underline">{{ $networkUser->avatar_url }}</x-ui.link>
                        @else
                            <p class="text-sm text-muted">—</p>
                        @endif
                        <p class="text-sm text-muted">{{ __('Set by codebar. Empty = Gravatar.') }}</p>
                    </div>

                    @if($networkUser->avatar_path)
                        <p class="text-sm text-muted">{{ __('The avatar is a raw upload — please convert it to Cloudinary.') }}</p>
                    @else
                        <x-form.field name="avatar" :label="__('Upload')">
                            <x-form.file name="avatar" accept="image/jpeg,image/png,image/webp,image/avif"/>

                            <x-slot:help>
                                {{ __('JPG, PNG, WebP or AVIF, 1:1, max. 2 MB.') }}
                                <x-ui.link :href="asset('images/templates/avatar-template.jpg')" download="avatar-template.jpg"
                                           class="text-brand underline">{{ __('Download template (JPG)') }}</x-ui.link>
                            </x-slot:help>
                        </x-form.field>
                    @endif
                </div>
            </x-ui.panel>

            <x-ui.panel class="mt-4 space-y-6 p-6">
                <div class="flex items-center justify-between gap-4">
                    <p class="font-bold text-gray-800">{{ $network?->name ?? $networkUser->network_key }}</p>
                    <p class="text-sm text-muted">{{ __('Visibility of the company is managed by codebar.') }}</p>
                </div>

                <x-form.field name="website" :label="__('Website')">
                    <x-form.input name="website" type="url" :value="$network?->website" placeholder="https://..."/>
                </x-form.field>

                <div class="space-y-6 border-t border-border-soft pt-6">
                    <x-h3 :title="__('Company image')"/>

                    {{-- Read-only, so no <label>: there is no control to label. --}}
                    <div class="space-y-1.5">
                        <p class="text-sm font-medium text-gray-800">{{ __('Cover URL') }}</p>
                        @if($network?->cover_url)
                            <div class="flex h-20 items-center rounded-panel border border-border bg-surface px-4">
                                <img src="{{ $network->cover_url }}" alt="{{ $network->name }}" loading="lazy"
                                     class="max-h-14 w-auto">
                            </div>
                            <x-ui.link :href="$network->cover_url" target="_blank"
                                       class="block truncate text-sm text-brand underline">{{ $network->cover_url }}</x-ui.link>
                        @else
                            <p class="text-sm text-muted">—</p>
                        @endif
                        <p class="text-sm text-muted">{{ __('Set by codebar.') }}</p>
                    </div>

                    @if($network?->cover_path)
                        <p class="text-sm text-muted">{{ __('The company image is a raw upload — please convert it to Cloudinary.') }}</p>
                    @else
                        <x-form.field name="cover" :label="__('Upload')">
                            <x-form.file name="cover" accept="image/jpeg,image/png,image/webp,image/avif"/>

                            <x-slot:help>
                                {{ __('JPG, PNG, WebP or AVIF, 3:1, max. 4 MB.') }}
                                <x-ui.link :href="asset('images/templates/cover-template.jpg')" download="cover-template.jpg"
                                           class="text-brand underline">{{ __('Download template (JPG)') }}</x-ui.link>
                            </x-slot:help>
                        </x-form.field>
                    @endif
                </div>
            </x-ui.panel>

            <x-form.actions>
                <x-ui.button :label="__('Save changes')"/>
            </x-form.actions>
        </form>
    </x-layout.section>
</x-app-layout>
