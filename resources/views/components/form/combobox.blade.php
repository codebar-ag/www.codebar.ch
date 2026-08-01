@props(['name', 'label', 'value' => null, 'placeholder' => null, 'options' => []])

@php $listboxId = 'combobox-' . $name . '-listbox'; @endphp

<div class="block w-full" x-data="combobox" x-on:click.outside="close">
    <label class="block">
        <span class="block text-sm font-medium text-gray-800">{{ $label }}</span>
        <span class="relative mt-1.5 block">
            <x-form.input type="text" :name="$name" :value="$value" :placeholder="$placeholder"
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
                          class="pr-20"/>
            <button type="button" x-on:click="clear" x-show="hasValue" x-cloak
                    class="absolute right-10 top-1/2 -translate-y-1/2 icon-button text-muted transition hover:text-gray-800 focus-ring"
                    aria-label="{{ __('Clear') }}">
                <x-icon.close/>
            </button>
            <x-icon.chevron-down class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-muted"/>
        </span>
    </label>
    <div class="relative">
        <ul x-ref="list" x-show="isOpen" x-cloak
            id="{{ $listboxId }}" role="listbox" aria-label="{{ $label }}"
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-panel border border-border bg-white py-1 text-base shadow-pop">
            @foreach ($options as $option)
                <li x-on:click="select" data-value="{{ $option }}"
                    id="combobox-{{ $name }}-option-{{ $loop->index }}" role="option" aria-selected="false"
                    class="flex min-h-control cursor-pointer items-center px-4 text-gray-800 hover:bg-gray-100">{{ $option }}</li>
            @endforeach
        </ul>
    </div>
</div>
