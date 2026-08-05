<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.media.title')" :subtitle="__('zunscan.media.subtitle')"/>

    <x-zunscan.components.section>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <x-zunscan.components.card>
                <img src="https://res.cloudinary.com/codebar/image/upload/w_600,f_auto,q_auto/www-paperflakes-ch/logos/ruyozrdfjlqykodlwabw.png"
                     alt="{{ __('zunscan.media.logo_alt') }}" loading="lazy" decoding="async" class="w-full">

                <a href="https://res.cloudinary.com/codebar/image/upload/www-paperflakes-ch/logos/ruyozrdfjlqykodlwabw.png"
                   download
                   class="mt-4 inline-flex min-h-control items-center gap-2 font-bold text-zunscan-blue hover:underline">
                    {{ __('zunscan.media.download') }}
                </a>
            </x-zunscan.components.card>
        </div>
    </x-zunscan.components.section>
</x-zunscan.layout>
