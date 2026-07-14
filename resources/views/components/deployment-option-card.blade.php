@props(['title', 'description'])

<div class="flex flex-col gap-2 p-5 rounded-xl ring-1 ring-gray-200">
    <div class="font-semibold text-gray-800">
        {{ $title }}
    </div>
    <div class="text-gray-600">
        {{ $description }}
    </div>
</div>
