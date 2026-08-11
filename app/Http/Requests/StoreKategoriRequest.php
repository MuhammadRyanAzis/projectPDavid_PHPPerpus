<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                'unique:kategori,nama_kategori',
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
