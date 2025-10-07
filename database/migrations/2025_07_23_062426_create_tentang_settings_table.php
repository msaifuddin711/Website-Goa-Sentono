<!-- database\migrations\2025_07_23_062426_create_tentang_settings_table.php -->

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TentangSetting;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tentang_settings', function (Blueprint $table) {
            $table->string('key')->primary(); // Kunci sebagai primary key
            $table->text('value')->nullable();
            $table->string('label'); // Label yang mudah dibaca untuk admin
            $table->timestamps();
        });

        // Isi data awal agar form di admin tidak kosong
        TentangSetting::insert([
            ['key' => 'sejarah_deskripsi', 'value' => 'Isi deskripsi sejarah di sini...', 'label' => 'Deskripsi Sejarah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'video_url', 'value' => 'https://www.youtube.com/embed/your-video-id', 'label' => 'URL Video YouTube', 'created_at' => now( ), 'updated_at' => now()],
            ['key' => 'peta_model_src', 'value' => 'models/nama-model.glb', 'label' => 'Path Model 3D Peta', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tentang_settings');
    }
};
