<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsPhotosRequest extends FormRequest
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
            'news_id' => 'required|exists:news,id',
            'image_name' => 'required|array',
            'image_name.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'image_name.max' => 'Anda hanya bisa mengunggah maksimal 5 gambar sekaligus.',
            'image_name.*.image' => 'File yang diunggah harus berupa gambar.',
            'image_name.*.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'image_name.*.max' => 'Ukuran setiap gambar tidak boleh lebih dari 2 MB.',
        ];
    }
}
