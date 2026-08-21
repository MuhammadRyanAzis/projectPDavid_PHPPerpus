<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kategori_id',
        'judul',
        'isbn',
        'stok',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function scopeAdaStok(Builder $query): Builder
    {
        return $query->where('stok', '>', 0);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where('judul', 'like', "%{$term}%")
                ->orWhere('isbn', 'like', "%{$term}%")
                ->orWhereHas('kategori', function ($k) use ($term) {
                    $k->where('nama_kategori', 'like', "%{$term}%");
                });
        });
    }

    public function kurangiStok(int $jumlah = 1): bool
    {
        if ($this->stok < $jumlah) {
            return false;
        }

        $this->decrement('stok', $jumlah);

        return true;
    }

    public function tambahStok(int $jumlah = 1): void
    {
        $this->increment('stok', $jumlah);
    }
}
