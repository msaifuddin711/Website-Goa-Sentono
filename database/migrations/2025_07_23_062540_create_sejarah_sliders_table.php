<!-- database\migrations\2025_07_23_062540_create_sejarah_sliders_table.php -->

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sejarah_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('gambar_path'); // Hanya path di storage
            $table->string('alt_text')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sejarah_sliders');
    }
};
