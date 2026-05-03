<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        title="Design System Contract"
        teaser="Canonical tokens, primitives and blocks used by every page template in the app."
        eyebrow="System"
    >
        <x-ui.button href="{{ localized_route('start.index') }}" label="Back to site" variant="outline" />
    </x-blocks.editorial-hero>

    <x-ui.section class-attributes="editorial-divider">
        <x-ui.eyebrow text="Tokens" />
        <x-ui.headline title="Color and Surface System" level="h2" class-attributes="mt-2 mb-4" />
        <x-ui.grid columns="3">
            <x-ui.card>
                <x-ui.eyebrow text="Brand" />
                <div class="mt-3 h-20 rounded-lg border" style="background: var(--brand-color); border-color: color-mix(in srgb, var(--brand-color) 45%, white);"></div>
                <x-ui.text class-attributes="mt-3">`--brand-color` powers links, accents, rings and call-to-actions.</x-ui.text>
            </x-ui.card>
            <x-ui.card>
                <x-ui.eyebrow text="Feature Surface" />
                <div class="hero-gradient mt-3 h-20 rounded-lg border border-[var(--border-subtle)]"></div>
                <x-ui.text class-attributes="mt-3">`--surface-feature` creates the editorial hero atmosphere.</x-ui.text>
            </x-ui.card>
            <x-ui.card>
                <x-ui.eyebrow text="Elevation" />
                <div class="surface-card mt-3 h-20"></div>
                <x-ui.text class-attributes="mt-3">Subtle-to-strong elevation scales are controlled from token shadows.</x-ui.text>
            </x-ui.card>
        </x-ui.grid>
    </x-ui.section>

    <x-ui.section class-attributes="editorial-divider">
        <x-ui.eyebrow text="Primitives" />
        <x-ui.headline title="Typography, Actions and Cards" level="h2" class-attributes="mt-2 mb-4" />
        <x-ui.grid>
            <x-ui.card>
                <x-ui.stack gap="4">
                    <x-ui.headline title="Display Headline (H1)" level="h1" />
                    <x-ui.headline title="Section Headline (H2)" level="h2" />
                    <x-ui.headline title="Card Headline (H3)" level="h3" />
                    <x-ui.text size="lg">Large body copy for narrative intros.</x-ui.text>
                    <x-ui.text>Base copy for lists, details and utility content.</x-ui.text>
                </x-ui.stack>
            </x-ui.card>
            <x-ui.card>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.button href="#" label="Primary Button" />
                    <x-ui.button href="#" label="Outline Button" variant="outline" />
                    <x-ui.button href="#" label="Ghost Button" variant="ghost" />
                    <x-ui.badge label="Default Badge" />
                    <x-ui.badge label="Outline Badge" variant="outline" />
                </div>
                <x-ui.text class-attributes="mt-4">Button and badge primitives map to the same semantic color roles.</x-ui.text>
            </x-ui.card>
        </x-ui.grid>
    </x-ui.section>

    <x-ui.section class-attributes="editorial-divider">
        <x-ui.eyebrow text="Blocks" />
        <x-ui.headline title="Page Archetype Building Blocks" level="h2" class-attributes="mt-2 mb-4" />
        <x-ui.grid columns="3">
            <x-ui.feature-card title="Editorial Hero" teaser="Used on all index and detail pages as the narrative opener." />
            <x-ui.feature-card title="Content River" teaser="Longform body layout with optional sidebar for metadata." />
            <x-ui.feature-card title="Meta Strip" teaser="Compact structured metadata for article and detail contexts." />
        </x-ui.grid>
    </x-ui.section>

    <x-ui.section class-attributes="editorial-divider">
        <x-ui.eyebrow text="Composite Example" />
        <x-ui.headline title="Example: List to Detail Flow" level="h2" class-attributes="mt-2 mb-4" />
        <x-ui.grid columns="3">
            <x-list-card
                url="#"
                title="List Card"
                teaser="Index pages use this pattern for news, services, products and technologies."
                :tags="['editorial', 'ui', 'block']"
            />
            <x-ui.card>
                <x-blocks.meta-strip :items="[
                    ['label' => 'Published at', 'value' => '2026-02-24'],
                    ['label' => 'Author', 'value' => 'Design Team'],
                ]" />
            </x-ui.card>
            <x-ui.cta title="Call to action block" teaser="Reusable closing block for conversion and next-step guidance.">
                <x-ui.button href="#" label="Primary action" />
                <x-ui.button href="#" label="Secondary action" variant="outline" />
            </x-ui.cta>
        </x-ui.grid>
    </x-ui.section>
</x-app-layout>
