<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'judul' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'content' => 'required|string|nullable',
            'status' => 'required|in:Published,Draf',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul berita tidak boleh kosong.',
            'judul.max' => 'Judul berita tidak boleh lebih dari 255 karakter.',
            'thumbnail.required' => 'Gambar thumbnail wajib diunggah.',
            'thumbnail.image' => 'File yang diunggah harus berupa gambar.',
            'thumbnail.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau webp.',
            'thumbnail.max' => 'Ukuran gambar maksimal adalah 2 MB.',
            'status.required' => 'Status publikasi harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];
    }
}
