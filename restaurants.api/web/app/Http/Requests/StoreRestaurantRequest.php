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
        $isUpdate = $this->routeIs('restaurants.update');
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image_file_name' => $isUpdate ? ['nullable', 'string'] : ['required', 'string'],
            'menu_file_name' => $isUpdate ? ['nullable', 'string'] : ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'key_words' => 'array',
            'key_words.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required'),
            'name.string' => __('validation.string'),
            'name.max' => __('validation.max'),
            'description.required' => __('validation.required'),
            'description.string' => __('validation.required'),
            'description.max' => __('validation.max'),
            'image_file_name.required' => __('validation.required'),
            'image_file_name.string' => __('validation.string'),
            'menu_file_name.required' => __('validation.required'),
            'menu_file_name.string' => __('validation.string'),
            'longitude.required' => __('validation.required'),
            'longitude.numeric' => __('validation.numeric'),
            'longitude.between' => __('validation.between.numeric'),
            'latitude.required' => __('validation.required'),
            'latitude.numeric' => __('validation.numeric'),
            'latitude.between' => __('validation.between.numeric'),
            'key_words.array' => __('validation.array'),
            'key_words.*.exists' => __('validation.exists'),
        ];
    }
}
