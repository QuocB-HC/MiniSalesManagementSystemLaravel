<?php

namespace App\Http\Requests\Discount;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change this logic based on your application's authorization needs.
        // Example: Only admin users can create discounts
        // return auth()->user() && auth()->user()->is_admin;
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            // If checkbox is not checked, it won't be present in the request, so we set it to false
            'is_active' => $this->has('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:discounts,code'],
            'type' => ['required', 'in:fixed,percentage'], // Only allow 'fixed' or 'percentage'
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'is_active' => ['boolean'], // Laravel will automatically convert 'on' to true and absence to false for checkboxes
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'The discount code is required.',
            'code.unique' => 'This discount code already exists.',
            'type.required' => 'The discount type is required.',
            'type.in' => 'The discount type is invalid. Only "fixed" or "percentage" are allowed.',
            'value.required' => 'The discount value is required.',
            'value' => [
                'required' => 'The discount value is required.',
                'numeric' => 'The discount value must be a number.',
                'min:0' => 'The discount value must be a positive number.',
                function ($attribute, $value, $fail) {
                    if ($this->type === 'percentage' && $value > 100) {
                        $fail('The discount value as a percentage cannot exceed 100%.');
                    }
                },
            ],
            'expires_at.date' => 'The expiration date is invalid.',
            'expires_at.after_or_equal' => 'The expiration date must be today or a future date.',
        ];
    }
}
