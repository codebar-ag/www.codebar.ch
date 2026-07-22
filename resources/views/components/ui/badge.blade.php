@props(['label' => null, 'title' => null])

<span @if(filled($title)) title="{{ $title }}" @endif
      {{ $attributes->merge(['class' => 'inline-flex items-center rounded-pill bg-gray-400/10 px-2 py-1 text-sm font-medium text-muted ring-1 ring-gray-400/20 ring-inset']) }}>
    {{ $label }}
    {{ $slot }}
</span>
