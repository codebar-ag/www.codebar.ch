<?php

namespace Database\Seeders\Codebar;

use App\Enums\AiModelCategoryEnum;
use App\Models\AiModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AiModelsTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->active();
        $this->archived();
    }

    private function active(): void
    {
        $models = [
            // Reasoning & Coding
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 1,
                'name' => 'deepseek-v4:flash',
                'provider' => 'DeepSeek AI (CN)',
                'ram' => '~102 GB RAM',
                'license' => 'MIT',
                'role' => [
                    'de' => 'Flaggschiff: Analysen, Buchungslogik, komplexes Coding',
                    'en' => 'Flagship: analysis, booking logic, complex coding',
                ],
                'link_label' => 'Hugging Face',
                'link_url' => 'https://huggingface.co/unsloth',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 2,
                'name' => 'kimi-linear:48b',
                'provider' => 'Moonshot AI (CN)',
                'ram' => '30 GB RAM',
                'license' => 'MIT',
                'role' => [
                    'de' => 'Alltags-Sprinter, lange Dokumente & Kontextverständnis',
                    'en' => 'Everyday sprinter, long documents & context understanding',
                ],
                'link_label' => 'Hugging Face',
                'link_url' => 'https://huggingface.co/moonshotai',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 3,
                'name' => 'qwen3-coder:30b',
                'provider' => 'Alibaba / Qwen (CN)',
                'ram' => '18 GB RAM',
                'license' => 'Apache-2.0',
                'role' => [
                    'de' => 'Coding-Spezialist für schnelle Iterationen & Agenten-Tasks',
                    'en' => 'Coding specialist for fast iterations & agent tasks',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/qwen3-coder',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 4,
                'name' => 'qwen3.6:35b',
                'provider' => 'Alibaba / Qwen (CN)',
                'ram' => '23 GB RAM',
                'license' => 'Apache-2.0',
                'role' => [
                    'de' => 'Reserve-Mittelklasse',
                    'en' => 'Mid-range reserve',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/qwen3.6',
            ],

            // Vision & Dokumente
            [
                'category' => AiModelCategoryEnum::VISION_DOCUMENTS,
                'order' => 1,
                'name' => 'qwen3-vl:32b',
                'provider' => 'Alibaba / Qwen (CN)',
                'ram' => '20 GB RAM',
                'license' => 'Apache-2.0',
                'role' => [
                    'de' => 'Detailliertes Bildverständnis: Screenshots, Diagramme, Belege',
                    'en' => 'Detailed image understanding: screenshots, diagrams, receipts',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/qwen3-vl',
            ],
            [
                'category' => AiModelCategoryEnum::VISION_DOCUMENTS,
                'order' => 2,
                'name' => 'qwen3-vl:8b',
                'provider' => 'Alibaba / Qwen (CN)',
                'ram' => '6,1 GB RAM',
                'license' => 'Apache-2.0',
                'role' => [
                    'de' => 'Schnelle Vision-Variante für einfache Bildaufgaben',
                    'en' => 'Fast vision variant for simple image tasks',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/qwen3-vl',
            ],
            [
                'category' => AiModelCategoryEnum::VISION_DOCUMENTS,
                'order' => 3,
                'name' => 'gemma4:31b',
                'provider' => 'Google',
                'ram' => '19 GB RAM',
                'license' => 'Gemma',
                'role' => [
                    'de' => 'Bild-Input + stilsichere Texte',
                    'en' => 'Image input + polished writing',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/gemma4',
            ],
            [
                'category' => AiModelCategoryEnum::VISION_DOCUMENTS,
                'order' => 4,
                'name' => 'deepseek-ocr',
                'provider' => 'DeepSeek AI (CN)',
                'ram' => '6,7 GB RAM',
                'license' => 'MIT',
                'role' => [
                    'de' => 'PDF/Scan → Markdown',
                    'en' => 'PDF/scan → Markdown',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/deepseek-ocr',
            ],

            // Retrieval & Suche
            [
                'category' => AiModelCategoryEnum::RETRIEVAL_SEARCH,
                'order' => 1,
                'name' => 'qwen3-embedding:8b',
                'provider' => 'Alibaba / Qwen (CN)',
                'ram' => '4,7 GB RAM',
                'license' => 'Apache-2.0',
                'role' => [
                    'de' => 'Vektoren für Ähnlichkeitssuche (4096 Dimensionen)',
                    'en' => 'Vectors for similarity search (4096 dimensions)',
                ],
                'link_label' => 'Ollama',
                'link_url' => 'https://ollama.com/library/qwen3-embedding',
            ],
        ];

        foreach ($models as $model) {
            AiModel::updateOrCreate(
                ['name' => $model['name']],
                array_merge(['archived_at' => null, 'replaced_by_id' => null], $model)
            );
        }
    }

    private function archived(): void
    {
        $archivedAt = '2026-07-01';

        $models = [
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 1,
                'name' => 'qwen3.5:122b',
                'replaced_by' => 'deepseek-v4:flash',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 2,
                'name' => 'qwen3.6:35b-a3b',
                'replaced_by' => 'kimi-linear:48b',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 3,
                'name' => 'qwen3.6:27b',
                'replaced_by' => 'qwen3.6:35b',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 4,
                'name' => 'qwen3.5:9b',
                'replaced_by' => 'kimi-linear:48b',
            ],
            [
                'category' => AiModelCategoryEnum::REASONING_CODING,
                'order' => 5,
                'name' => 'qwen3.5:4b',
                'replaced_by' => 'kimi-linear:48b',
            ],
            [
                'category' => AiModelCategoryEnum::VISION_DOCUMENTS,
                'order' => 1,
                'name' => 'gemma4:12b',
                'replaced_by' => 'qwen3-vl:8b',
            ],
            [
                'category' => AiModelCategoryEnum::VISION_DOCUMENTS,
                'order' => 2,
                'name' => 'gemma4:e4b',
                'replaced_by' => 'qwen3-vl:8b',
            ],
            [
                'category' => AiModelCategoryEnum::RETRIEVAL_SEARCH,
                'order' => 1,
                'name' => 'qwen3-embedding:4b',
                'replaced_by' => 'qwen3-embedding:8b',
            ],
            [
                'category' => AiModelCategoryEnum::RETRIEVAL_SEARCH,
                'order' => 2,
                'name' => 'qwen3-reranker:8b',
                'replaced_by' => null,
            ],
        ];

        foreach ($models as $model) {
            $replacedByName = Arr::pull($model, 'replaced_by');
            $replacedById = $replacedByName ? AiModel::where('name', $replacedByName)->value('id') : null;

            AiModel::updateOrCreate(
                ['name' => $model['name']],
                array_merge(['archived_at' => $archivedAt, 'replaced_by_id' => $replacedById], $model)
            );
        }
    }
}
