<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengembalianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'peminjaman_id' => $this->peminjaman_id,
            'petugas_id' => $this->petugas_id,
            'tanggal_pengembalian' => $this->tanggal_pengembalian?->format('Y-m-d'),
            'denda' => (float) $this->denda,
            'peminjaman' => new PeminjamanResource($this->whenLoaded('peminjaman')),
            'petugas' => new UserResource($this->whenLoaded('petugas')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
