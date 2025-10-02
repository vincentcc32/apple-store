<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class productRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'category' => 'required|integer|exists:categories,id',
        ];

        // Nếu là tạo mới (POST), ảnh là bắt buộc
        if ($this->isMethod('post')) {
            $rules['thumbnail'] = 'required|file|mimes:jpeg,png,jpg|max:2048';
        }
        // Nếu là cập nhật (PUT/PATCH), ảnh là tùy chọn
        else {
            $rules['thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề sản phẩm',
            'title.string' => 'Tiêu đề sản phẩm phải là một chuỗi ký tự',
            'title.min' => 'Tiêu đề sản phẩm phải có ít nhất 3 ký tự',
            'title.max' => 'Tiêu đề sản phẩm không được vượt quá 255 ký tự',

            'description.required' => 'Vui lòng nhập mô tả sản phẩm',
            'description.string' => 'Mô tả sản phẩm phải là một chuỗi ký tự',

            'thumbnail.required' => 'Vui lòng chọn ảnh (bắt buộc khi tạo sản phẩm)',
            'thumbnail.image' => 'Ảnh phải là một tệp',
            'thumbnail.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg',
            'thumbnail.max' => 'Ảnh không được vượt quá 2MB',

            'status.required' => 'Vui lòng chọn trạng thái',
            'status.boolean' => 'Trạng thái phải là một giá trị boolean',

            'category.required' => 'Vui lòng chọn danh mục',
            'category.integer' => 'Danh mục phải là một số nguyên',
            'category.exists' => 'Danh mục không tồn tại',
        ];
    }
}
