<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',

            
            'description' => 'required|array',
            'description.ar' => 'nullable|array',
            'description.ar.*' => 'string|max:1000',
            'description.en' => 'nullable|array',
            'description.en.*' => 'string|max:1000',

           
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            
            'is_active' => 'boolean',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخدمة مطلوب',
            'name.array' => 'اسم الخدمة يجب أن يكون كائن يحتوي على اللغات',
            'name.ar.required' => 'الاسم بالعربي مطلوب',
            'name.ar.string' => 'الاسم بالعربي يجب أن يكون نص',
            'name.ar.max' => 'الاسم بالعربي يجب ألا يتجاوز 255 حرف',
            'name.en.required' => 'الاسم بالإنجليزي مطلوب',
            'name.en.string' => 'الاسم بالإنجليزي يجب أن يكون نص',
            'name.en.max' => 'الاسم بالإنجليزي يجب ألا يتجاوز 255 حرف',

            'description.required' => 'الوصف مطلوب',
            'description.array' => 'الوصف يجب أن يكون كائن يحتوي على اللغات',
            'description.ar.array' => 'الوصف العربي يجب أن يكون قائمة من النصوص',
            'description.ar.*.string' => 'كل عنصر في الوصف العربي يجب أن يكون نصًا',
            'description.en.array' => 'الوصف الإنجليزي يجب أن يكون قائمة من النصوص',
            'description.en.*.string' => 'كل عنصر في الوصف الإنجليزي يجب أن يكون نصًا',

            'image.image' => 'الصورة يجب أن تكون ملف صورة',
            'image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',

            'is_active.boolean' => 'قيمة الحالة يجب أن تكون true أو false',
        ];
    }
}
