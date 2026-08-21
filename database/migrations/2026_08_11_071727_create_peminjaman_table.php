<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anggota_id')
                ->constrained('anggota')
                ->cascadeOnDelete();

            $table->foreignId('buku_id')
                ->constrained('buku')
                ->cascadeOnDelete();

            $table->foreignId('petugas_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('tanggal_pinjam')->index();
            $table->date('tanggal_jatuh_tempo')->index();

            $table->enum('status', [
                'dipinjam',
                'dikembalikan',
                'terlambat',
            ])->default('dipinjam')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
