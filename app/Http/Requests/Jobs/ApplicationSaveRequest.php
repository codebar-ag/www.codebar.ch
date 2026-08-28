<?php

declare(strict_types=1);

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'between:10,99'],
            'city' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'string', 'max:10000'],
            'focus_fit' => ['nullable', 'string', 'max:10000'],
            'built_so_far' => ['nullable', 'string', 'max:10000'],
            'about' => ['nullable', 'string', 'max:10000'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'project_link' => ['nullable', 'url', 'max:255'],
            'documents' => ['nullable', 'array', 'max:10'],
            'documents.*' => ['file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:10240'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function applicationAttributes(): array
    {
        $validated = (array) $this->validated();

        $attributes = [];

        foreach ([
            'first_name',
            'last_name',
            'age',
            'city',
            'interests',
            'focus_fit',
            'built_so_far',
            'about',
            'github',
            'linkedin',
            'project_link',
        ] as $field) {
            if (array_key_exists($field, $validated)) {
                $attributes[$field] = $validated[$field];
            }
        }

        return $attributes;
    }
}
