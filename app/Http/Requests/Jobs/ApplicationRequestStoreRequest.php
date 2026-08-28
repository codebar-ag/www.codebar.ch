<?php

declare(strict_types=1);

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }
}
