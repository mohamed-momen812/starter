<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                'first_name' => 'required|string|between:2,100',
                'last_name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

     public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.between' => 'First name must be between 2 and 100 characters',
            'last_name.required' => 'Last name is required',
            'last_name.between' => 'Last name must be between 2 and 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.max' => 'Email must be at most 100 characters',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be jpeg, png, jpg, gif, svg',
            'image.max' => 'Image size must be less than 2mb',
        ];
    }
}