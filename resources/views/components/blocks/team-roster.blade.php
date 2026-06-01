@props([
    'title',
    'members',
    'compact' => false,
])

<x-ui.section>
    <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-zinc-950 mb-12">{{ $title }}</h2>

    <div class="grid gap-x-8 gap-y-12 {{ $compact ? 'grid-cols-5' : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' }}">
        @foreach($members as $member)
            <x-list-image-card
                :name="$member->name"
                :role="$member->role ?? null"
                :icons="$member->icons ?? []"
                :image="$member->image"
                :compact="$compact"
            />
        @endforeach
    </div>
</x-ui.section>
