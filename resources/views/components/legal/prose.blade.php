@props(['classAttributes' => ''])

<div {{ $attributes->merge(['class' => trim('legal-prose prose prose-gray max-w-none text-gray-800 prose-p:my-0 prose-p:leading-relaxed prose-ul:mt-4 prose-ul:mb-0 prose-li:my-1.5 prose-li:leading-relaxed '.$classAttributes)]) }}>
    {{ $slot }}
</div>
