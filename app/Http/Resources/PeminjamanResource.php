<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'anggota_id' => $this->anggota_id,
            'buku_id' => $this->buku_id,
            'petugas_id' => $this->petugas_id,
            'tanggal_pinjam' => $this->tanggal_pinjam?->format('Y-m-d'),
            'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo?->format('Y-m-d'),
            'status' => $this->status,
            'is_terlambat' => $this->isTerlambat(),
            'estimasi_denda' => $this->status !== 'dikembalikan' ? $this->hitungDenda() : 0,
            'anggota' => new AnggotaResource($this->whenLoaded('anggota')),
            'buku' => new BukuResource($this->whenLoaded('buku')),
            'petugas' => new UserResource($this->whenLoaded('petugas')),
            'pengembalian' => new PengembalianResource($this->whenLoaded('pengembalian')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
