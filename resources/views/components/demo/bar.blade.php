@props(['href', 'label', 'title' => null, 'inset' => false])

{{-- The «back to all variants» strip above a demo page. Shared by the standalone
     prototype shell and the variants that render inside the real app layout; the
     latter pass inset so the bar can break out of the page frame. --}}
<div @class([
    'flex items-center justify-between gap-4 bg-zinc-950 px-4 py-2 text-white sm:px-6 lg:px-8',
    'sticky top-0 z-50 text-sm' => ! $inset,
    '-mx-4 mb-6 text-xs sm:-mx-6 lg:-mx-8' => $inset,
])>
    <a href="{{ $href }}" class="font-semibold transition hover:text-white/70 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
        ← {{ $label }}
    </a>
    <span class="truncate text-white/50">{{ $title }}</span>
</div>
