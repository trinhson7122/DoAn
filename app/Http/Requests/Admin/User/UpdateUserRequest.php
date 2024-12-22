<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:250',
            'date_of_birth' => 'nullable|date|before:today',
            'email' => 'required|email:rfc,dns|max:250|unique:users,email,' . $this->user->id,
            'phone_number' => 'nullable|string|max:11|regex:/^\d{10,11}$/',
        ];
    }
}
