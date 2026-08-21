<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $anggotaId = $this->route('anggota')?->id ?? $this->route('anggota');

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                'unique:anggota,user_id,'.$anggotaId,
            ],
            'nomor_anggota' => [
                'required',
                'string',
                'max:50',
                'unique:anggota,nomor_anggota,'.$anggotaId,
            ],
            'alamat' => [
                'required',
                'string',
                'max:500',
            ],
            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID wajib dipilih.',
            'user_id.exists' => 'User ID tidak ditemukan.',
            'user_id.unique' => 'User ini sudah terdaftar sebagai anggota lain.',
            'nomor_anggota.required' => 'Nomor anggota wajib diisi.',
            'nomor_anggota.unique' => 'Nomor anggota sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'status.in' => 'Status harus aktif atau nonaktif.',
        ];
    }
}
