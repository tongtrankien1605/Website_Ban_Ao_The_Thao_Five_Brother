<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductAtributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Chuẩn bị dữ liệu đầu vào trước khi validate
     */
    // protected function prepareForValidation()
    // {
    //     // Xóa các attributes rỗng trước khi validate
    //     $attributes = array_filter($this->input('attributes', []), function ($attribute) {
    //         return !empty($attribute['name']) || !empty(array_filter($attribute['values'] ?? []));
    //     });

    //     // Xóa variants rỗng trước khi validate
    //     $variants = array_filter($this->input('variants', []), function ($variant) {
    //         return !empty($variant['attributes']) && isset($variant['price'], $variant['quantity']);
    //     });

    //     $this->merge([
    //         'attributes' => array_values($attributes), // Reset index tránh nhảy số
    //         'variants' => array_values($variants),
    //     ]);
    // }

    // /**
    //  * Định nghĩa các rules để validate
    //  */
    // public function rules(): array
    // {
    //     $rules = [
    //         'attributes' => ['required', 'array', 'min:1'], // Ít nhất một thuộc tính
    //         'variants' => ['required', 'array', 'min:1'], // Ít nhất một biến thể
    //     ];

    //     foreach ($this->input('attributes', []) as $index => $attribute) {
    //         $rules["attributes.$index.name"] = [
    //             'required',
    //             'string',
    //             'max:255',

    //         ];

    //         $rules["attributes.$index.values"] = [
    //             'required',
    //             'array',
    //             function ($attribute, $value, $fail) {
    //                 if (empty(array_filter($value))) {
    //                     $fail('Bạn phải nhập ít nhất một giá trị hợp lệ cho thuộc tính.');
    //                 }
    //             },
    //         ];

    //         foreach ($attribute['values'] ?? [] as $valueIndex => $value) {
    //             $rules["attributes.$index.values.$valueIndex"] = ['required', 'string', 'max:255'];
    //         }
    //     }

    //     // Validate cho variants
    //     foreach ($this->input('variants', []) as $index => $variant) {
    //         $rules["variants.$index.attributes"] = ['required', 'string'];
    //         $rules["variants.$index.price"] = ['required', 'numeric', 'min:0'];
    //         $rules["variants.$index.quantity"] = ['required', 'integer', 'min:0'];
    //         $rules["variants.$index.barcode"] = ['nullable', 'string', 'max:255'];
    //     }

    //     return $rules;
    // }
}
