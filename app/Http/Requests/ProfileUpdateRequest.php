<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates profile update fields with unique email constraint.
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Normalize empty strings to null before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('whatsapp_number') === '') {
            $this->merge(['whatsapp_number' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'whatsapp_number' => ['nullable', 'string', 'regex:/^\+?[1-9]\d{1,14}$/'],
        ];
    }
}
