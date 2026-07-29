@props(['href', 'label' => null, 'target' => '_self', 'title' => null, 'download' => null])

{{-- Colour, not weight, marks the hover: bolding the label on hover re-flowed the
     line and nudged every link beside it in the navigation. --}}
<a href="{{ $href }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   @if(filled($title)) title="{{ $title }}" @endif
   @if(filled($download)) download="{{ $download }}" @endif
   {{ $attributes->merge(['class' => 'rounded-pill transition hover:text-brand focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand']) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</a>
