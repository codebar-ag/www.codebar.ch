@props(['links' => [], 'name' => null, 'titles' => []])

@php
    // One row of contact icons for every card that has people on it. Order comes
    // from this table, not from the caller, so two cards side by side list the same
    // channels in the same sequence.
    $types = [
        'linkedin' => ['icon' => 'icon.linkedin', 'label' => 'LinkedIn', 'external' => true],
        'github' => ['icon' => 'icon.github', 'label' => 'GitHub', 'external' => true],
        'website' => ['icon' => 'icon.website', 'label' => __('Website'), 'external' => true],
        'email' => ['icon' => 'icon.email', 'label' => __('Email'), 'external' => false, 'scheme' => 'mailto:'],
        'phone' => ['icon' => 'icon.phone', 'label' => __('Phone'), 'external' => false, 'scheme' => 'tel:'],
    ];

    $items = collect($types)
        ->map(fn (array $type, string $key) => $type + ['value' => data_get($links, $key)])
        ->filter(fn (array $type) => filled($type['value']));
@endphp

@if($items->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'flex items-center']) }}>
        @foreach($items as $key => $type)
            @php
                $value = $type['value'];
                $href = match ($type['scheme'] ?? null) {
                    'tel:' => 'tel:'.preg_replace('/[^0-9+]/', '', $value),
                    'mailto:' => 'mailto:'.$value,
                    default => $value,
                };
                // «LinkedIn» repeated once per person is useless in a screen reader's
                // link list; the name makes each one identifiable.
                $label = filled($name) ? $type['label'].' — '.$name : $type['label'];

                // Mail and phone show the address itself; a caller can override any
                // entry — the network cards show the bare host rather than «Website».
                $tooltip = data_get($titles, $key) ?? (isset($type['scheme']) ? $value : $type['label']);
            @endphp

            <a href="{{ $href }}"
               @if($type['external']) target="_blank" rel="noopener noreferrer" @endif
               aria-label="{{ $label }}"
               title="{{ $tooltip }}"
               class="grid size-control place-items-center rounded-pill text-muted transition hover:text-gray-800 focus:outline-none focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand sm:size-8">
                <x-dynamic-component :component="$type['icon']"/>
            </a>
        @endforeach
    </div>
@endif
