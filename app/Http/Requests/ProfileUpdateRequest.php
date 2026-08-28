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
        $num = $this->input('whatsapp_number');
        if ($num === '' || $num === null) {
            $this->merge(['whatsapp_number' => null]);
        } else {
            $num = trim($num);
            $num = preg_replace('/^(\+\d{1,4})0+(\d+)/', '$1$2', $num);
            $this->merge(['whatsapp_number' => $num]);
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
