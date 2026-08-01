@props(['models', 'caption' => null])

<x-ui.panel variant="plain" class="p-3 sm:p-4">
    <x-ui.table :caption="$caption">
        <thead>
        <x-ui.table.row variant="head">
            <x-ui.table.cell as="th" scope="col">{{ __('components.ai_llm.archive.columns.model') }}</x-ui.table.cell>
            <x-ui.table.cell as="th" scope="col">{{ __('components.ai_llm.archive.columns.replaced_by') }}</x-ui.table.cell>
        </x-ui.table.row>
        </thead>
        <tbody>
        @foreach ($models as $model)
            <x-ui.table.row>
                <x-ui.table.cell class="font-medium">{{ $model->name }}</x-ui.table.cell>
                <x-ui.table.cell class="text-muted">{{ $model->replacedBy?->name ?? '—' }}</x-ui.table.cell>
            </x-ui.table.row>
        @endforeach
        </tbody>
    </x-ui.table>
</x-ui.panel>
