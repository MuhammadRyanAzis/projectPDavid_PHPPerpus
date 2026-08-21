<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_login_via_api(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@perpustakaan.test',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.email', 'admin@perpustakaan.test');
    }

    public function test_kategori_api_crud(): void
    {
        // Index
        $response = $this->getJson('/api/kategori');
        $response->assertStatus(200);

        // Store
        $storeResponse = $this->postJson('/api/kategori', [
            'nama_kategori' => 'Kecerdasan Buatan',
        ]);
        $storeResponse->assertStatus(201)
            ->assertJsonPath('data.nama_kategori', 'Kecerdasan Buatan');

        $id = $storeResponse->json('data.id');

        // Show
        $this->getJson("/api/kategori/{$id}")
            ->assertStatus(200)
            ->assertJsonPath('data.nama_kategori', 'Kecerdasan Buatan');

        // Update
        $this->putJson("/api/kategori/{$id}", [
            'nama_kategori' => 'AI & Machine Learning',
        ])->assertStatus(200)
            ->assertJsonPath('data.nama_kategori', 'AI & Machine Learning');

        // Delete
        $this->deleteJson("/api/kategori/{$id}")
            ->assertStatus(200);
    }

    public function test_buku_api_crud(): void
    {
        $kategori = Kategori::first();

        // Store
        $response = $this->postJson('/api/buku', [
            'kategori_id' => $kategori->id,
            'judul' => 'Clean Code Principles',
            'isbn' => '9780000000099',
            'stok' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.judul', 'Clean Code Principles');

        $bukuId = $response->json('data.id');

        // Update
        $this->putJson("/api/buku/{$bukuId}", [
            'kategori_id' => $kategori->id,
            'judul' => 'Clean Code Principles - Updated',
            'isbn' => '9780000000099',
            'stok' => 12,
        ])->assertStatus(200)
            ->assertJsonPath('data.judul', 'Clean Code Principles - Updated');
    }

    public function test_peminjaman_dan_pengembalian_buku_flow(): void
    {
        $anggota = Anggota::where('status', 'aktif')->first();
        $buku = Buku::where('stok', '>', 0)->first();
        $stokAwal = $buku->stok;

        // 1. Process Peminjaman
        $peminjamanResponse = $this->postJson('/api/peminjaman', [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
        ]);

        $peminjamanResponse->assertStatus(201)
            ->assertJsonPath('data.status', 'dipinjam');

        $peminjamanId = $peminjamanResponse->json('data.id');

        // Verify stock decreased
        $this->assertEquals($stokAwal - 1, $buku->fresh()->stok);

        // 2. Process Pengembalian
        $pengembalianResponse = $this->postJson('/api/pengembalian', [
            'peminjaman_id' => $peminjamanId,
            'tanggal_pengembalian' => now()->format('Y-m-d'),
        ]);

        $pengembalianResponse->assertStatus(201)
            ->assertJsonPath('data.peminjaman_id', $peminjamanId);

        // Verify stock restored
        $this->assertEquals($stokAwal, $buku->fresh()->stok);

        // Verify borrowing status updated
        $peminjaman = Peminjaman::find($peminjamanId);
        $this->assertEquals('dikembalikan', $peminjaman->status);
    }
}
