@if ($configuration?->key === '_codebar')
    <x-section>
        <x-h2 :title="__('Next')" />
        <div class="mt-2 flex flex-col sm:flex-row gap-2 sm:gap-6">
            <x-a :href="localized_route('about-us.index')" :label="__('Team')" classAttributes="block" />
            <x-a :href="localized_route('contact.index')" :label="__('Contact')" classAttributes="block" />
        </div>
    </x-section>
@endif
