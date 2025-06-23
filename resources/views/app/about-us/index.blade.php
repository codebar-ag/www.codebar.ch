<x-app-layout :page="$page">
    <x-h1 :title="__('About us')"/>

    {{-- <x-section>Lorem Ipsums Lorem Ipsum Lorem Ipsum</x-section>--}}

    <div class="space-y-12">


        <x-section>
            <x-h2 :title="__('paperflakes AG')"/>

            <div class="space-y-6">
                @if(!empty($contacts->employee_services) && $contacts->employee_services->count())
                    <x-h3 :title="__('Services')"/>
                    <x-list-grid>

                        @foreach($contacts->employee_services as $contact)
                            <x-list-image-card :name="$contact->name" :role="$contact->role" :icons="$contact->icons"
                                               :image="$contact->image"/>
                        @endforeach
                    </x-list-grid>
                @endif

                @if(!empty($contacts->employee_services) && $contacts->employee_services->count())
                    <x-h3 :title="__('Products')"/>
                    <x-list-grid>
                        @foreach($contacts->employee_products as $contact)
                            <x-list-image-card :name="$contact->name" :role="$contact->role" :icons="$contact->icons"
                                               :image="$contact->image"/>
                        @endforeach
                    </x-list-grid>
                @endif

                @if(!empty($contacts->employee_administration) && $contacts->employee_administration->count())
                    <x-h3 :title="__('Administration')"/>
                    <x-list-grid>
                        @foreach($contacts->employee_administration as $contact)
                            <x-list-image-card :name="$contact->name" :role="$contact->role" :icons="$contact->icons"
                                               :image="$contact->image"/>
                        @endforeach
                    </x-list-grid>
                @endif
            </div>
        </x-section>

        @if(!empty($contacts->employee_services) && $contacts->employee_services->count())
            <x-section>
                <x-h2 :title="__('Collaboration')"/>
                <x-list-grid>
                    @foreach($contacts->collaborations as $contact)
                        <x-list-image-card :name="$contact->name" :role="$contact->role" :image="$contact->image"
                                           image-container-class-attributes="h-20 w-auto flex-shrink-0 overflow-hidden"/>
                    @endforeach
                </x-list-grid>
            </x-section>
        @endif

        <x-section>
            <x-h2 :title="__('Board of directors')"/>
            <x-list-grid class-attributes="mt-2 grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($contacts->board_members as $contact)
                    <x-list-image-card :name="$contact->name" :image="$contact->image"
                                       image-container-class-attributes="h-20 w-auto flex-shrink-0 overflow-hidden"/>
                @endforeach
            </x-list-grid>
        </x-section>
    </div>
</x-app-layout>