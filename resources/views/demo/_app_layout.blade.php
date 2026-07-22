<x-app-layout :page="$page">
    <div class="-mx-4 sm:-mx-6 lg:-mx-8 flex items-center justify-between gap-4 bg-zinc-950 text-white text-xs px-4 sm:px-6 lg:px-8 py-2 mb-6">
        <a href="{{ route('demo.flows.v2.index') }}" class="font-semibold hover:text-white/70 transition">← Alle Swiss-Grid Varianten</a>
        <span class="text-white/50 truncate">{{ $variantTitle ?? '' }}</span>
    </div>

    @yield('content')
</x-app-layout>
