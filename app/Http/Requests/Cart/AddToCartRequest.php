<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $maxStock = $product?->stock_quantity ?? 9999;

        return [
            'quantity' => "required|integer|min:1|max:{$maxStock}",
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.max' => 'Invalid quantity or exceeds stock limit!',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Invalid quantity or exceeds stock limit!',
        ], 200));
    }
}