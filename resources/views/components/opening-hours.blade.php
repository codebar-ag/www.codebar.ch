@props(['hours'])

@php
    $now = \Carbon\Carbon::now();
    $todayIso = $now->dayOfWeekIso;
    $currentTime = $now->format('H:i');

    $groups = [];
    $i = 0;
    $count = count($hours);
    while ($i < $count) {
        $start = $i;
        while ($i + 1 < $count && $hours[$i + 1]['open'] === $hours[$start]['open'] && $hours[$i + 1]['close'] === $hours[$start]['close']) {
            $i++;
        }
        $groups[] = [
            'startDay' => $hours[$start]['day'],
            'endDay' => $hours[$i]['day'],
            'startIdx' => $start,
            'endIdx' => $i,
            'open' => $hours[$start]['open'],
            'close' => $hours[$start]['close'],
        ];
        $i++;
    }

    $todayEntry = $hours[$todayIso - 1] ?? null;
    $isOpen = false;
    if ($todayEntry && $todayEntry['open'] && $todayEntry['close']) {
        $isOpen = $currentTime >= $todayEntry['open'] && $currentTime < $todayEntry['close'];
    }
@endphp

<div {{ $attributes }}>
    <div class="flex items-center gap-3 mb-3">
        <x-h2 :title="__('Opening hours')"/>
        @if ($isOpen)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-sm font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                <span class="size-1.5 rounded-full bg-green-500"></span>
                {{ __('Currently open') }}
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-2.5 py-0.5 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                <span class="size-1.5 rounded-full bg-gray-400"></span>
                {{ __('Currently closed') }}
            </span>
        @endif
    </div>

    <div class="space-y-1">
        @foreach ($groups as $group)
            @php
                $isToday = $todayIso - 1 >= $group['startIdx'] && $todayIso - 1 <= $group['endIdx'];
                $dayLabel = $group['startIdx'] === $group['endIdx']
                    ? __($group['startDay'])
                    : __($group['startDay']) . ' – ' . __($group['endDay']);
            @endphp
            <div @class([
                'flex justify-between items-center py-2',
                'border-b border-gray-200' => !$loop->last,
                'border-l-2 border-l-brand pl-3 bg-brand/5 rounded-r' => $isToday,
            ])>
                <span class="{{ $isToday ? 'font-semibold' : 'font-medium' }}">{{ $dayLabel }}</span>
                @if ($group['open'])
                    <span class="font-light">{{ __(':open to :close', ['open' => $group['open'], 'close' => $group['close']]) }}</span>
                @else
                    <span class="font-light text-gray-500">{{ __('Closed') }}</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
