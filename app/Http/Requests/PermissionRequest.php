<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            'name'      => 'required|unique:permissions,name',
            'role_ids' => 'array',
            'role_ids.*' => 'integer|exists:roles,id',
        ];
    }
    public function onUpdate()
    {
        return [
            'name' => 'required|unique:permissions,name,' . $this->route('permission'),
            'role_ids' => 'array',
            'role_ids.*' => 'integer|exists:roles,id',
        ];
    }

     public function messages()
    {
        return [
            'name.required' => 'The permission name field is required.',
            'name.unique' => 'The permission name has already been taken.',
            'role_ids.*.integer' => 'The selected role ID is invalid.',
            'role_ids.*.exists' => 'The selected role does not exist.',
        ];
    }
}
