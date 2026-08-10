<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

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
        if ($this->input('phone_number') === '') {
            $this->merge(['phone_number' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'matric_no' => [
                $this->user()->role === 'user' ? 'required' : 'nullable',
                'numeric',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone_number' => ['nullable', 'string', 'regex:/^\+[1-9]\d{6,14}$/'],
        ];
    }
}

