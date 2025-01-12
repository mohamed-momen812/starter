<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                'title' => 'required|string|min:3|max:255',
                'description' => 'string|nullable|min:3|max:255',
                'price' => 'required|numeric|min:1',
                'color' => 'required|string?|in:red,green',
                'size' => 'required|numeric',
        ];
    }
}
