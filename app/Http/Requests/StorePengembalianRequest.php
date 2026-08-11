<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengembalianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peminjaman_id' => [
                'required',
                'exists:peminjaman,id',
                'unique:pengembalian,peminjaman_id',
            ],
            'petugas_id' => [
                'sometimes',
                'exists:users,id',
            ],
            'tanggal_pengembalian' => [
                'sometimes',
                'date',
            ],
            'denda' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'peminjaman_id.required' => 'Peminjaman ID wajib diisi.',
            'peminjaman_id.exists' => 'Data peminjaman tidak ditemukan.',
            'peminjaman_id.unique' => 'Buku untuk peminjaman ini sudah dikembalikan.',
            'petugas_id.exists' => 'Petugas tidak ditemukan.',
            'tanggal_pengembalian.date' => 'Tanggal pengembalian tidak valid.',
            'denda.numeric' => 'Denda harus berupa angka.',
            'denda.min' => 'Denda tidak boleh negatif.',
        ];
    }
}
