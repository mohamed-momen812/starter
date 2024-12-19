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
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|string|email|unique:users',
            'password'       => 'required|string',
            'type'           => 'in:admin,user',
            'permission_id'  => 'array|nullable',
            'permission_id.*' => 'integer|exists:permissions,id'
        ];
    }

    public function onUpdate(){
        return [
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|string|email',
            'password'       => 'required|string',
            'type'           => 'in:admin,user',
            'permission_id'  => 'array|nullable',
            'permission_id.*' => 'integer|exists:permissions,id'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'   => 'first_name is required',
            'first_name.string'     => 'first name must be string',
            'last_name.required'    => 'first_name is required',
            'last_name.string'      => 'first name must be string',
            'email.required'        => 'email is required',
            'email.string'          => 'email must be string',
            'email.email'           => "email isn't correct",
            'password.required'     => 'password field is required',
            'type.string'           => 'type must be string'
        ];
    }
}
