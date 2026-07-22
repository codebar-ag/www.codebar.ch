@props(['models', 'caption' => null])

<x-ui.panel variant="plain" class="p-3 sm:p-4 overflow-x-auto">
    <table class="w-full text-sm">
        @if(filled($caption))
            <caption class="sr-only">{{ $caption }}</caption>
        @endif
        <thead>
        <tr class="text-left text-gray-500">
            <th scope="col" class="pb-2 pr-4 font-medium">{{ __('components.ai_llm.archive.columns.model') }}</th>
            <th scope="col" class="pb-2 font-medium">{{ __('components.ai_llm.archive.columns.replaced_by') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($models as $model)
            <tr class="border-t border-border-soft">
                <td class="py-2 pr-4 font-medium text-gray-800">{{ $model->name }}</td>
                <td class="py-2 text-muted">{{ $model->replacedBy?->name ?? '—' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-ui.panel>
