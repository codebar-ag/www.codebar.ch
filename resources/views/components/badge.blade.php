@props(['label','title' => '','classAttributes' => ""])

<span title="{{ $title ?? $label }}"
      class="{{ $classAttributes }} inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-sm font-medium text-gray-600 ring-1 ring-gray-400/20 ring-inset">
    {{ $label }}
</span>
