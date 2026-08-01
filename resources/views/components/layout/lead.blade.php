{{-- One lead treatment for the whole site: the paragraph directly under a page title.
     Detail pages used to set this semibold and index pages light, so the same slot read
     as two different things depending on where you had come from — and the news article
     kept its own copy of the class string, which is how a shared treatment drifts. --}}
<p {{ $attributes->merge(['class' => 'max-w-3xl text-lead font-light text-gray-800']) }}>{{ $slot }}</p>
