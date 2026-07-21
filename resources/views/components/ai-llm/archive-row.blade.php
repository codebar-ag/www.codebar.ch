@props(['model'])

<div class="grid grid-cols-1 sm:grid-cols-[1fr_1.5fr] gap-1 sm:gap-4 border-t border-gray-100 py-2 text-sm">
    <div class="text-gray-800 font-medium">{{ $model->name }}</div>
    <div class="text-gray-600">
        <span class="sm:hidden">{{ __('components.ai_llm.archive.columns.replaced_by') }}: </span>{{ $model->replacedBy?->name ?? '—' }}
    </div>
</div>
