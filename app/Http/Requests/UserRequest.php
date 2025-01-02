<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return request()->isMethod('PUT') || request()->isMethod('PATCH') ? $this->onUpdate() : $this->onCreate() ;
    }

    public function onCreate(){
        return [
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'email'           => 'required|string|email|unique:users',
            'bio'             => 'nullable|string',
            'password'        => 'nullable|string', // change this line to the next line
            // 'password'        => 'required|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'permission_id'   => 'nullable|array',
            'permission_id.*' => 'integer|exists:permissions,id'
        ];
    }

    public function onUpdate(){
        return [
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'bio'             => 'nullable|string',
            'email'           => 'required|string|email',
            'password'        => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'permission_id'   => 'array|nullable',
            'permission_id.*' => 'integer|exists:permissions,id'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'     => 'first_name is required',
            'first_name.string'       => 'first name must be string',
            'last_name.required'      => 'first_name is required',
            'last_name.string'        => 'first name must be string',
            'bio.string'              => 'bio must be string',
            'email.required'          => 'email is required',
            'email.string'            => 'email must be string',
            'email.email'             => "email isn't correct",
            'password.required'       => 'password field is required',
            'password.string'         => 'password must be string',
            'image.image'             => 'image must be image',
            'image.mimes'             => 'image must be jpeg,png,jpg,gif,svg',
            'image.max'               => 'image size must be less than 2mb',
            'permission_id.array'     => 'permission_id must be array',
            'permission_id.*.integer' => 'permission_id must be integer',
            'permission_id.*.exists'  => 'permission_id does not exist',];
    }
}
