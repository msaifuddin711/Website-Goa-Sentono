<!-- database\migrations\2025_07_23_062612_create_items_table.php -->

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['keunikan', 'fasilitas', 'wisata_sekitar']);
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('gambar_path'); // Hanya path di storage
            $table->string('info_tambahan')->nullable(); // Untuk jarak pada wisata
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
