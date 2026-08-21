<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_id')
                ->constrained('kategori')
                ->cascadeOnDelete();

            $table->string('judul')->index();
            $table->string('isbn')->unique();
            $table->integer('stok')->default(0)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
