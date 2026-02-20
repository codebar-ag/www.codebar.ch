<x-app-layout :page="$page">
    <x-h1 :title="__('Open Source')"/>

    <x-section>
        <div x-data="repositorySearch({{ $openSourceJson }})">
            <div class="mb-6">
                <input type="text"
                       x-model="search"
                       @input="updateUrl"
                       placeholder="{{ __('Search repositories...') }}"
                       class="w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 focus:border-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-500"/>
            </div>

            <div class="flex flex-col divide-y divide-gray-100">
                <template x-for="item in filteredItems" x-bind:key="item.url">
                    <a x-bind:href="item.url"
                       class="group block p-4 transition hover:bg-gray-50/50 hover:shadow-sm rounded min-h-[120px]">
                        <div class="flex flex-col gap-1">
                            <div class="font-semibold text-gray-800 group-hover:text-primary" x-text="item.title"></div>
                            <div class="text-gray-600" x-text="item.teaser"></div>
                            <div class="mt-1 hidden md:flex flex-wrap gap-2">
                                <template x-for="tag in item.tags" x-bind:key="tag">
                                    <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-gray-400/20 ring-inset"
                                          x-text="tag"></span>
                                </template>
                            </div>
                        </div>
                    </a>
                </template>
            </div>

            <div x-show="hasResults" x-cloak class="hidden"></div>
            <div x-show="!hasResults" x-cloak class="p-4 text-gray-500">
                {{ __('No repositories found.') }}
            </div>
        </div>
    </x-section>
</x-app-layout>
