<?php

namespace Database\Seeders;

use App\Enums\AiModelCategoryEnum;
use App\Models\AiModel;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class AiModelsTableSeeder extends Seeder
{
    use ReadsCsv;

    public function run(): void
    {
        $rows = collect($this->readCsv('ai_models.csv'));

        $models = $rows->map(function (array $row) {
            return AiModel::updateOrCreate(
                ['name' => $row['name']],
                [
                    'category' => AiModelCategoryEnum::from($row['category']),
                    'order' => (int) $row['order'],
                    'provider' => $row['provider'] !== '' ? $row['provider'] : null,
                    'ram' => $row['ram'] !== '' ? $row['ram'] : null,
                    'license' => $row['license'] !== '' ? $row['license'] : null,
                    'role' => $this->decodeJson($row['role']),
                    'link_label' => $row['link_label'] !== '' ? $row['link_label'] : null,
                    'link_url' => $row['link_url'] !== '' ? $row['link_url'] : null,
                    'archived_at' => $row['archived_at'] !== '' ? $row['archived_at'] : null,
                ]
            );
        });

        $idsByCsvId = $rows->pluck('id')->combine($models->pluck('id'));

        $rows->each(function (array $row) use ($idsByCsvId, $models) {
            if ($row['replaced_by_id'] === '') {
                return;
            }

            $models->firstWhere('id', $idsByCsvId->get($row['id']))
                ?->update(['replaced_by_id' => $idsByCsvId->get($row['replaced_by_id'])]);
        });
    }
}
