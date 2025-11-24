<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // penting!
    }

    public function rules(): array
    {
        return [
            'username' => ['nullable', 'string', 'max:255'],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'no_hp' => ['nullable', 'string', 'max:20'],

            'avatar' => ['nullable', 'image', 'max:2048'],

            'current_password' => ['nullable'],

            'password' => ['nullable', 'confirmed', 'min:6'],
        ];
    }
}
