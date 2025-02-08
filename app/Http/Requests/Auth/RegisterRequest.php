<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|max:255',
            'phone_number' => 'required|numeric|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|max:16'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên của bạn',
            'name.max' => 'Vui lòng nhập tên không quá 255 kí tự',
            'email.required' => 'Vui lòng nhập email của bạn',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.unique' => 'Email của bạn đã được đăng ký, vui lòng đăng nhập',
            'phone_number.required' => 'Vui lòng nhập số điện thoại của bạn',
            'phone_number.numeric' => 'Vui lòng nhập đúng định dạng số điện thoại',
            'phone_number.unique' => 'Số điện thoại này đã được sử dụng, vui lòng chọn số điện thoại khác',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu đã nhập không khớp',
            'password.max' => 'Vui lòng nhập mật khẩu không quá 16 kí tự'
        ];
    }
}
