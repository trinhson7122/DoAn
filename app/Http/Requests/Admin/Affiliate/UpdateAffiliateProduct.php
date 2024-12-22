<?php

namespace App\Http\Requests\Admin\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffiliateProduct extends FormRequest
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
        if (request('has_affiliate', false)) {
            return [
                'affiliate_discount' => 'required|integer|min:1|max:100',
                'has_affiliate' => 'required|boolean',
            ];
        } else {
            return [
                'affiliate_discount' => 'nullable|integer|min:1|max:100',
                'has_affiliate' => 'nullable|boolean',
            ];
        }
    }
}
