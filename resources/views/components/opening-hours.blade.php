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
    {{-- The status sits next to the heading as a chip: a green dot while we are open,
         a grey one while we are not — the box itself keeps the neutral logo wash. --}}
    <div class="flex flex-wrap items-center gap-3">
        <x-h2 :title="__('Opening hours')"/>

        <x-ui.badge class="mb-2" :variant="$isOpen ? 'success' : 'default'">
            <span @class([
                'mr-1.5 size-1.5 shrink-0 rounded-full',
                'bg-emerald-500' => $isOpen,
                'bg-gray-400' => ! $isOpen,
            ])></span>
            {{ $isOpen ? __('Currently open') : __('Currently closed') }}
        </x-ui.badge>
    </div>

    <div class="rounded-panel border border-border bg-linear-to-r from-fuchsia-600/10 via-brand/10 to-blue-600/10 px-4 py-2 sm:px-6">
        <dl class="divide-y divide-border-soft text-base">
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
