<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:15',
            'address'        => 'required|string|max:500',
            'discount_id'    => 'nullable|exists:discounts,id',
            'payment_method' => 'required|in:cod,vnpay',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Please enter recipient name.',
            'phone.required'          => 'Please enter phone number.',
            'phone.max'               => 'Phone number must not exceed 15 characters.',
            'address.required'        => 'Please enter delivery address.',
            'discount_id.exists'      => 'Discount code is invalid.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in'       => 'Invalid payment method selected.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator,
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check your information again.')
        );
    }
}
