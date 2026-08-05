@props([
    'name',
    'company',
    'website',
    'websiteLabel',
    'email',
    'phone',
    'linkedin' => null,
    'image' => null,
])

{{-- The main site's x-card.person-card is deliberately not reused here: it is
     bound to that site's tokens, and pulling it in would put Poppins and the
     brand purple into this bundle. CloudinaryUrl is fine to share — it is a URL
     builder with no design opinion. --}}
<x-zunscan.components.card class="flex flex-col gap-5 sm:flex-row sm:items-start">
    @if($image)
        <img class="size-24 shrink-0 rounded-card object-cover"
             src="{{ \App\Support\CloudinaryUrl::src($image, 96) }}"
             srcset="{{ \App\Support\CloudinaryUrl::srcset($image, 96) }}"
             sizes="96px"
             width="96" height="96" loading="lazy" decoding="async"
             alt="{{ $name }}">
    @endif

    <div class="min-w-0">
        <p class="text-heading text-zunscan-dark-gray">{{ $name }}</p>
        <p class="text-zunscan-light-gray">{{ $company }}</p>

        <a href="{{ $website }}" target="_blank" rel="noopener"
           class="break-all text-zunscan-blue hover:underline">{{ $websiteLabel }}</a>

        {{-- Channels as icons rather than repeated blue text lines: the address
             itself is not the point, reaching the person is. Each carries an
             accessible name, since an icon alone is not a label. --}}
        <div class="mt-4 flex items-center gap-1">
            <a href="mailto:{{ $email }}" title="{{ $email }}"
               class="grid size-control place-items-center rounded-card text-zunscan-blue transition hover:bg-zunscan-white">
                <span class="sr-only">{{ __('zunscan.contact.write_to', ['name' => $name]) }}</span>
                <x-zunscan.components.icon name="mail" class="h-5 w-5 text-zunscan-blue"/>
            </a>

            <a href="tel:{{ str_replace(' ', '', $phone) }}" title="{{ $phone }}"
               class="grid size-control place-items-center rounded-card text-zunscan-blue transition hover:bg-zunscan-white">
                <span class="sr-only">{{ __('zunscan.contact.call', ['name' => $name]) }}</span>
                <x-zunscan.components.icon name="phone" class="h-5 w-5 text-zunscan-blue"/>
            </a>

            @if($linkedin)
                <a href="{{ $linkedin }}" target="_blank" rel="noopener"
                   class="grid size-control place-items-center rounded-card text-zunscan-blue transition hover:bg-zunscan-white">
                    <span class="sr-only">{{ __('zunscan.contact.linkedin_of', ['name' => $name]) }}</span>
                    <x-zunscan.components.icon name="linkedin"/>
                </a>
            @endif
        </div>
    </div>
</x-zunscan.components.card>
