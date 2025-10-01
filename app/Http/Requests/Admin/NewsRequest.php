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
            'user_id' => 'required|uuid|exists:users,id',
            'judul' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'content' => 'required|string',
            'slug' => 'required|string|max:255|unique:beritas,slug|regex:/^[a-z0-9-]+$/',
            'status' => 'required|in:published,draf',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Penulis berita harus ditentukan.',
            'user_id.exists' => 'Penulis yang dipilih tidak valid.',
            'judul.required' => 'Judul berita tidak boleh kosong.',
            'judul.max' => 'Judul berita tidak boleh lebih dari 255 karakter.',
            'thumbnail.required' => 'Gambar thumbnail wajib diunggah.',
            'thumbnail.image' => 'File yang diunggah harus berupa gambar.',
            'thumbnail.mimes' => 'Format gambar yang diizinkan adalah jpeg, png, jpg, atau webp.',
            'thumbnail.max' => 'Ukuran gambar maksimal adalah 2 MB.',
            'content.required' => 'Isi konten berita tidak boleh kosong.',
            'slug.required' => 'Slug tidak boleh kosong.',
            'slug.unique' => 'Judul ini sudah digunakan, harap ganti dengan judul lain.',
            'slug.regex' => 'Format slug tidak valid (hanya huruf kecil, angka, dan tanda hubung).',
            'status.required' => 'Status publikasi harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];
    }
}
