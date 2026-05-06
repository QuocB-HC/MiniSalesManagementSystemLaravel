<?php

namespace App\Http\Requests\Shop;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole(UserRole::CUSTOMER);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->name),
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('shops', 'name')->ignore($this->shop?->id),
            ],
            'logo_url' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The shop name is required.',
            'name.string' => 'The shop name must be a string.',
            'name.min' => 'The shop name must be at least 3 characters.',
            'name.max' => 'The shop name must be at most 100 characters.',
            'name.unique' => 'This shop name is already taken. Please choose another one.',
            'logo_url.string' => 'The shop logo URL must be a string.',
            'logo_url.max' => 'The shop logo URL must be at most 255 characters.',
            'address.required' => 'The shop address is required.',
            'address.string' => 'The shop address must be a string.',
            'address.max' => 'The shop address must be at most 255 characters.',
            'phone.required' => 'The shop phone number is required.',
            'phone.string' => 'The shop phone number must be a string.',
            'phone.max' => 'The shop phone number must be at most 20 characters.',
            'facebook_url.string' => 'The shop Facebook URL must be a string.',
            'facebook_url.max' => 'The shop Facebook URL must be at most 255 characters.',
            'instagram_url.string' => 'The shop Instagram URL must be a string.',
            'instagram_url.max' => 'The shop Instagram URL must be at most 255 characters.',
            'twitter_url.string' => 'The shop Twitter URL must be a string.',
            'twitter_url.max' => 'The shop Twitter URL must be at most 255 characters.',
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
