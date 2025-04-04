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
            'variants' => 'required|array',
            'variants.*.cost_price' => 'required|numeric|lte:variants.*.price|min:10000|max:10000000',
            'variants.*.price' => 'required|numeric|min:10000|max:10000000',
            'variants.*.quantity' => 'required|numeric|min:1|max:10000',
            'variants.*.sale_price' => 'nullable|numeric|lt:variants.*.price|min:10000|max:10000000',
            'variants.*.discount_start' => 'nullable|date|after_or_equal:today',
            'variants.*.discount_end' => 'nullable|date|after_or_equal:variants.*.discount_start',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $variants = $this->input('variants', []);
            foreach ($variants as $index => $variant) {
                if (!empty($variant['sale_price'])) {
                    if (empty($variant['discount_start'])) {
                        $validator->errors()->add("variants.$index.discount_start", 'Ngày bắt đầu khuyến mãi là bắt buộc.');
                    }
                    if (empty($variant['discount_end'])) {
                        $validator->errors()->add("variants.$index.discount_end", 'Ngày kết thúc khuyến mãi là bắt buộc.');
                    }
                }
            }
        });
    }
    public function messages()
    {
        return [
            'variants.*.cost_price.required' => 'Vui lòng nhập giá nhập',
            'variants.*.cost_price.numeric' => 'Giá nhập phải là số',
            'variants.*.cost_price.lte' => 'Giá nhập không được lớn hơn giá bán',
            'variants.*.cost_price.min' => 'Giá nhập phải từ 10,000 đến 10,000,000',
            'variants.*.cost_price.max' => 'Giá nhập phải từ 10,000 đến 10,000,000',

            'variants.*.price.required' => 'Vui lòng nhập giá bán',
            'variants.*.price.numeric' => 'Giá bán phải là số',
            'variants.*.price.min' => 'Giá bán phải từ 10,000 đến 10,000,000',
            'variants.*.price.max' => 'Giá bán phải từ 10,000 đến 10,000,000',

            'variants.*.sale_price.numeric' => 'Giá khuyến mãi phải là số',
            'variants.*.sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán',
            'variants.*.sale_price.min' => 'Giá khuyến mãi phải từ 10,000 đến 10,000,000',
            'variants.*.sale_price.max' => 'Giá khuyến mãi phải từ 10,000 đến 10,000,000',

            'variants.*.quantity.required' => 'Vui lòng nhập số lượng sản phẩm',
            'variants.*.quantity.numeric' => 'Số lượng sản phẩm phải là số',
            'variants.*.quantity.min' => 'Số lượng phải từ 1 đến 10,000',
            'variants.*.quantity.max' => 'Số lượng phải từ 1 đến 10,000',

            'variants.*.discount_start.date' => 'Ngày bắt đầu khuyến mãi không hợp lệ',
            'variants.*.discount_start.after_or_equal' => 'Ngày bắt đầu khuyến mãi phải từ hôm nay trở đi',

            'variants.*.discount_end.date' => 'Ngày kết thúc khuyến mãi không hợp lệ',
            'variants.*.discount_end.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
        ];
    }
}
