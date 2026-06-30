<x-app-layout :page="$page">
    <x-h1 :title="__('Team')"/>

    <x-section>
        <x-h2 :title="__('Employees')"/>
        @if(!empty($contacts->employees) && $contacts->employees->count())
            <x-list-grid>
                @foreach($contacts->employees as $contact)
                    <x-list-image-card
                            :name="$contact->name"
                            :role="$contact->role"
                            :icons="$contact->icons"
                            :image="$contact->image"/>
                @endforeach
            </x-list-grid>
        @endif
    </x-section>

    @if(!empty($contacts->collaborations) && $contacts->collaborations->count())
        <x-section>
            <x-h2 :title="__('Collaboration')"/>
            <x-list-grid>
                @foreach($contacts->collaborations as $contact)
                    <x-list-image-card
                            :name="$contact->name"
                            :role="$contact->role"
                            :icons="$contact->icons"
                            :image="$contact->image"/>
                @endforeach
            </x-list-grid>
        </x-section>
    @endif

    <x-section>
        <x-h2 :title="__('Board of directors')"/>
        <x-list-grid class-attributes="mt-2 grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($contacts->board_members as $contact)
                <x-list-image-card
                        :name="$contact->name"
                        :icons="$contact->icons"
                        :image="$contact->image"/>
            @endforeach
        </x-list-grid>
    </x-section>
</x-app-layout>
