<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                'name' => 'required|string|min:3|max:255',
                'description' => 'string|nullable|min:3|max:255',
                'price' => 'required|numeric|min:1',
                'stock' => 'required|numeric|min:1',
                'category_id' => 'required|exists:categories,id',
                'images' => 'required|array|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'Product name is required',
            'name.string' => 'Product name must be string',
            'name.min' => 'Product name must be at least 3 characters',
            'name.max' => 'Product name must be at most 255 characters',
            'description.string' => 'Product description must be string',
            'description.min' => 'Product description must be at least 3 characters',
            'description.max' => 'Product description must be at most 255 characters',
            'price.required' => 'Product price is required',
            'price.numeric' => 'Product price must be numeric',
            'price.min' => 'Product price must be at least 1',
            'stock.required' => 'Product stock is required',
            'stock.numeric' => 'Product stock must be numeric',
            'stock.min' => 'Product stock must be at least 1',
            'category_id.required' => 'Product category is required',
            'category_id.exists' => 'Product category does not exist',
            'images.required' => 'Product images are required',
        ];
    }
}
