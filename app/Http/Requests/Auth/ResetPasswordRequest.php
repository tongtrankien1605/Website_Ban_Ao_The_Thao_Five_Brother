<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed|max:16'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email của bạn',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.exists' => 'Email bạn nhập không tồn tại, vui lòng thử lại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu đã nhập không khớp',
            'password.max' => 'Vui lòng nhập mật khẩu không quá 16 kí tự'
        ];
    }
}
