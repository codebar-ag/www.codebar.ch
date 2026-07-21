@props(['id' => null])

<div @if($id) id="{{ $id }}" @endif class="border border-gray-200 rounded-xl bg-gray-50 px-6 pt-6 pb-4 mt-8">
    {{ $slot }}
</div>
