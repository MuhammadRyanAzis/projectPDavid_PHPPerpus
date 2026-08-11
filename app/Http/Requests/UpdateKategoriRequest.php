<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kategoriId = $this->route('kategori')?->id ?? $this->route('kategori');

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                'unique:kategori,nama_kategori,' . $kategoriId,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah terdaftar.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
        ];
    }
}
