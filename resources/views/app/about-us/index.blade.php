<x-app-layout :page="$page">
    <x-h1 :title="__('About us')"/>

    {{-- <x-section>Lorem Ipsums Lorem Ipsum Lorem Ipsum</x-section>--}}

    <div class="space-y-12">

        <x-section>
            <x-h2 :title="__('Employees')"/>
            <x-h3 :title="__('Software Engineering')"/>

            <div class="space-y-6 mb-6">
                @if(!empty($contacts->software_engineering) && $contacts->software_engineering->count())
                    <x-list-grid>

                        @foreach($contacts->software_engineering as $contact)
                            <x-list-image-card
                                    :name="$contact->name"
                                    :role="$contact->role"
                                    :icons="$contact->icons"
                                    :image="$contact->image"/>
                        @endforeach
                    </x-list-grid>
                @endif

            </div>

            <x-h3 :title="__('Digital Transformation')" class=""/>

            <div class="space-y-6 mb-6">
                @if(!empty($contacts->digital_transformation) && $contacts->digital_transformation->count())
                    <x-list-grid>

                        @foreach($contacts->digital_transformation as $contact)
                            <x-list-image-card
                                    :name="$contact->name"
                                    :role="$contact->role"
                                    :icons="$contact->icons"
                                    :image="$contact->image"/>
                        @endforeach
                    </x-list-grid>
                @endif

            </div>

            <x-h3 :title="__('Scanning')" class=""/>

            <div class="space-y-6 mb-6">
                @if(!empty($contacts->scanning) && $contacts->scanning->count())
                    <x-list-grid>

                        @foreach($contacts->scanning as $contact)
                            <x-list-image-card
                                    :name="$contact->name"
                                    :role="$contact->role"
                                    :icons="$contact->icons"
                                    :image="$contact->image"/>
                        @endforeach
                    </x-list-grid>
                @endif

            </div>


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
                            :image="$contact->image"
                            image-container-class-attributes="h-24 w-24 flex-shrink-0 overflow-hidden"/>
                @endforeach
            </x-list-grid>
        </x-section>
    </div>
</x-app-layout>
