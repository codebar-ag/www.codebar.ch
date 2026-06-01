<x-app-layout :page="$page">
    {{-- Hero --------------------------------------------------------- --}}
    <x-ui.hero
        :eyebrow="__('People')"
        :title="__('About us')"
        :teaser="__('A small Swiss team building dependable software since 2017. This is the story behind the work and the people who ship it.')"
    />

    {{-- Who we are --------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header
            :eyebrow="__('Who we are')"
            :title="__('A studio for software you can rely on.')"
        />
        <div class="mt-12 grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
            <p class="text-base leading-relaxed text-zinc-600">
                {{ __('codebar Solutions AG is a small, senior team based in Switzerland. We pick our work carefully, stay with clients for years, and prefer fewer commitments done well.') }}
            </p>
            <div class="prose prose-zinc max-w-none">
                <p>
                    {{ __('We design and engineer digital products with editorial clarity and Swiss-grade craft. The team blends strategy, design and engineering under one roof, which means decisions move in hours, not weeks.') }}
                </p>
                <p>
                    {{ __('Most of what we ship is invisible — line-of-business software, document workflows, integrations and operations tools — but it is always built to be used every day, by real people, for years.') }}
                </p>
            </div>
        </div>
    </x-ui.section>

    {{-- History / Timeline ------------------------------------------- --}}
    @if($milestones->count())
        <x-blocks.timeline
            :eyebrow="__('History')"
            :title="__('Almost ten years of building.')"
            :teaser="__('A short timeline of the moments that shaped how we work today.')"
            :milestones="$milestones"
        />
    @endif

    {{-- Pillars ------------------------------------------------------ --}}
    @if($pillars->count())
        <x-ui.section tone="muted">
            <x-ui.section-header
                :eyebrow="__('Our pillars')"
                :title="__('What we stand for.')"
                :teaser="__('Four principles we use to decide what to take on, what to decline, and how to work day to day.')"
            />
            <div class="mt-16">
                <x-ui.feature-row :columns="$pillars->count() === 4 ? '4' : '3'">
                    @foreach($pillars as $pillar)
                        <x-ui.feature
                            :eyebrow="$pillar->eyebrow ? __($pillar->eyebrow) : null"
                            :title="__($pillar->title)"
                            :teaser="$pillar->teaser ? __($pillar->teaser) : null"
                        />
                    @endforeach
                </x-ui.feature-row>
            </div>
        </x-ui.section>
    @endif

    {{-- Team --------------------------------------------------------- --}}
    @if(!empty($contacts->employees) && $contacts->employees->count())
        <x-blocks.team-roster :title="__('Employees')" :members="$contacts->employees" compact />
    @endif

    @if(!empty($contacts->collaborations) && $contacts->collaborations->count())
        <x-blocks.team-roster :title="__('Collaboration')" :members="$contacts->collaborations" />
    @endif

    @if(!empty($contacts->board_members) && $contacts->board_members->count())
        <x-blocks.team-roster :title="__('Board of directors')" :members="$contacts->board_members" compact />
    @endif

    {{-- CTA ---------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.cta
            :title="__('Want to work with us?')"
            :teaser="__('Tell us what you have in mind. We respond within one working day.')"
        >
            <x-ui.button :href="localized_route('contact.index')" :label="__('Start a project')" variant="primary" />
            <x-ui.button :href="localized_route('services.index')" :label="__('Read our services')" variant="secondary" />
        </x-ui.cta>
    </x-ui.section>
</x-app-layout>
