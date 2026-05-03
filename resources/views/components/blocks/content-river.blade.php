@props(['classAttributes' => ''])

<section class="editorial-shell ui-region {{ $classAttributes }}">
    <div class="@isset($aside) grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] @else grid gap-8 @endisset">
        <div class="min-w-0">
            {{ $slot }}
        </div>
        @isset($aside)
            <aside class="lg:pt-1">
                {{ $aside }}
            </aside>
        @endisset
    </div>
</section>
