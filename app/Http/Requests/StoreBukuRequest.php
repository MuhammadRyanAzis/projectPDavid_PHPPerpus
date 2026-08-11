<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => [
                'required',
                'exists:kategori,id',
            ],
            'judul' => [
                'required',
                'string',
                'max:255',
            ],
            'isbn' => [
                'required',
                'string',
                'max:100',
                'unique:buku,isbn',
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak ditemukan.',
            'judul.required' => 'Judul buku wajib diisi.',
            'isbn.required' => 'ISBN wajib diisi.',
            'isbn.unique' => 'ISBN sudah terdaftar.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok buku harus berupa angka bulat.',
            'stok.min' => 'Stok buku minimal 0.',
        ];
    }
}
