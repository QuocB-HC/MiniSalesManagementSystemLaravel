<?php

namespace App\Http\Requests\Discount;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change this logic based on your application's authorization needs.
        // Example: Only admin users can update discounts
        // return auth()->user() && auth()->user()->is_admin;
        return true;
    }

    protected function prepareForValidation()
    {
        // Ensure that 'is_active' is always present in the validated data, even if the checkbox is not checked (which means it won't be sent in the request)
        $this->merge([
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
            // When updating, we need to ignore the current discount's code for uniqueness check
            'code' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('discounts', 'code')->ignore($this->discount->id)
            ],
            'type' => ['required', 'in:fixed,percentage'],
            'value' => [
                'required', 
                'numeric', 
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->type === 'percentage' && $value > 100) {
                        $fail('The discount value as a percentage cannot exceed 100%.');
                    }
                }
            ],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
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
            'value.numeric' => 'The discount value must be a number.',
            'value.min' => 'The discount value must be a positive number.',
            'expires_at.date' => 'The expiration date is invalid.',
            'expires_at.after_or_equal' => 'The expiration date must be today or a future date.',
        ];
    }
}
