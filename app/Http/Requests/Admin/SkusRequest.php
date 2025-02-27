<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SkusRequest extends FormRequest
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
            "name" => "required|max:255",
            "price" => "required|numeric|gt:sale_price|min:1|max:9999999",
            "sale_price" => "nullable|numeric|lt:price|min:0|max:9999999",
            "image" => "nullable|image|mimes:jpeg,png,jpg|max:2048",
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên biến thể',
            'name.max' => 'Vui lòng nhập tên biến thể trong khoảng 255 kí tự',
            'price.required' => 'Vui lòng nhập giá biến thể',
            'price.numeric' => 'Giá biến thể phải là số',
            'sale_price.numeric' => 'Giá biến thể phải là số',
            'price.max' => 'Vui lòng nhập giá biển thể trong khoảng 1-999999',
            'price.min' => 'Vui lòng nhập giá biển thể trong khoảng 1-999999',
            'sale_price.max' => 'Vui lòng nhập giá biển thể trong khoảng 1-999999',
            'sale_price.min' => 'Vui lòng nhập giá biển thể trong khoảng 1-999999',
            'sale_price.lt' => 'Giá giảm phải nhỏ hơn giá gốc',
            'price.gt' => 'Giá gốc phải lớn hơn giá giảm',
            'image.mimes'=>'Không đúng định dạng file',
            'image.image'=>'File vừa chọn không phải là file ảnh'
        ];
    }
}
