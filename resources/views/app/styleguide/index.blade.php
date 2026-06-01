<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('System')"
        :title="__('Design system')"
        :teaser="__('Tokens, primitives, patterns. Every page in the app is built from this single source of visual truth.')"
    >
        <x-ui.button :href="localized_route('start.index')" :label="__('Back to site')" variant="secondary" />
    </x-ui.hero>

    {{-- Typography ----------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Typography" title="Type scale" teaser="A single grotesque does the work — heading down to caption — with display-tight tracking on the largest sizes." />

        <div class="mt-16 space-y-12 border-t border-zinc-200 pt-12">
            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Display · text-5xl–8xl · -0.035em</p>
                <h1 class="mt-4 max-w-4xl text-balance text-5xl font-semibold leading-[0.95] tracking-[-0.035em] text-zinc-950 md:text-7xl lg:text-8xl">
                    Build the calm, durable thing.
                </h1>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">H2 · text-3xl–5xl · -0.025em</p>
                <h2 class="mt-4 text-3xl font-semibold leading-[1.04] tracking-[-0.025em] text-zinc-950 md:text-4xl lg:text-5xl">
                    A section headline carries the section.
                </h2>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">H3 · text-xl–2xl · -0.015em</p>
                <h3 class="mt-4 text-xl font-semibold leading-snug tracking-[-0.015em] text-zinc-950 md:text-2xl">
                    Card and feature headline.
                </h3>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Lead · text-lg–xl · zinc-600</p>
                <p class="mt-4 max-w-3xl text-pretty text-lg leading-relaxed text-zinc-600 md:text-xl md:leading-relaxed">
                    A lead paragraph introduces a section. It uses a slightly larger size and a lighter color to lead the reader in without competing with the headline above it.
                </p>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Body · text-base · zinc-700</p>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-700">
                    Body copy carries the argument. Comfortable line-height, neutral weight. The page disappears, the words remain.
                </p>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Small · text-sm · zinc-500</p>
                <p class="mt-4 text-sm text-zinc-500">For captions, metadata, helper text.</p>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Eyebrow · text-xs · uppercase</p>
                <p class="mt-4 text-xs font-medium uppercase tracking-[0.22em] text-zinc-500">A small label sits above the headline.</p>
            </div>

            <div>
                <p class="font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Mono · for code, identifiers, paths</p>
                <p class="mt-4 text-base text-zinc-700">
                    Inline code looks like <x-ui.mono>resources/css/app.css</x-ui.mono>. Suitable for filenames, env keys, class names.
                </p>
            </div>
        </div>
    </x-ui.section>

    {{-- Colors --------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Tokens" title="Color palette" teaser="Monochrome zinc carries everything. Brand purple is reserved for the rare moment that needs it." />

        <div class="mt-16 grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-4 lg:grid-cols-6">
            @php
                $swatches = [
                    ['label' => 'White', 'class' => 'bg-white border border-zinc-200', 'value' => '#ffffff'],
                    ['label' => 'Zinc 50', 'class' => 'bg-zinc-50', 'value' => '#fafafa'],
                    ['label' => 'Zinc 100', 'class' => 'bg-zinc-100', 'value' => '#f4f4f5'],
                    ['label' => 'Zinc 200', 'class' => 'bg-zinc-200', 'value' => '#e4e4e7'],
                    ['label' => 'Zinc 300', 'class' => 'bg-zinc-300', 'value' => '#d4d4d8'],
                    ['label' => 'Zinc 500', 'class' => 'bg-zinc-500', 'value' => '#71717a'],
                    ['label' => 'Zinc 600', 'class' => 'bg-zinc-600', 'value' => '#52525b'],
                    ['label' => 'Zinc 800', 'class' => 'bg-zinc-800', 'value' => '#27272a'],
                    ['label' => 'Zinc 950', 'class' => 'bg-zinc-950', 'value' => '#09090b'],
                    ['label' => 'Brand', 'class' => 'bg-brand', 'value' => '#500472'],
                    ['label' => 'Brand strong', 'class' => 'bg-brand-strong', 'value' => '#3a0354'],
                ];
            @endphp

            @foreach($swatches as $swatch)
                <div>
                    <div class="aspect-square w-full rounded-lg {{ $swatch['class'] }}"></div>
                    <p class="mt-3 text-sm font-medium text-zinc-950">{{ $swatch['label'] }}</p>
                    <p class="font-mono text-xs text-zinc-500">{{ $swatch['value'] }}</p>
                </div>
            @endforeach
        </div>
    </x-ui.section>

    {{-- Buttons -------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Primitives" title="Buttons" />

        <div class="mt-16 grid gap-12 md:grid-cols-2">
            <div>
                <p class="mb-5 font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Primary surface</p>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.button href="#" label="Primary" variant="primary" />
                    <x-ui.button href="#" label="Brand" variant="brand" />
                </div>
            </div>
            <div>
                <p class="mb-5 font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Secondary &amp; ghost</p>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.button href="#" label="Secondary" variant="secondary" />
                    <x-ui.button href="#" label="Ghost" variant="ghost" />
                </div>
            </div>
            <div>
                <p class="mb-5 font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Sizes</p>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.button href="#" label="Small" size="sm" />
                    <x-ui.button href="#" label="Medium" size="md" />
                    <x-ui.button href="#" label="Large" size="lg" />
                </div>
            </div>
            <div>
                <p class="mb-5 font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">With icon</p>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.button href="#" label="Read docs" variant="secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3.5">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                        </svg>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </x-ui.section>

    {{-- Tags & Links -------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Primitives" title="Tags &amp; links" />

        <div class="mt-16 grid gap-12 md:grid-cols-2">
            <div>
                <p class="mb-5 font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Tags</p>
                <div class="flex flex-wrap items-center gap-2">
                    <x-ui.tag>Engineering</x-ui.tag>
                    <x-ui.tag>Design</x-ui.tag>
                    <x-ui.tag>DocuWare</x-ui.tag>
                    <x-ui.tag>Open Source</x-ui.tag>
                </div>
            </div>
            <div>
                <p class="mb-5 font-mono text-xs uppercase tracking-[0.2em] text-zinc-400">Inline link</p>
                <p class="text-base text-zinc-700">
                    A paragraph with an <x-ui.link href="#" label="inline link" /> uses underline-offset and a soft decoration that darkens on hover.
                </p>
            </div>
        </div>
    </x-ui.section>

    {{-- Feature row -------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Patterns" title="Feature row" teaser="Three text-only features. No icons, no boxes, no shadows. The hierarchy is the design." />

        <div class="mt-16">
            <x-ui.feature-row>
                <x-ui.feature
                    eyebrow="Engineering"
                    title="Laravel & TypeScript"
                    teaser="Resilient backends, typed frontends. CI from day one. No surprises in production."
                />
                <x-ui.feature
                    eyebrow="Design"
                    title="Editorial, opinionated"
                    teaser="Type-led interfaces. Fewer ornaments, sharper hierarchy. Decisions you can defend."
                />
                <x-ui.feature
                    eyebrow="Operations"
                    title="DocuWare & automation"
                    teaser="Document workflows wired into the systems you already trust."
                />
            </x-ui.feature-row>
        </div>
    </x-ui.section>

    {{-- Logo cloud ---------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Patterns" title="Logo cloud" />
        <div class="mt-16">
            <x-ui.logo-cloud>
                <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">DocuWare</div>
                <div class="flex items-center justify-center font-mono text-base font-semibold text-zinc-500">Laravel</div>
                <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">Vue</div>
                <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">Inertia</div>
                <div class="flex items-center justify-center text-base font-semibold tracking-tight text-zinc-500">Cloudinary</div>
            </x-ui.logo-cloud>
        </div>
    </x-ui.section>

    {{-- Quote --------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Patterns" title="Quote" />
        <div class="mt-16">
            <x-ui.quote
                attribution="Sebastian Bürgin-Fix"
                role="Software Architect"
            >
                We pick small, sharp tools and use them well. Most “innovation” is just discipline applied consistently.
            </x-ui.quote>
        </div>
    </x-ui.section>

    {{-- List card ----------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Patterns" title="List card" teaser="The default index-page row. Title, teaser, tags, hover-affordance arrow." />

        <div class="mt-16">
            <x-ui.list>
                <x-list-card
                    url="#"
                    title="Migrating to Tailwind 4 without losing the editorial voice"
                    teaser="A short field report on stripping a heavy custom-CSS layer and rebuilding the same site with utility classes only."
                    :tags="['Engineering', 'Design system']"
                />
                <x-list-card
                    url="#"
                    title="Why we picked Geist over Inter"
                    teaser="Notes on legibility, vertical rhythm and the small details that nudge a sans-serif from competent to confident."
                    :tags="['Type', 'Notes']"
                />
                <x-list-card
                    url="#"
                    title="Switzerland-grade contracts and the trust they earn"
                    teaser="A practical look at why we keep our delivery process simple, written and explicit."
                    :tags="['Process']"
                />
            </x-ui.list>
        </div>
    </x-ui.section>

    {{-- Meta strip ---------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Patterns" title="Meta strip" />
        <div class="mt-16">
            <x-blocks.meta-strip :items="[
                ['label' => __('Published at'), 'value' => '2026-05-01'],
                ['label' => __('Last updated at'), 'value' => '2026-05-03'],
                ['label' => __('Author'), 'value' => 'Design Team'],
            ]" />
        </div>
    </x-ui.section>

    {{-- Prose --------------------------------------------------------- --}}
    <x-ui.section>
        <x-ui.section-header eyebrow="Patterns" title="Longform prose" teaser="Markdown-rendered content. Tight headings, comfortable paragraphs, soft underlined links." />

        <div class="mt-16 prose prose-zinc mx-auto max-w-3xl prose-headings:tracking-tight prose-headings:font-semibold prose-headings:text-zinc-950 prose-a:text-zinc-950 prose-a:underline prose-a:decoration-zinc-300 prose-a:underline-offset-4 hover:prose-a:decoration-zinc-950">
            <h3>Section heading</h3>
            <p>Prose blocks pick up the typography defaults and tighten heading tracking. Paragraphs flow with relaxed leading; <a href="#">links</a> reuse the underline-offset pattern. Lists fall in line:</p>
            <ul>
                <li>One thing</li>
                <li>Another thing</li>
                <li>A third thing for balance</li>
            </ul>
            <blockquote>A confident product trusts the content to do the work.</blockquote>
        </div>
    </x-ui.section>

    {{-- CTA ------------------------------------------------------------ --}}
    <x-ui.section>
        <x-ui.cta
            title="A clean canvas for clean ideas."
            teaser="The styleguide is the contract. Every page in the app picks its parts from this page — nothing else."
        >
            <x-ui.button href="{{ localized_route('start.index') }}" label="Back to site" variant="primary" />
            <x-ui.button href="{{ localized_route('contact.index') }}" label="Get in touch" variant="secondary" />
        </x-ui.cta>
    </x-ui.section>
</x-app-layout>
