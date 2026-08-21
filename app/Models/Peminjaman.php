<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'petugas_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function scopeDipinjam(Builder $query): Builder
    {
        return $query->whereIn('status', ['dipinjam', 'terlambat']);
    }

    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'dipinjam')
                    ->where('tanggal_jatuh_tempo', '<', now()->startOfDay());
            });
    }

    public function isTerlambat(?Carbon $tanggalPengembalian = null): bool
    {
        $tglKembali = $tanggalPengembalian ? $tanggalPengembalian->startOfDay() : now()->startOfDay();

        return $tglKembali->greaterThan($this->tanggal_jatuh_tempo->startOfDay());
    }

    public function hitungDenda(?Carbon $tanggalPengembalian = null, float $tarifPerHari = 1000.0): float
    {
        $tglKembali = $tanggalPengembalian ? $tanggalPengembalian->copy()->startOfDay() : now()->startOfDay();
        $tglJatuhTempo = $this->tanggal_jatuh_tempo->copy()->startOfDay();

        if ($tglKembali->greaterThan($tglJatuhTempo)) {
            $selisihHari = $tglKembali->diffInDays($tglJatuhTempo);

            return (float) ($selisihHari * $tarifPerHari);
        }

        return 0.0;
    }
}
