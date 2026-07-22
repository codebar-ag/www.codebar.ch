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
    <x-h2 :title="__('Opening hours')"/>

    {{-- The box colour carries the status: green while open, red while closed. --}}
    <div @class([
        'mt-2 rounded-panel border px-4 sm:px-6 py-2',
        'border-green-200 bg-green-50/60' => $isOpen,
        'border-red-200 bg-red-50/60' => ! $isOpen,
    ])>
        <span class="sr-only">{{ $isOpen ? __('Currently open') : __('Currently closed') }}</span>

        <dl @class([
            'divide-y text-base',
            'divide-green-200/70' => $isOpen,
            'divide-red-200/70' => ! $isOpen,
        ])>
            @foreach ($groups as $group)
                @php
                    $isToday = $todayIso - 1 >= $group['startIdx'] && $todayIso - 1 <= $group['endIdx'];
                    $dayLabel = $group['startIdx'] === $group['endIdx']
                        ? __($group['startDay'])
                        : __($group['startDay']) . ' – ' . __($group['endDay']);
                @endphp
                <div class="flex items-center justify-between gap-4 py-2.5">
                    <dt class="{{ $isToday ? 'font-semibold text-gray-900' : 'font-medium' }}">
                        {{ $dayLabel }}
                    </dt>
                    @if ($group['open'])
                        <dd class="tabular-nums {{ $isToday ? 'font-semibold text-gray-900' : 'font-light' }}">
                            {{ __(':open to :close', ['open' => $group['open'], 'close' => $group['close']]) }}
                        </dd>
                    @else
                        <dd class="font-light text-gray-500">{{ __('Closed') }}</dd>
                    @endif
                </div>
            @endforeach
        </dl>
    </div>
</div>
