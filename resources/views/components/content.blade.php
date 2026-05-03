@props(['content'])

<x-section>
    <div class="prose prose-gray max-w-none md:prose-lg prose-headings:tracking-tight">
        {!! $content !!}
    </div>
</x-section>