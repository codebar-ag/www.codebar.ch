@props(['classAttributes' => ""])

<section class="{{ $classAttributes }} mt-6 mb-2 text-lg text-gray-800">
    {{ $slot }}
</section>
