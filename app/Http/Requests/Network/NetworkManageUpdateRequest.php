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
            'name' => ['required', 'string', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'published' => ['nullable', 'boolean'],
            // "image" isn't used here — its hardcoded mime whitelist has no avif entry,
            // so it would reject avif uploads even though the "mimes" rule below allows them.
            'public_email' => ['nullable', 'email', 'max:255'],
            'avatar' => ['nullable', 'file', 'mimes:jpeg,png,webp,avif', 'max:2048', 'dimensions:ratio=1/1'],
            'cover' => ['nullable', 'file', 'mimes:jpeg,png,webp,avif', 'max:4096', 'dimensions:ratio=3/1'],
        ];
    }
}
