<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
            //
            'verification_code' => 'required|digits:6'
        ];
    }

    public function message(){
        return[
            'verification_code.required' => 'كود التحقق مطلوب',
            'verification_code.digits' => 'كود التحقق يجب أن يكون 6 أرقام'
        ];
    }

}
