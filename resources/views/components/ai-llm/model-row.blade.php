@props(['model'])

<div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-3 sm:gap-6 sm:items-center border-t border-gray-200 py-4 px-2">
    <div class="flex flex-col gap-2">
        <div class="font-semibold text-lg text-gray-800">{{ $model->name }}</div>
        <div class="text-gray-600">{{ $model->localizedRole() }}</div>
        <div class="flex flex-wrap gap-2">
            <x-badge :label="$model->provider" :title="__('components.ai_llm.tooltips.provider')" class-attributes="text-xs"/>
            <x-badge :label="$model->ram" :title="__('components.ai_llm.tooltips.ram')" class-attributes="text-xs"/>
            <x-badge :label="$model->licenseLabel()" :title="$model->licenseTooltip()" class-attributes="text-xs"/>
        </div>
    </div>
    <div class="sm:justify-self-end">
        <x-a-badge
                :href="$model->link_url"
                :label="$model->link_label.' ↗'"
                target="_blank"
                :title="__('components.ai_llm.tooltips.link')"
                variant="brand"
                class-attributes="text-xs whitespace-nowrap"/>
    </div>
</div>
