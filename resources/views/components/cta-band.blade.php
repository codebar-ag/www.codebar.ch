@props(['title', 'body', 'buttonLabel' => null, 'buttonHref' => null])

<div class="mt-6" style="--brand: var(--color-brand);">
    <x-section class-attributes="relative isolate bg-gray-100 overflow-hidden">
        <div
            class="absolute -top-32 -left-20 -z-10 h-[30rem] w-[30rem] rounded-full bg-(--brand) opacity-10 blur-[120px]">
        </div>
        <section class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 p-6 md:p-12">
            <div class="w-full">
                <x-h2 :title="$title"/>
                <p class="mb-6">
                    {{ $body }}
                </p>
                @if($buttonLabel && $buttonHref)
                    <div class="flex flex-col sm:flex-row gap-2 text-center">
                        <x-button variant="primary" :href="$buttonHref" :label="$buttonLabel"/>
                    </div>
                @endif
            </div>
        </section>
    </x-section>
</div>
