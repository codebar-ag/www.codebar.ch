@props(['code', 'name' => '', 'height' => 16])

@php
    $stripes = '';

    foreach ([0, 2, 4, 6, 8, 10, 12] as $row) {
        $stripes .= '<rect y="'.round($row * 20 / 13, 3).'" width="30" height="'.round(20 / 13, 3).'" fill="#B22234"/>';
    }

    $stars = '';

    foreach ([1.6, 4.1, 6.6, 9.1] as $y) {
        foreach ([1.8, 4.6, 7.4, 10.2] as $x) {
            $stars .= '<circle cx="'.$x.'" cy="'.$y.'" r=".7" fill="#fff"/>';
        }
    }

    $flags = [
        'CH' => '<rect width="20" height="20" fill="#DA291C"/><rect x="8.125" y="3.75" width="3.75" height="12.5" fill="#fff"/><rect x="3.75" y="8.125" width="12.5" height="3.75" fill="#fff"/>',
        'DE' => '<rect width="30" height="20" fill="#FFCE00"/><rect width="30" height="13.334" fill="#DD0000"/><rect width="30" height="6.667" fill="#000"/>',
        'SE' => '<rect width="30" height="20" fill="#006AA7"/><rect x="9" width="4" height="20" fill="#FECC00"/><rect y="8" width="30" height="4" fill="#FECC00"/>',
        'BE' => '<rect width="30" height="20" fill="#EF3340"/><rect width="20" height="20" fill="#FDDA24"/><rect width="10" height="20" fill="#000"/>',
        'BG' => '<rect width="30" height="20" fill="#D62612"/><rect width="30" height="13.334" fill="#00966E"/><rect width="30" height="6.667" fill="#fff"/>',
        'TW' => '<rect width="30" height="20" fill="#FE0000"/><rect width="15" height="10" fill="#000095"/><polygon points="7.50,1.10 8.10,2.78 9.45,1.62 9.13,3.37 10.88,3.05 9.72,4.40 11.40,5.00 9.72,5.60 10.88,6.95 9.13,6.63 9.45,8.38 8.10,7.22 7.50,8.90 6.90,7.22 5.55,8.38 5.87,6.63 4.12,6.95 5.28,5.60 3.60,5.00 5.28,4.40 4.12,3.05 5.87,3.37 5.55,1.62 6.90,2.78" fill="#fff"/><circle cx="7.5" cy="5" r="2.1" fill="#000095"/><circle cx="7.5" cy="5" r="1.75" fill="#fff"/>',
        'US' => '<rect width="30" height="20" fill="#fff"/>'.$stripes.'<rect width="12" height="10.77" fill="#3C3B6E"/>'.$stars,
        'WW' => '<rect width="30" height="20" fill="#E0F2FE"/><g fill="none" stroke="#0369A1" stroke-width="1.3"><circle cx="15" cy="10" r="7"/><ellipse cx="15" cy="10" rx="3" ry="7"/><path d="M8 10h14"/></g>',
    ];
    $flag = $flags[$code] ?? null;
    $ratio = $code === 'CH' ? 1 : 1.5;
@endphp

@if($flag)
    <svg {{ $attributes->merge(['class' => 'inline-block shrink-0 rounded-[2px] ring-1 ring-black/15']) }}
         width="{{ round($height * $ratio, 1) }}" height="{{ $height }}" viewBox="0 0 {{ (int) (20 * $ratio) }} 20"
         role="img" aria-label="{{ $name }}">{!! $flag !!}</svg>
@endif
