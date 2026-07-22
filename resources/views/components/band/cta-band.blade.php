@props(['title', 'body', 'brandColor' => null])

<div class="mt-6" @if(filled($brandColor)) style="--color-brand: {{ $brandColor }};" @endif>
    <x-layout.section class="relative isolate bg-gray-100 overflow-hidden">
        <div aria-hidden="true"
             class="absolute -top-32 -left-20 -z-10 h-[30rem] w-[30rem] rounded-full bg-brand opacity-10 blur-[120px]">
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 p-6 md:p-12">
            <div class="w-full">
                <x-h2 :title="$title"/>
                <p class="mb-6">
                    {{ $body }}
                </p>
                @if(!$slot->isEmpty())
                    <div class="flex flex-col sm:flex-row gap-2 text-center">
                        {{ $slot }}
                    </div>
                @endif
            </div>
        </div>
    </x-layout.section>
</div>
