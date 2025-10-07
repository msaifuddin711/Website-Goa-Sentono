<?php

// xxxx_xx_xx_xxxxxx_create_artikels_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique(); // URL-friendly, unik
            $table->longText('isi_konten'); // Isi artikel lengkap, bisa sangat panjang
            $table->string('gambar_path'); // Path ke gambar utama di storage
            $table->boolean('is_featured')->default(false); // Penanda untuk artikel utama
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
