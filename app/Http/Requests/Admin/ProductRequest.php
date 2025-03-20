<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $id = $this->product;
        $validate = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('products', 'name')->ignore($id),
            ],
            "description" => "nullable",
            "id_category" => "required|integer|exists:categories,id",
            "id_brand" => "required|integer|exists:brands,id",
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            "variants.*.name" => "required|max:255",
            "variants.*.price" => "required|numeric|min:0|max:99999999",
            "variants.*.sale_price" => "nullable|numeric|min:0|lte:variants.*.price",
            "variants.*.quantity" => "required|numeric|min:0|max:10000",
            "variants.*.image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
            "images" => "nullable|min:1|max:10",
            "images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ];
        if ($id) {
            $validate['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            $validate["variants.*.image"] = "image|mimes:jpeg,png,jpg,gif|max:2048";
        }
        return $validate;
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',

            'id_category.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'id_category.integer' => 'Danh mục sản phẩm không hợp lệ.',
            'id_category.exists' => 'Danh mục sản phẩm không tồn tại.',

            'id_brand.required' => 'Vui lòng chọn thương hiệu sản phẩm.',
            'id_brand.integer' => 'Thương hiệu sản phẩm không hợp lệ.',
            'id_brand.exists' => 'Thương hiệu sản phẩm không tồn tại.',

            'image.required' => 'Ảnh đại diện không được để trống.',
            'image.image' => 'Ảnh đại diện phải là định dạng hình ảnh.',
            'image.mimes' => 'Ảnh đại diện chỉ chấp nhận các định dạng jpeg, png, jpg, gif.',
            'image.max' => 'Ảnh đại diện không được vượt quá 2MB.',

            'variants.*.name.required' => 'Tên biến thể không được để trống.',
            'variants.*.name.max' => 'Tên biến thể không được vượt quá 255 ký tự.',

            'variants.*.price.required' => 'Giá của biến thể không được để trống.',
            'variants.*.price.numeric' => 'Giá của biến thể phải là số.',
            'variants.*.price.min' => 'Giá của biến thể phải lớn hơn 0.',
            'variants.*.price.max' => 'Giá của biến thể không được lớn hơn 99999999.',

            'variants.*.sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'variants.*.sale_price.min' => 'Giá khuyến mãi không được nhỏ hơn 0.',
            'variants.*.sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',

            'variants.*.quantity.required' => 'Số lượng biến thể không được để trống.',
            'variants.*.quantity.numeric' => 'Số lượng biến thể phải là số.',
            'variants.*.quantity.min' => 'Số lượng biến thể phải lớn hơn 0.',
            'variants.*.quantity.max' => 'Số lượng biến thể không được lớn hơn 10000.',

            'variants.*.image.required' => 'Ảnh của biến thể không được để trống.',
            'variants.*.image.image' => 'Ảnh của biến thể phải là hình ảnh.',
            'variants.*.image.mimes' => 'Ảnh của biến thể chỉ chấp nhận jpeg, png, jpg, gif.',
            'variants.*.image.max' => 'Ảnh của biến thể không được vượt quá 2MB.',

            'images.min' => 'Bạn phải tải lên ít nhất một ảnh.',
            'images.max' => 'Bạn chỉ có thể tải lên tối đa 10 ảnh.',
            'images.*.image' => 'Ảnh sản phẩm phải là hình ảnh hợp lệ.',
            'images.*.mimes' => 'Ảnh sản phẩm chỉ chấp nhận jpeg, png, jpg, gif.',
            'images.*.max' => 'Ảnh sản phẩm không được vượt quá 2MB.',
        ];
    }
}
