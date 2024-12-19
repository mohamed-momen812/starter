<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        return request()->isMethod('PUT') || request()->isMethod('PATCH') ? $this->onUpdate() : $this->onCreate();
    }

    public function onCreate()
    {
        return [
            'name'          => 'required|string|unique:roles,name',
            'permission_ids' => 'array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ];
    }
    public function onUpdate()
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->route('role'),
            'permission_ids' => 'array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ];
    }

     public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.unique' => 'The name has already been taken.',
            'permission_ids.array' => 'The permissions field must be an array.',
            'permission_ids.*.integer' => 'The selected permissions must be integers.',
            'permission_ids.*.exists' => 'The selected permissions do not exist.',
        ];
    }
}
