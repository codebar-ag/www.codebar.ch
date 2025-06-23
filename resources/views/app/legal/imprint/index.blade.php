<x-app-layout :page="$page">
    @if(filled($configuration?->key))
        @include("app.legal.imprint._partials.{$configuration->key}")
    @endif
</x-app-layout>

