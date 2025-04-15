<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateQuantityConfirmRequest extends FormRequest
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
            'import_id' => 'required',
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:inventory_entries,id',
            'variants.*.cost_price' => 'required|numeric|min:10000|max:10000000',
            'variants.*.price' => 'required|numeric|min:10000|max:10000000',
            'variants.*.quantity' => 'required|numeric|min:1|max:10000',
            'variants.*.sale_price' => 'nullable|numeric|min:10000|max:10000000',
            'variants.*.discount_start' => 'nullable|date|after_or_equal:today',
            'variants.*.discount_end' => 'nullable|date|after_or_equal:variants.*.discount_start',
        ];
    }

    /**
     * Thêm validation tùy chỉnh sau khi các quy tắc mặc định đã được kiểm tra
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $variants = $this->input('variants');
            
            if (is_array($variants)) {
                foreach ($variants as $key => $value) {
                    // Kiểm tra giá nhập phải nhỏ hơn hoặc bằng giá bán
                    if (isset($value['cost_price']) && isset($value['price']) && 
                        (float)$value['cost_price'] > (float)$value['price']) {
                        $validator->errors()->add(
                            "variants.{$key}.cost_price", 
                            'Giá nhập phải nhỏ hơn hoặc bằng giá bán'
                        );
                    }
                    
                    // Kiểm tra giá khuyến mãi nếu có
                    if (!empty($value['sale_price'])) {
                        // Giá khuyến mãi phải nhỏ hơn giá bán
                        if ((float)$value['sale_price'] >= (float)$value['price']) {
                            $validator->errors()->add(
                                "variants.{$key}.sale_price", 
                                'Giá khuyến mãi phải nhỏ hơn giá bán'
                            );
                        }
                        
                        // Giá khuyến mãi phải lớn hơn hoặc bằng giá nhập
                        if ((float)$value['sale_price'] < (float)$value['cost_price']) {
                            $validator->errors()->add(
                                "variants.{$key}.sale_price", 
                                'Giá khuyến mãi phải lớn hơn hoặc bằng giá nhập'
                            );
                        }
                    }
                }
            }
        });
    }
    
    /**
     * Các thông báo lỗi tùy chỉnh
     */
    public function messages(): array
    {
        return [
            'variants.*.cost_price.required' => 'Giá nhập không được để trống',
            'variants.*.cost_price.numeric' => 'Giá nhập phải là số',
            'variants.*.cost_price.min' => 'Giá nhập phải từ 10.000 VNĐ trở lên',
            'variants.*.cost_price.max' => 'Giá nhập tối đa là 10.000.000 VNĐ',
            
            'variants.*.price.required' => 'Giá bán không được để trống',
            'variants.*.price.numeric' => 'Giá bán phải là số',
            'variants.*.price.min' => 'Giá bán phải từ 10.000 VNĐ trở lên',
            'variants.*.price.max' => 'Giá bán tối đa là 10.000.000 VNĐ',
            
            'variants.*.quantity.required' => 'Số lượng không được để trống',
            'variants.*.quantity.numeric' => 'Số lượng phải là số',
            'variants.*.quantity.min' => 'Số lượng tối thiểu là 1',
            'variants.*.quantity.max' => 'Số lượng tối đa là 10.000',
            
            'variants.*.sale_price.numeric' => 'Giá khuyến mãi phải là số',
            'variants.*.sale_price.min' => 'Giá khuyến mãi phải từ 10.000 VNĐ trở lên',
            'variants.*.sale_price.max' => 'Giá khuyến mãi tối đa là 10.000.000 VNĐ',
            
            'variants.*.discount_start.date' => 'Ngày bắt đầu không hợp lệ',
            'variants.*.discount_start.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
            
            'variants.*.discount_end.date' => 'Ngày kết thúc không hợp lệ',
            'variants.*.discount_end.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ];
    }
}
