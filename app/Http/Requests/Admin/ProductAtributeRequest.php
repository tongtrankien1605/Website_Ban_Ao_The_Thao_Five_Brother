<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductAtributeRequest extends FormRequest
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
        $id = $this->product_attribute;
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('product_atributes', 'name')->ignore($id),
            ],
            'values' => 'required|array|min:1',
            'values.*' => 'required|max:255'
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Tên thuộc tính không được để trống.',
            'name.string' => 'Tên thuộc tính phải là chuỗi.',
            'name.max' => 'Tên thuộc tính không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
            'values.required' => 'Danh sách giá trị không được để trống.',
            'values.array' => 'Danh sách giá trị phải là một mảng.',
            'values.min' => 'Phải có ít nhất một giá trị.',
            'values.*.required' => 'Giá trị không được để trống.',
            'values.*.string' => 'Giá trị phải là chuỗi.',
            'values.*.max' => 'Giá trị không được vượt quá 255 ký tự.',
            'values.*.distinct' => 'Các giá trị không được trùng nhau.'
        ];
    }
}
