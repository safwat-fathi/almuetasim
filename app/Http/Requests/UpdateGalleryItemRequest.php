<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'caption' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'caption.required' => 'حقل الوصف مطلوب.',
            'caption.max' => 'لا يمكن أن يتجاوز الوصف 255 حرفًا.',
            'image.file' => 'الملف المرفوع غير صالح.',
            'image.mimes' => 'صيغة الصورة غير مدعومة.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت.',
        ];
    }
}
