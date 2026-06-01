<x-app-layout :page="$page">
    <x-ui.hero :eyebrow="__('Product')" :title="$name" :teaser="$teaser" />

    <x-ui.section spacing="default">
        <div class="mx-auto max-w-3xl">
            <x-content :content="$content" />
        </div>
    </x-ui.section>

    <x-ui.section tone="muted">
        <x-ui.section-header
            align="center"
            :eyebrow="__('Modules')"
            :title="__('Two modules for Odoo + DocuWare')"
            :teaser="__('From master-data sync to accounting workflows.')"
        />

        <div class="mx-auto mt-12 max-w-4xl">
            <x-ui.grid columns="2">
                <a href="#module-masterdata" class="group block transition hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <x-ui.feature-card
                        title="Odoo Masterdata"
                        :teaser="__('Sync contacts, accounts, taxes and journals from Odoo into DocuWare cabinets.')"
                    >
                        <span class="inline-flex items-center text-sm font-medium text-brand">
                            {{ __('See module details') }}
                            <span aria-hidden="true" class="ml-1 transition-transform group-hover:translate-x-0.5">→</span>
                        </span>
                    </x-ui.feature-card>
                </a>

                <a href="#module-accounting" class="group block transition hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <x-ui.feature-card
                        title="Odoo Accounting"
                        :teaser="__('Bring accounting processes between Odoo and DocuWare together.')"
                    >
                        <span class="inline-flex items-center text-sm font-medium text-brand">
                            {{ __('See module details') }}
                            <span aria-hidden="true" class="ml-1 transition-transform group-hover:translate-x-0.5">→</span>
                        </span>
                    </x-ui.feature-card>
                </a>
            </x-ui.grid>
        </div>
    </x-ui.section>

    <x-ui.section id="module-masterdata" class="scroll-mt-24">
        <div class="grid gap-12 lg:grid-cols-[1.3fr_1fr] lg:items-start">
            <div>
                <x-ui.section-header
                    :eyebrow="__('Module')"
                    title="Odoo Masterdata"
                    :teaser="__('Synchronizes master data from Odoo into DocuWare data-record cabinets — three sync strategies, composite record keys, and pre-configured mappings for contacts, chart of accounts, taxes, and journals.')"
                />
                <div class="mt-8">
                    <x-ui.button
                        href="https://apps.odoo.com/apps/modules/19.0/docuware_masterdata"
                        target="_blank"
                        :label="__('Open in Odoo Apps Store')"
                        variant="secondary"
                    />
                </div>
            </div>

            <x-blocks.pricing-card
                name="Odoo Masterdata"
                :priceChf="40"
                :period="__('month')"
                currency="CHF"
            >
                <x-ui.button
                    href="https://apps.odoo.com/apps/modules/19.0/docuware_masterdata"
                    target="_blank"
                    :label="__('Get this module')"
                    variant="brand"
                    classAttributes="w-full"
                />
            </x-blocks.pricing-card>
        </div>
    </x-ui.section>

    <x-ui.section id="module-accounting" class="scroll-mt-24" tone="muted">
        <div class="grid gap-12 lg:grid-cols-[1.3fr_1fr] lg:items-start">
            <div>
                <x-ui.section-header
                    :eyebrow="__('Module')"
                    title="Odoo Accounting"
                    :teaser="__('Brings your accounting processes together between Odoo and DocuWare. Automate document-driven booking flows end-to-end.')"
                />
                <p class="mt-6 text-sm text-zinc-500">
                    {{ __('Public Odoo Apps Store link coming soon — get in touch for early access.') }}
                </p>
            </div>

            <x-blocks.pricing-card
                name="Odoo Accounting"
                :priceChf="60"
                :period="__('month')"
                currency="CHF"
                featured
                :badge="__('New')"
            >
                <x-ui.button
                    :href="localized_route('contact.index').'?subject='.rawurlencode(__('Odoo Accounting — early access'))"
                    :label="__('Request early access')"
                    variant="brand"
                    classAttributes="w-full"
                />
            </x-blocks.pricing-card>
        </div>
    </x-ui.section>

    <x-ui.section spacing="tight">
        <div class="mx-auto max-w-2xl space-y-2 text-center text-sm text-zinc-500">
            <p>{{ __('Prices excl. Swiss tax · 1-year minimum contract') }}</p>
            <p>{{ __('Subscribe for 2 years and pay in advance — get the 3rd year free.') }}</p>
            <p>{{ __('Module lifespan is aligned with the Odoo release cycle (approx. 3 years).') }}</p>
        </div>
    </x-ui.section>

    <x-blocks.contact-cta
        :title="__('Need a custom Odoo–DocuWare integration?')"
        :teaser="__('We build tailor-made modules to match your exact requirements.')"
        :email="false"
        :phone="false"
        :subject="null"
        tone="dark"
    />
</x-app-layout>
