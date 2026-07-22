@props(['title', 'description'])

<div class="flex flex-col gap-2">
    <x-h3 :title="$title"/>
    <p class="text-gray-600">
        {{ $description }}
    </p>
</div>
