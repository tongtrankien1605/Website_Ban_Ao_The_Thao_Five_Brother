<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $id = $this->user;
        $validate = [
            'name' => 'required|max:255',
            'phone_number' => [
                'required',
                'numeric',
                Rule::unique('users')->ignore($this->user)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user)
            ],
            'password' => 'required|confirmed|min:10|max:16',
            'avatar' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpg,jpeg,png,gif',
            ],
            'role' => 'required|exists:roles,id',
            'gender' => 'required',
            'birthday' => 'required'
        ];
        if ($id) {
            $validate['password'] = 'nullable|min:10|max:16';
        }
        return $validate;
    }
}
