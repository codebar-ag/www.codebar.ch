@props(['summary'])

@php
    use App\Helpers\Facades\HelperNumber;
@endphp

{{-- The tokens/requests pair shown on both the AI overview and the LLM page. --}}
<x-layout.grid :cols="2" class="mt-4">
    <x-card.stat-card
            :label="__('components.ai.stats.tokens_month')"
            :value="HelperNumber::abbreviate($summary['total_tokens'])"
            :input="HelperNumber::abbreviate($summary['prompt_tokens'])"
            :output="HelperNumber::abbreviate($summary['completion_tokens'])"/>
    <x-card.stat-card
            :label="__('components.ai.stats.requests_month')"
            :value="HelperNumber::format($summary['requests'], 0)"/>
</x-layout.grid>
