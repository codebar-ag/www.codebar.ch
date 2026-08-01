<?php

declare(strict_types=1);

namespace App\DTO;

use App\Actions\LlmUsageStatsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * The three query-string filters of the analytics page, resolved against what the data
 * actually contains. Anything unrecognised becomes null rather than reaching a query —
 * a filter the user cannot have chosen must not narrow the numbers.
 */
class LlmUsageFilterDTO
{
    public function __construct(
        public readonly ?string $year = null,
        public readonly ?string $month = null,
        public readonly ?string $model = null,
    ) {}

    /**
     * @param  Collection<int, string>  $years
     * @param  Collection<string, string>  $monthOptions
     * @param  Collection<int, string>  $models
     */
    public static function resolve(
        Request $request,
        Collection $years,
        Collection $monthOptions,
        Collection $models,
        string $otherLabel,
        bool $hasOtherModels,
    ): self {
        return new self(
            year: $years->first(fn (string $option): bool => $option === $request->query('year')),
            month: self::month($monthOptions, (string) $request->query('month')),
            model: self::model($models, $otherLabel, (string) $request->query('model'), $hasOtherModels),
        );
    }

    /**
     * Accepts both the URL form ("05") and the localized label ("Mai"), so a month can
     * be linked to either way.
     *
     * @param  Collection<string, string>  $monthOptions
     */
    private static function month(Collection $monthOptions, string $input): ?string
    {
        if ($monthOptions->has($input)) {
            return $input;
        }

        $key = $monthOptions->search(fn (string $label): bool => strcasecmp($label, $input) === 0);

        return $key === false ? null : $key;
    }

    /**
     * @param  Collection<int, string>  $models
     */
    private static function model(Collection $models, string $otherLabel, string $input, bool $hasOther): ?string
    {
        if ($hasOther && (strcasecmp($input, $otherLabel) === 0 || strcasecmp($input, LlmUsageStatsAction::OTHER_MODEL) === 0)) {
            return LlmUsageStatsAction::OTHER_MODEL;
        }

        return $models->first(fn (string $option): bool => strcasecmp($option, $input) === 0);
    }

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return array_filter([
            'year' => $this->year,
            'month' => $this->month,
            'model' => $this->model,
        ], fn (?string $value): bool => $value !== null);
    }

    public function modelLabel(string $otherLabel): ?string
    {
        return $this->model === LlmUsageStatsAction::OTHER_MODEL ? $otherLabel : $this->model;
    }
}
