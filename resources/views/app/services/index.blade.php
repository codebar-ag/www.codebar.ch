<x-app-layout :page="$page" :schema="$schema">
    <x-layout.page-header :title="__('Services')" :intro="__('components.services.header')"/>

    <x-layout.section>
        {{-- Not x-card.item-card: this list alternates the side its illustration sits on and
             lets it leave the page frame, which has no business being imposed on products,
             technologies or open source. It is the same row the news lists use, though —
             x-illustration-row owns the drawing's size, how far out it sits and the rhythm
             between rows, so a services row and a news row cannot drift apart. --}}
        @foreach($services as $entry)
            {{-- Found by convention, like the partner drawings on /network: the row simply
                 loses its illustration when the file is missing, and nothing needs
                 registering. See prompts/illustration-services.md. --}}
            @php
                $card = 'images/services/'.$entry->slug.'-card.svg';
                $card = file_exists(public_path($card)) ? asset($card) : null;
            @endphp

            <x-illustration-row :illustration="$card" :side="$loop->odd ? 'right' : 'left'">
                <span aria-hidden="true" class="block h-1.5 w-10 rounded-pill bg-brand sm:w-12"></span>
                {{-- One token, no sm: step. --text-heading is already fluid between
                     20 and 24px, so the breakpoint was doing the token's job twice
                     and starting a size lower than every other h2 on the site. --}}
                <h2 class="mt-3 text-heading font-semibold text-balance text-gray-900 sm:mt-4">{{ $entry->name }}</h2>
                <p class="mt-3 text-muted">{{ $entry->teaser }}</p>
                <x-data.tag-list :tags="$entry->tags" class="mt-4"/>

                @if($entry->slug === 'dms-ecm-consulting')
                    <x-ui.arrow-link
                            :href="localized_route('services.dms-ecm.index')"
                            :label="__('components.docuware.dms_ecm.title')"
                            class="mt-4"/>
                @endif
            </x-illustration-row>
        @endforeach
    </x-layout.section>

</x-app-layout>
