@props(['models'])

<div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
    <div class="hidden sm:grid grid-cols-[1fr_1.5fr] gap-4 text-sm font-medium text-gray-500">
        <div>{{ __('components.ai_llm.archive.columns.model') }}</div>
        <div>{{ __('components.ai_llm.archive.columns.replaced_by') }}</div>
    </div>

    @foreach ($models as $model)
        <x-ai-llm.archive-row :model="$model"/>
    @endforeach
</div>
