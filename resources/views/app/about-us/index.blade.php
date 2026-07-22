<x-app-layout :page="$page" :preconnect-cloudinary="true">
    <x-h1 :title="__('Team')"/>

    <x-layout.section>
        <x-h2 :title="__('Employees')"/>
        @if(!empty($contacts->employees) && $contacts->employees->count())
            <x-layout.grid :cols="2" class="mt-2">
                @foreach($contacts->employees as $contact)
                    <x-card.person-card
                            :name="$contact->name"
                            :role="$contact->role"
                            :icons="$contact->icons"
                            :image="$contact->image"/>
                @endforeach
            </x-layout.grid>
        @endif
    </x-layout.section>

    @if(!empty($contacts->collaborations) && $contacts->collaborations->count())
        <x-layout.section>
            <x-h2 :title="__('Collaboration')"/>
            <x-layout.grid :cols="2" class="mt-2">
                @foreach($contacts->collaborations as $contact)
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
        <x-layout.grid :cols="2" class="mt-2">
            @foreach($contacts->board_members as $contact)
                <x-card.person-card
                        :name="$contact->name"
                        :icons="$contact->icons"
                        :image="$contact->image"/>
            @endforeach
        </x-layout.grid>
    </x-layout.section>
</x-app-layout>
