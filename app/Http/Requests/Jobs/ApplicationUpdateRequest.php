<?php

declare(strict_types=1);

namespace App\Http\Requests\Jobs;

class ApplicationUpdateRequest extends ApplicationSaveRequest
{
    public function isSubmitAction(): bool
    {
        return $this->string('action')->value() === 'submit';
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['action'] = ['required', 'in:save,submit'];

        if ($this->isSubmitAction()) {
            foreach (['first_name', 'last_name', 'age', 'city', 'interests', 'focus_fit', 'about'] as $field) {
                $rules[$field][0] = 'required';
            }
        }

        return $rules;
    }
}
