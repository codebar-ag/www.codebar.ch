@props(['name', 'label', 'value' => null, 'placeholder' => null, 'options' => []])

<div class="block w-full" x-data="combobox" x-on:click.outside="close">
    <label class="block">
        <span class="text-xs font-medium text-gray-500">{{ $label }}</span>
        <span class="relative mt-1 block">
            <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
                   autocomplete="off" data-1p-ignore data-lpignore="true" data-form-type="other"
                   x-ref="input" x-on:focus="open" x-on:input="filter" x-on:keydown.escape="close"
                   class="block w-full rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-16 text-sm text-gray-800 placeholder-gray-400 focus:border-(--brand) focus:outline-none focus:ring-1 focus:ring-(--brand)"/>
            <button type="button" x-on:click="clear" x-show="hasValue" x-cloak
                    class="absolute right-8 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600"
                    aria-label="×">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                 fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
            </svg>
        </span>
    </label>
    <div class="relative">
        <ul x-ref="list" x-show="isOpen" x-cloak
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-gray-200 bg-white py-1 text-sm shadow-lg">
            @foreach ($options as $option)
                <li x-on:click="select" data-value="{{ $option }}"
                    class="cursor-pointer px-3 py-2 text-gray-800 hover:bg-gray-100">{{ $option }}</li>
            @endforeach
        </ul>
    </div>
</div>
