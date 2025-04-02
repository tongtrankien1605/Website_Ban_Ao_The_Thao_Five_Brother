<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SkusQuantityRequest extends FormRequest
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
            "cost_price" => "required|numeric|lte:price|min:10000|max:10000000",
            "price" => "required|numeric|min:10000|max:10000000",
            "quantity" => "required|numeric|min:1|max:10000",
            "sale_price" => "nullable|numeric|lt:price|min:10000|max:10000000",
        ];
    }
    public function withValidator($validator)
    {
        $validator->sometimes(["sale_start_date", "sale_end_date"], [
            "required",
            "date",
            "after_or_equal:today"
        ], function ($input) {
            return !is_null($input->sale_price);
        });

        $validator->sometimes("sale_end_date", [
            "after_or_equal:sale_start_date"
        ], function ($input) {
            return !is_null($input->sale_price);
        });
    }
    public function messages()
    {
        return [
            'price.required' => 'Vui lòng nhập giá bán',
            'cost_price.required' => 'Vui lòng nhập giá nhập',
            'price.numeric' => 'Giá bán phải là số',
            'sale_price.numeric' => 'Giá giảm phải là số',
            'cost_price.numeric' => 'Giá nhập phải là số',
            'price.max' => 'Giá bán phải từ 1 đến 10,000,000',
            'price.min' => 'Giá bán phải từ 1 đến 10,000,000',
            'cost_price.max' => 'Giá nhập phải từ 1 đến 10,000,000',
            'cost_price.min' => 'Giá nhập phải từ 1 đến 10,000,000',
            'sale_price.max' => 'Giá khuyến mãi phải từ 1 đến 10,000,000',
            'sale_price.min' => 'Giá khuyến mãi phải từ 1 đến 10,000,000',
            'sale_price.lt' => 'Giá khuyến mãi không được lớn hơn bằng giá bán',
            'cost_price.lte' => 'Giá nhập không được lớn hơn giá bán',
            
            'quantity.numeric' => 'Số lượng sản phẩm phải là số',
            'quantity.required' => 'Vui lòng nhập số lượng sản phẩm',
            'quantity.max' => 'Số lượng phải từ 1 đến 10,000',
            'quantity.min' => 'Số lượng phải từ 1 đến 10,000',

            'sale_start_date.required' => 'Ngày bắt đầu khuyến mãi là bắt buộc.',
            'sale_start_date.date' => 'Ngày bắt đầu khuyến mãi không hợp lệ.',
            'sale_start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',

            'sale_end_date.required' => 'Ngày kết thúc khuyến mãi là bắt buộc.',
            'sale_end_date.date' => 'Ngày kết thúc khuyến mãi không hợp lệ.',
            'sale_end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
