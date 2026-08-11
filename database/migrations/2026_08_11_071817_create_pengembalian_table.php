<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peminjaman_id')
                ->unique()
                ->constrained('peminjaman')
                ->cascadeOnDelete();

            $table->foreignId('petugas_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('tanggal_pengembalian');

            $table->decimal('denda', 12, 2)
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};