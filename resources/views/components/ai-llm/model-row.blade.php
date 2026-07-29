@props(['model'])

<x-ui.row class="sm:grid-cols-[1fr_auto] sm:items-center">
    <div class="flex flex-col gap-2">
        <div class="font-semibold text-lg text-gray-800">{{ $model->name }}</div>
        <div class="text-muted">{{ $model->localizedRole() }}</div>
        <div class="flex flex-wrap gap-2">
            <x-ui.badge :label="$model->provider" :title="__('components.ai_llm.tooltips.provider')" size="xs"/>
            <x-ui.badge :label="$model->ram" :title="__('components.ai_llm.tooltips.ram')" size="xs"/>
            <x-ui.badge :label="$model->licenseLabel()" :title="$model->licenseTooltip()" size="xs"/>
        </div>
    </div>
    <div class="sm:justify-self-end">
        <x-ui.badge-link
                :href="$model->link_url"
                :label="$model->link_label"
                target="_blank"
                :title="__('components.ai_llm.tooltips.link')"
                variant="brand"
                class="whitespace-nowrap">
            <x-icon.external-link class="ml-1 size-3"/>
        </x-ui.badge-link>
    </div>
</x-ui.row>
