@if ($configuration?->key === '_codebar')
    <x-section-flex>
        <div>
            <x-h3 :title="__('Team')" />
            <x-a :href="localized_route('about-us.index')" :label="__('Meet the team')" classAttributes="block" />
        </div>
        <div>
            <x-h3 :title="__('Contact')" />
            <x-a href="mailto:info@codebar.ch?subject=Hello%20World!" label="{{ __('info(at)codebar.ch') }}"
                 classAttributes="block" />
            <x-a href="tel:0041615156090" label="{{ __('+41 61 515 60 90') }}" classAttributes="block" />
            <x-a :href="localized_route('contact.index')" :label="__('Contact')" classAttributes="block" />
        </div>
    </x-section-flex>
@endif
