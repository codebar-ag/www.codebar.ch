@props([
    'title' => null,
    'teaser' => null,
    'email' => null,
    'phone' => null,
    'contactRoute' => true,
    'contactLabel' => null,
    'subject' => null,
    'tone' => 'dark',
    'align' => 'center',
])

@php
    $title ??= __('Get in touch');
    $email ??= config('site.contact.email');
    $phone ??= config('site.contact.phone');
    $phoneTel = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
    $contactLabel ??= __('Open contact page');

    $mailto = $email ? 'mailto:'.$email.($subject ? '?subject='.rawurlencode($subject) : '') : null;

    $contactHref = null;
    if ($contactRoute) {
        $base = localized_route('contact.index');
        $contactHref = $base.($subject ? '?subject='.rawurlencode($subject) : '');
    }

    $toneClasses = match ($tone) {
        'light' => 'bg-zinc-50 text-zinc-950',
        'brand' => 'bg-brand text-white',
        default => 'bg-zinc-950 text-zinc-100',
    };

    $titleClasses = match ($tone) {
        'light', 'muted' => 'text-zinc-950',
        default => 'text-white',
    };

    $teaserClasses = match ($tone) {
        'light', 'muted' => 'text-zinc-600',
        'brand' => 'text-white/80',
        default => 'text-zinc-300',
    };

    $primaryVariant = match ($tone) {
        'light', 'muted' => 'primary',
        'brand' => 'secondary',
        default => 'brand',
    };

    $secondaryVariant = match ($tone) {
        default => 'secondary',
    };

    $isLeft = $align === 'left';
    $contentWrapperClasses = $isLeft ? 'max-w-3xl' : 'mx-auto max-w-3xl text-center';
    $buttonsRowClasses = $isLeft ? 'mt-8 flex flex-wrap justify-start gap-3' : 'mt-8 flex flex-wrap justify-center gap-3';
@endphp

<section {{ $attributes->merge(['class' => "relative {$toneClasses}"]) }}>
    <div class="mx-auto max-w-6xl px-6 py-16 md:py-20 lg:px-8 lg:py-24">
        <div class="{{ $contentWrapperClasses }}">
            <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-balance {{ $titleClasses }}">{{ $title }}</h2>
            @if(filled($teaser))
                <p class="mt-4 text-base leading-relaxed md:text-lg {{ $teaserClasses }}">{{ $teaser }}</p>
            @endif

            <div class="{{ $buttonsRowClasses }}">
                @if($mailto)
                    <x-ui.button :href="$mailto" :variant="$primaryVariant" size="lg" :label="__('Email us')" />
                @endif
                @if($phoneTel)
                    <x-ui.button :href="'tel:'.$phoneTel" :variant="$secondaryVariant" size="lg" :label="$phone" />
                @endif
                @if($contactHref)
                    <x-ui.button :href="$contactHref" :variant="$secondaryVariant" size="lg" :label="$contactLabel" />
                @endif
            </div>
        </div>
    </div>
</section>
