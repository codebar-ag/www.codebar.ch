<x-app-layout :page="$page">
    {{-- Hero --------------------------------------------------------- --}}
    <x-ui.hero-home
        :eyebrow="__('codebar Solutions AG')"
        :title="__('Software, crafted for the work you actually do.')"
        :teaser="__('We concept, engineer and scale digital products with editorial clarity and Swiss-grade craft. No theatrics. Just dependable software.')"
    >
        <x-ui.button :href="localized_route('services.index')" :label="__('Explore services')" variant="primary" />
        <x-ui.button :href="localized_route('contact.index')" :label="__('Start a project')" variant="secondary" />
    </x-ui.hero-home>

    {{-- Who we are (intro from config) ------------------------------ --}}
    <x-intro />

    {{-- What we do (capabilities) ------------------------------------ --}}
    <x-ui.section tone="muted">
        <x-ui.section-header
            :eyebrow="__('What we do')"
            :title="__('Three disciplines, one team.')"
            :teaser="__('Strategy, design and engineering live under the same roof. Decisions move in hours, not weeks.')"
        />

        <div class="mt-16">
            <x-ui.feature-row>
                <x-ui.feature
                    :eyebrow="__('Engineering')"
                    :title="__('Laravel & TypeScript at the core')"
                    :teaser="__('We build resilient backends in Laravel, ship typed frontends in TypeScript, and keep both honest with tests and CI from day one.')"
                />
                <x-ui.feature
                    :eyebrow="__('Design')"
                    :title="__('Editorial, opinionated, restrained')"
                    :teaser="__('Type-led interfaces that put your content first. Fewer ornaments, sharper hierarchy, decisions you can defend.')"
                />
                <x-ui.feature
                    :eyebrow="__('Operations')"
                    :title="__('DocuWare, integrations, automation')"
                    :teaser="__('Document workflows, ERP sync and reporting — wired together so the team in the back office stops copying things by hand.')"
                />
            </x-ui.feature-row>
        </div>
    </x-ui.section>

    {{-- Logo cloud --------------------------------------------------- --}}
    <x-ui.section tone="muted" spacing="tight">
        <x-ui.logo-cloud :eyebrow="__('Trusted technology partners')">
            <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">DocuWare</div>
            <div class="flex items-center justify-center font-mono text-base font-semibold text-zinc-500">Laravel</div>
            <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">Vue</div>
            <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">Inertia</div>
            <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">Cloudinary</div>
        </x-ui.logo-cloud>
    </x-ui.section>

    {{-- Quote -------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.quote
            attribution="Sebastian Bürgin-Fix"
            :role="__('Software Architect, Codebar')"
        >
            {{ __('We pick small, sharp tools and use them well. Most "innovation" is just discipline applied consistently.') }}
        </x-ui.quote>
    </x-ui.section>

    {{-- Recent news -------------------------------------------------- --}}
    @if($configuration?->section_news && !empty($news) && $news->count())
        <x-ui.section tone="muted">
            <div class="flex items-end justify-between gap-6 pb-12">
                <x-ui.section-header
                    :eyebrow="__('Newsroom')"
                    :title="__('Latest from the studio')"
                    class-attributes="max-w-2xl"
                />
                <a
                    href="{{ localized_route('news.index') }}"
                    class="hidden shrink-0 text-sm font-medium text-zinc-700 hover:text-zinc-950 md:inline"
                >
                    {{ __('View all') }} →
                </a>
            </div>
            <x-ui.list>
                @foreach($news as $entry)
                    <x-list-card
                        :url="localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $entry])"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                    />
                @endforeach
            </x-ui.list>
        </x-ui.section>
    @endif

    {{-- CTA ---------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.cta
            :title="__('Got something you want to build?')"
            :teaser="__('Tell us what you have in mind. We respond within one working day.')"
        >
            <x-ui.button :href="localized_route('contact.index')" :label="__('Start a project')" variant="primary" />
            <x-ui.button :href="localized_route('about-us.index')" :label="__('Meet the team')" variant="secondary" />
        </x-ui.cta>
    </x-ui.section>

    <x-docuware-showme />
</x-app-layout>
