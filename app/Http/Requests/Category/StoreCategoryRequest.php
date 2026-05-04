<?php

namespace App\Http\Requests\Category;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.string' => 'Category name must be a string.',
            'name.max' => 'Category name must not exceed 255 characters.',
            'name.unique' => 'Category name must be unique.',
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
