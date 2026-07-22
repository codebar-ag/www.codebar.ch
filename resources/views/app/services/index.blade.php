<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Services')" :page="$page"/>

    <x-layout.section>
        <x-layout.list>
            @foreach($services as $entry)
                <div class="py-4">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ $entry->name }}
                        </h3>

                        @if(filled($entry->teaser))
                            <div class="text-muted">
                                {{ $entry->teaser }}
                            </div>
                        @endif

                        <x-data.tag-list :tags="$entry->tags" class="mt-1"/>
                    </div>
                </div>
            @endforeach
        </x-layout.list>
    </x-layout.section>

</x-app-layout>
