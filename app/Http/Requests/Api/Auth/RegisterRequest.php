<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|string',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*\d)[a-zA-Z\d]{6,}$/',
        ];
    }


    public function messages() {
        return [
            'password.regex' => 'Password must contain at least 1 lowercase letter and 1 number, and be at least 6 characters long',
        ];
    }


    /**
     * Handle a failed validation attempt.
     * This returns JSON response for API instead of redirecting
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422)
        );
    }

}
