@props(['message' => null])

@if(filled($message))
    <div x-data="toast" x-show="visible" role="status"
         class="pointer-events-none fixed inset-x-0 bottom-6 z-50 flex justify-center px-4">
        <div class="pointer-events-auto flex max-w-4xl items-center gap-3 rounded-panel bg-green-50 px-4 py-3 text-green-900 shadow-pop ring-1 ring-inset ring-green-600/20 sm:px-6">
            <p class="text-sm sm:text-lg">{!! $message !!}</p>
            <button type="button" x-on:click="close" aria-label="{{ __('Close') }}"
                    class="focus-ring -mr-1 flex size-8 shrink-0 cursor-pointer items-center justify-center rounded-pill text-green-900/70 transition hover:bg-green-600/10 hover:text-green-900">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
