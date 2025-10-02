<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class registerRequest extends FormRequest
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
            //
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|lowercase',
            'password' => 'required|string|min:8|max:255',
            'password_confirmation' => 'required|same:password',
            'role' => 'required|in:0,1'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'name.string' => 'Tên phải là một chuỗi ký tự',
            'name.min' => 'Tên phải có ít nhất 2 ký tự',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.lowercase' => 'Email phải là chữ thường',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu',
            'password_confirmation.same' => 'Mật khẩu không khớp nhau',
            'role.required' => 'Vui lòng chọn vai trò',
            'role.in' => 'Vai trò không hợp lệ',
        ];
    }
}
