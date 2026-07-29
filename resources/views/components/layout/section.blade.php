{{-- The one vertical rhythm between page sections. It comes from --spacing-section,
     so the whole site re-spaces from a single number in app.css. --}}
<section {{ $attributes->merge(['class' => 'mt-section text-lg text-gray-800']) }}>
    {{ $slot }}
</section>
