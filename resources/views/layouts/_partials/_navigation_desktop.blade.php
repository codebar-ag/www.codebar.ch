{{-- One list, driven by the same PageNavigation source the explore grid and the
     next-page chain use, so the menu can never drift out of step with them.
     Technologies stays out of the menu — it is reachable from the pages that
     reference it. --}}
<div class="hidden w-full justify-between md:flex">
    <div class="flex items-center gap-2">
        @foreach(\App\Support\PageNavigation::pages() as $item)
            <x-nav.link :route="$item['route']" :label="$item['label']"/>

            @if(! $loop->last)
                <span class="text-gray-300" aria-hidden="true">|</span>
            @endif
        @endforeach
    </div>
</div>
