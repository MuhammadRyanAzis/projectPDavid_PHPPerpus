<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'anggota_id' => [
                'required',
                'exists:anggota,id',
            ],
            'buku_id' => [
                'required',
                'exists:buku,id',
            ],
            'petugas_id' => [
                'sometimes',
                'exists:users,id',
            ],
            'tanggal_pinjam' => [
                'sometimes',
                'date',
            ],
            'tanggal_jatuh_tempo' => [
                'sometimes',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'anggota_id.exists' => 'Anggota tidak ditemukan.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'buku_id.exists' => 'Buku tidak ditemukan.',
            'petugas_id.exists' => 'Petugas tidak ditemukan.',
            'tanggal_pinjam.date' => 'Tanggal pinjam tidak valid.',
            'tanggal_jatuh_tempo.date' => 'Tanggal jatuh tempo tidak valid.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus sama dengan atau setelah tanggal pinjam.',
        ];
    }
}
