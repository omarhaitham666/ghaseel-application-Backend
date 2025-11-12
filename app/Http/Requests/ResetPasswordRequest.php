<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            //
            'verification_code' => 'required|digits:6',
            'new_password' => 'required|string|min:6|confirmed'
        ];
    }

    public function message(){
        return[
            'verification_code.required' => 'كود التحقق مطلوب',
            'verification_code.digits' => 'كود التحقق يجب أن يكون 6 أرقام',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل',
            'new_password.confirmed' => 'تأكيد كلمة المرور غير مطابق'
        ];
    }
}
