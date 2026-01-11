<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can implement your authorization logic here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|json',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'is_part' => 'required|boolean',
            'warranty_months' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:product,service',
        ];

        if ($this->hasFile('images')) {
            $rules['images'] = 'nullable|array|max:5';
            $rules['images.*'] = 'file|mimes:jpeg,png,jpg,webp|max:5120';
        } elseif ($this->has('images')) {
            // Accept JSON for existing image paths when editing
            $rules['images'] = 'nullable|json';
        }

        return $rules;
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'حقل الاسم مطلوب.',
            'title.max' => 'لا يمكن أن يتجاوز الاسم 255 حرفًا.',
            'description.required' => 'حقل الوصف مطلوب.',
            'price.required' => 'حقل السعر مطلوب.',
            'price.numeric' => 'يجب أن يكون السعر رقماً.',
            'price.min' => 'لا يمكن أن يكون السعر أقل من 0.',
            'category_id.exists' => 'التصنيف المحدد غير موجود.',
            'type.in' => 'نوع المنتج غير صحيح.',
        ];
    }
}
