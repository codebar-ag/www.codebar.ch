@props(['classAttributes' => ""])

<section class="{{ $classAttributes }} mt-6 text-lg text-gray-800">
    {{ $slot }}
</section>