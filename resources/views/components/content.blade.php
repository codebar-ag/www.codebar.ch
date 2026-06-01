@props(['content'])

<x-ui.section>
    <div class="prose prose-zinc mx-auto max-w-3xl prose-headings:tracking-tight prose-headings:font-semibold prose-headings:text-zinc-950 prose-a:text-zinc-950 prose-a:underline prose-a:decoration-zinc-300 prose-a:underline-offset-4 hover:prose-a:decoration-zinc-950">
        {!! $content !!}
    </div>
</x-ui.section>
