<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'street_address' => 'sometimes|string|max:500',
            'building_number' => 'nullable|string|max:50',
            'apartment' => 'nullable|string|max:50',
            'city' => 'sometimes|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.string' => 'اسم العنوان يجب أن يكون نص',
            'name.max' => 'اسم العنوان يجب ألا يتجاوز 255 حرف',
            'street_address.string' => 'عنوان الشارع يجب أن يكون نص',
            'street_address.max' => 'عنوان الشارع يجب ألا يتجاوز 500 حرف',
            'city.string' => 'المدينة يجب أن تكون نص',
            'city.max' => 'المدينة يجب ألا تتجاوز 255 حرف',
            'latitude.numeric' => 'خط العرض يجب أن يكون رقماً',
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.numeric' => 'خط الطول يجب أن يكون رقماً',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
        ];
    }
}
