<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VolunteerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('volunteer') ? $this->route('volunteer')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'role' => 'required|in:admin,volunteer',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'address' => 'nullable|string',
            'number' => 'nullable|string',
            'city' => 'nullable|string',
            'cp' => 'nullable|string',
            'availability' => 'array',
            'availability.*' => 'array|nullable',
            'availability.*.*' => 'bool'
        ];
    }
}
