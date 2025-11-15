<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAcceptOrderRequest extends FormRequest
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
             'final_price' => 'required|numeric|min:0',
        ];
    }


    public function messages(): array
    {
        return [
            'final_price.required' => 'يجب إدخال السعر النهائي.',
            'final_price.numeric'  => 'يجب أن يكون السعر رقمًا.',
            'final_price.min'      => 'يجب ألا يقل السعر عن 0.',
        ];
    }
}
