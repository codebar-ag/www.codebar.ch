@props(['classAttributes' => ''])

<hr {{ $attributes->merge(['class' => "border-0 border-t border-zinc-200 {$classAttributes}"]) }} />
