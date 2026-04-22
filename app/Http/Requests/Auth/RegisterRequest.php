<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must be less than 255 characters.',
            'email.required' => 'Please enter your email.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Invalid email format.',
            'email.max' => 'Email must be less than 255 characters.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please enter your password.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'The password must have at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Please confirm your password.',
            'password_confirmation.string' => 'Password confirmation must be a string.',
            'password_confirmation.min' => 'The password confirmation must have at least 8 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422));
    }
}
