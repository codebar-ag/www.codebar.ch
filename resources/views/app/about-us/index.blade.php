<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('About us')"
        :teaser="__('The people, collaborators and leadership behind every project we ship.')"
        :eyebrow="__('People')"
    />

    <div class="space-y-10">
        @if(!empty($contacts->employees) && $contacts->employees->count())
            <x-blocks.team-roster :title="__('Employees')" :members="$contacts->employees" />
        @endif

        @if(!empty($contacts->collaborations) && $contacts->collaborations->count())
            <x-blocks.team-roster :title="__('Collaboration')" :members="$contacts->collaborations" />
        @endif

        @if(!empty($contacts->board_members) && $contacts->board_members->count())
            <x-blocks.team-roster :title="__('Board of directors')" :members="$contacts->board_members" compact />
        @endif
    </div>
</x-app-layout>
