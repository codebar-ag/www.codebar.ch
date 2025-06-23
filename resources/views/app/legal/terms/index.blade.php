<x-app-layout :page="$page">
    @if(filled($configuration?->key))
        @include("app.legal.terms._partials.{$configuration->key}")
    @endif
</x-app-layout>