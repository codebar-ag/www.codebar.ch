<?php

namespace App\Http\Requests\Network;

use Illuminate\Foundation\Http\FormRequest;

class NetworkManageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is enforced by the signed middleware on the route.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'published' => ['nullable', 'boolean'],
        ];
    }
}
