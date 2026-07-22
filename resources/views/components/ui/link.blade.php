@props(['href', 'label' => null, 'target' => '_self', 'title' => null, 'download' => null])

<a href="{{ $href }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   @if(filled($title)) title="{{ $title }}" @endif
   @if(filled($download)) download="{{ $download }}" @endif
   {{ $attributes->merge(['class' => 'hover:text-brand hover:font-semibold transition']) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</a>
