<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|unique:users'
        ];
    }


    public function messages(){
        return [
            'name.required' => 'اسم المستخدم مطلوب',
            'name.min' => 'اقل عدد من الاحرف ثلاثة احرف',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'صيغة البريد الالكتروني غير صحيحه',
            'email.unique' => 'هذا البريد الالكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبه',
            'password.min' => 'كلمة المرور يجب ان تكون 6 احرف على الاقل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'هذا الرقم مسجل بالفعل'
        ];
    }
}
