<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image_file_name' => 'required|string',
            'menu_file_name' => 'required|string',
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
//            'tags' => 'array',
//            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required'),
            'name.string' => __('validation.string'),
            'name.max' => __('validation.max'),
            'description.required' => 'The description field is required.',
            'description.string' => __('validation.required'),
            'description.max' => __('validation.max'),
            'image_file_name.required' => __('validation.required'),
            'image_file_name.string' => __('validation.string'),
            'menu_file_name.required' => __('validation.required'),
            'menu_file_name.string' => __('validation.string'),
            'longitude.required' => __('validation.required'),
            'longitude.numeric' => __('validation.numeric'),
        ];
    }
}
