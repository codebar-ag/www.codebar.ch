@props([
    'company' => null,
    'email' => null,
    'phone' => null,
    'offices' => null,
])

@php
    $company ??= config('site.company');
    $email ??= config('site.contact.email');
    $phone ??= config('site.contact.phone');
    $offices ??= config('site.contact.offices', []);
    $phoneTel = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
@endphp

<x-ui.section>
    <div class="grid gap-x-12 gap-y-12 md:grid-cols-2">
        <div>
            <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Phone') }}</p>
            <p class="mt-4 text-3xl font-semibold tracking-tight text-zinc-950 md:text-4xl">{{ $phone }}</p>
            @if($phoneTel)
                <a href="tel:{{ $phoneTel }}" class="mt-3 inline-flex text-sm text-zinc-600 underline decoration-zinc-300 underline-offset-4 hover:text-zinc-950 hover:decoration-zinc-950">
                    {{ __('Call us') }}
                </a>
            @endif
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Email') }}</p>
            <p class="mt-4 text-3xl font-semibold tracking-tight text-zinc-950 break-all md:text-4xl">{{ $email }}</p>
            @if($email)
                <a href="mailto:{{ $email }}" class="mt-3 inline-flex text-sm text-zinc-600 underline decoration-zinc-300 underline-offset-4 hover:text-zinc-950 hover:decoration-zinc-950">
                    {{ __('Send email') }}
                </a>
            @endif
        </div>
    </div>

    @if(!empty($offices))
        <div class="mt-16 grid gap-x-12 gap-y-12 md:grid-cols-2">
            @foreach($offices as $office)
                @php
                    $eyebrow = ($office['kind'] ?? null) === 'headquarter' ? __('Headquarter') : __('Branch office');
                    $label = trim(sprintf('%s · %s, %s %s', $company, $office['street'] ?? '', $office['postal_code'] ?? '', $office['city'] ?? ''));
                @endphp
                <div>
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ $eyebrow }}</p>
                    <address class="mt-4 not-italic leading-relaxed">
                        <p class="text-xl font-semibold text-zinc-950 md:text-2xl">{{ $company }}</p>
                        <p class="text-lg text-zinc-700 md:text-xl">{{ $office['street'] ?? '' }}</p>
                        <p class="text-lg text-zinc-700 md:text-xl">{{ ($office['postal_code'] ?? '') }} {{ $office['city'] ?? '' }}</p>
                    </address>

                    @if(isset($office['lat'], $office['lng']))
                        <div
                            data-leaflet-map
                            data-lat="{{ $office['lat'] }}"
                            data-lng="{{ $office['lng'] }}"
                            data-label="{{ $label }}"
                            class="mt-6 h-72 w-full overflow-hidden rounded-xl border border-zinc-200 bg-zinc-100 md:h-80"
                            aria-label="{{ __('Map of :address', ['address' => $label]) }}"
                        ></div>
                    @endif

                    @if(!empty($office['maps_url']))
                        <a
                            href="{{ $office['maps_url'] }}"
                            target="_blank"
                            rel="noopener"
                            class="mt-4 inline-flex items-center gap-1 text-sm text-zinc-600 underline decoration-zinc-300 underline-offset-4 hover:text-zinc-950 hover:decoration-zinc-950"
                        >
                            {{ __('Open in Google Maps') }}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 1 1.5 0v4A2.25 2.25 0 0 1 12.75 17h-8.5A2.25 2.25 0 0 1 2 14.75v-8.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 0 0 1.06.053L16.5 4.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-9.056 8.194a.75.75 0 0 0-.053 1.06z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-ui.section>
