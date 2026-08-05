<div class="bg-gradient-to-br from-zunscan-light-blue to-zunscan-blue relative">
    {{ $cta ?? '' }}
    <img class="bottom-0 left-40 w-1/5 max-h-[350px] hidden sm:absolute"
         src="{{ asset('images/zunscan/zunscan_paperclip_half.svg') }}" alt="">
    <div class="relative mx-auto max-w-5xl px-6 z-10">
        <div class="py-24">
            {{ $slot }}
        </div>
    </div>
</div>
