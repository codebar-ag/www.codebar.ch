@props(['href', 'label' => null, 'target' => '_self', 'title' => null, 'download' => null])

<a href="{{ $href }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   @if(filled($title)) title="{{ $title }}" @endif
   @if(filled($download)) download="{{ $download }}" @endif
   {{ $attributes->merge(['class' => 'rounded-pill transition hover:text-brand focus-ring']) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</a>
