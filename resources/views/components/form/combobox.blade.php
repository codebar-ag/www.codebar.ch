@props(['name', 'label', 'value' => null, 'placeholder' => null, 'options' => []])

@php $listboxId = 'combobox-' . $name . '-listbox'; @endphp

<div class="block w-full" x-data="combobox" x-on:click.outside="close">
    <label class="block">
        <span class="text-xs font-medium text-gray-500">{{ $label }}</span>
        <span class="relative mt-1 block">
            <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
                   autocomplete="off" data-1p-ignore data-lpignore="true" data-form-type="other"
                   role="combobox" aria-autocomplete="list" aria-controls="{{ $listboxId }}"
                   x-bind:aria-expanded="aria_expanded"
                   x-ref="input" x-on:focus="open" x-on:input="filter"
                   x-on:keydown.escape="close"
                   x-on:keydown.down.prevent="highlightNext"
                   x-on:keydown.up.prevent="highlightPrevious"
                   x-on:keydown.home.prevent="highlightFirst"
                   x-on:keydown.end.prevent="highlightLast"
                   x-on:keydown.enter.prevent="selectActive"
                   x-on:keydown.tab="close"
                   class="block w-full rounded-pill border border-gray-300 bg-white py-2 pl-3 pr-16 text-sm text-gray-800 placeholder-gray-500 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand"/>
            <button type="button" x-on:click="clear" x-show="hasValue" x-cloak
                    class="absolute right-8 top-1/2 -translate-y-1/2 p-1 text-gray-500 hover:text-muted"
                    aria-label="{{ __('Clear') }}">
                <x-icon.close/>
            </button>
            <x-icon.chevron-down class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 size-4 text-gray-500"/>
        </span>
    </label>
    <div class="relative">
        <ul x-ref="list" x-show="isOpen" x-cloak
            id="{{ $listboxId }}" role="listbox" aria-label="{{ $label }}"
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-pill border border-border bg-white py-1 text-sm shadow-lg">
            @foreach ($options as $option)
                <li x-on:click="select" data-value="{{ $option }}"
                    id="combobox-{{ $name }}-option-{{ $loop->index }}" role="option" aria-selected="false"
                    class="cursor-pointer px-3 py-2 text-gray-800 hover:bg-gray-100">{{ $option }}</li>
            @endforeach
        </ul>
    </div>
</div>
