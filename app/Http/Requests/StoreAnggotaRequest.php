<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                'unique:anggota,user_id',
            ],
            'nomor_anggota' => [
                'required',
                'string',
                'max:50',
                'unique:anggota,nomor_anggota',
            ],
            'alamat' => [
                'required',
                'string',
                'max:500',
            ],
            'status' => [
                'sometimes',
                'in:aktif,nonaktif',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID wajib dipilih.',
            'user_id.exists' => 'User ID tidak ditemukan.',
            'user_id.unique' => 'User ini sudah terdaftar sebagai anggota.',
            'nomor_anggota.required' => 'Nomor anggota wajib diisi.',
            'nomor_anggota.unique' => 'Nomor anggota sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'status.in' => 'Status harus aktif atau nonaktif.',
        ];
    }
}
