<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'service_ids' => 'required|array',
            'service_ids.*' => 'integer|exists:services,id',
            'location_id' => 'required|integer|exists:user_locations,id',
            'delivery_type' => 'required|string|in:normal,express',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|string',
            'delivery_date' => 'required|date|after_or_equal:pickup_date',
            'delivery_time' => 'required|string',
            'notes' => 'nullable|string|max:1000',
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
            'service_ids.required' => 'يجب اختيار خدمة واحدة على الأقل',
            'service_ids.array' => 'الخدمات يجب أن تكون مصفوفة',
            'service_ids.*.integer' => 'معرف الخدمة يجب أن يكون رقماً',
            'service_ids.*.exists' => 'الخدمة المحددة غير موجودة',
            'location_id.required' => 'العنوان مطلوب',
            'location_id.integer' => 'معرف العنوان يجب أن يكون رقماً',
            'location_id.exists' => 'العنوان المحدد غير موجود',
            'delivery_type.required' => 'نوع التوصيل مطلوب',
            'delivery_type.in' => 'نوع التوصيل غير صحيح',
            'pickup_date.required' => 'تاريخ الاستلام مطلوب',
            'pickup_date.date' => 'تاريخ الاستلام غير صحيح',
            'pickup_date.after_or_equal' => 'تاريخ الاستلام يجب أن يكون اليوم أو بعده',
            'pickup_time.required' => 'وقت الاستلام مطلوب',
            'delivery_date.required' => 'تاريخ التسليم مطلوب',
            'delivery_date.date' => 'تاريخ التسليم غير صحيح',
            'delivery_date.after_or_equal' => 'تاريخ التسليم يجب أن يكون بعد تاريخ الاستلام',
            'delivery_time.required' => 'وقت التسليم مطلوب',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف',
        ];
    }
}
