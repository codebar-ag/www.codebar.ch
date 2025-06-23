<x-app-layout :page="$page">
    @if(filled($configuration?->key))
        @include("app.contact._partials.{$configuration->key}")
    @endif
</x-app-layout>