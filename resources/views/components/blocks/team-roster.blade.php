@props([
    'title',
    'members',
    'compact' => false,
])

<x-ui.section class-attributes="editorial-divider">
    <x-ui.headline :title="$title" level="h2" class-attributes="mb-4" />

    <div class="{{ $compact ? 'grid gap-4 md:grid-cols-2' : 'grid gap-5 md:grid-cols-2' }}">
        @foreach($members as $member)
            <x-list-image-card
                :name="$member->name"
                :role="$member->role ?? null"
                :icons="$member->icons ?? []"
                :image="$member->image"
                :image-container-class-attributes="$compact ? 'h-24 w-24 flex-shrink-0 overflow-hidden' : null"
            />
        @endforeach
    </div>
</x-ui.section>
