<?php

namespace App\Http\Requests\Product;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => $this->price !== null ? (float) $this->price : null,
            'stock_quantity' => $this->stock_quantity !== null ? (int) $this->stock_quantity : 0,
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
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($this->product->id),
            ],
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:pending,approved,rejected,hidden,out_of_stock',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.max' => 'Product name must not exceed 255 characters.',
            'sku.required' => 'SKU is required.',
            'sku.string' => 'SKU must be a string.',
            'sku.max' => 'SKU must not exceed 50 characters.',
            'sku.unique' => 'SKU must be unique.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be an integer.',
            'stock_quantity.min' => 'Stock quantity must be at least 0.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of the following: pending, approved, rejected, hidden, out_of_stock.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image size must not exceed 2048 kilobytes.',
            'description.string' => 'Description must be a string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator,
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $errorMessage)
        );
    }
}
