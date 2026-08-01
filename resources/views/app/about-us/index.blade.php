<x-app-layout :page="$page" :preconnect-cloudinary="true" :schema="$schema">
    <x-layout.page-header :title="__('Team')" :intro="__('components.team.header')"/>

    <x-layout.section>
        <x-h2 :title="__('components.team.working_title')"/>
        <x-ui.prose>
            <p>{{ __('components.team.working_body') }}</p>
            <p>{{ __('components.team.learning_body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Employees')"/>
        @if($contacts['employees']->isNotEmpty())
            <x-layout.grid :cols="2">
                @foreach($contacts['employees'] as $contact)
                    <x-card.person-card
                            :name="$contact->name"
                            :role="$contact->role"
                            :icons="$contact->icons"
                            :image="$contact->image"/>
                @endforeach
            </x-layout.grid>
        @endif
    </x-layout.section>

    @if($contacts['collaborations']->isNotEmpty())
        <x-layout.section>
            <x-h2 :title="__('Collaboration')"/>
            <x-layout.grid :cols="2">
                @foreach($contacts['collaborations'] as $contact)
                    <x-card.person-card
                            :name="$contact->name"
                            :role="$contact->role"
                            :icons="$contact->icons"
                            :image="$contact->image"/>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endif

    <x-layout.section>
        <x-h2 :title="__('Board of directors')"/>
        <x-layout.grid :cols="2">
            @foreach($contacts['board_members'] as $contact)
                <x-card.person-card
                        :name="$contact->name"
                        :icons="$contact->icons"
                        :image="$contact->image"/>
            @endforeach
        </x-layout.grid>
    </x-layout.section>
</x-app-layout>
