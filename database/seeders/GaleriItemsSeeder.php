<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GaleriItem;

class GaleriItemsSeeder extends Seeder
{
    public function run(): void
    {
        GaleriItem::truncate();

        $items = [
            ['judul' => 'Stalaktit Menawan', 'deskripsi' => 'Pemandangan stalaktit di dalam gua.','gambar_path' => 'public/seeders/galeri/1.jpg'],
            ['judul' => 'Pintu Masuk Gua', 'deskripsi' => 'Gerbang menuju keajaiban alam.', 'gambar_path' => 'public/seeders/galeri/2.jpg'],
            ['judul' => 'Aliran Sungai Purba', 'deskripsi' => 'Sungai yang mengalir di dasar gua.','gambar_path' => 'public/seeders/galeri/3.jpg'],
            ['judul' => 'Pemandangan dari Atas', 'deskripsi' => 'Kawasan Goa Sentono dilihat dari drone.', 'gambar_path' => 'public/seeders/galeri/4.jpg'],
            ['judul' => 'Fauna Gua', 'deskripsi' => 'Kelelawar yang bergelantungan di atap gua.', 'gambar_path' => 'public/seeders/galeri/5.jpg'],
            ['judul' => 'Formasi Batuan Unik', 'deskripsi' => 'Bebatuan yang terbentuk secara alami.','gambar_path' => 'public/seeders/galeri/6.jpg'],
            ['judul' => 'Stalaktit Menawan', 'deskripsi' => 'Pemandangan stalaktit di dalam gua.','gambar_path' => 'public/seeders/galeri/1.jpg'],
            ['judul' => 'Pintu Masuk Gua', 'deskripsi' => 'Gerbang menuju keajaiban alam.', 'gambar_path' => 'public/seeders/galeri/2.jpg'],
            ['judul' => 'Aliran Sungai Purba', 'deskripsi' => 'Sungai yang mengalir di dasar gua.','gambar_path' => 'public/seeders/galeri/3.jpg'],
            ['judul' => 'Pemandangan dari Atas', 'deskripsi' => 'Kawasan Goa Sentono dilihat dari drone.', 'gambar_path' => 'public/seeders/galeri/4.jpg'],
            ['judul' => 'Fauna Gua', 'deskripsi' => 'Kelelawar yang bergelantungan di atap gua.', 'gambar_path' => 'public/seeders/galeri/5.jpg'],
            ['judul' => 'Formasi Batuan Unik', 'deskripsi' => 'Bebatuan yang terbentuk secara alami.','gambar_path' => 'public/seeders/galeri/6.jpg'],
            ['judul' => 'Stalaktit Menawan', 'deskripsi' => 'Pemandangan stalaktit di dalam gua.','gambar_path' => 'public/seeders/galeri/1.jpg'],
            ['judul' => 'Pintu Masuk Gua', 'deskripsi' => 'Gerbang menuju keajaiban alam.', 'gambar_path' => 'public/seeders/galeri/2.jpg'],
            ['judul' => 'Aliran Sungai Purba', 'deskripsi' => 'Sungai yang mengalir di dasar gua.','gambar_path' => 'public/seeders/galeri/3.jpg'],
            ['judul' => 'Pemandangan dari Atas', 'deskripsi' => 'Kawasan Goa Sentono dilihat dari drone.', 'gambar_path' => 'public/seeders/galeri/4.jpg'],
            ['judul' => 'Fauna Gua', 'deskripsi' => 'Kelelawar yang bergelantungan di atap gua.', 'gambar_path' => 'public/seeders/galeri/5.jpg'],
            ['judul' => 'Formasi Batuan Unik', 'deskripsi' => 'Bebatuan yang terbentuk secara alami.','gambar_path' => 'public/seeders/galeri/6.jpg'],
            ['judul' => 'Stalaktit Menawan', 'deskripsi' => 'Pemandangan stalaktit di dalam gua.','gambar_path' => 'public/seeders/galeri/1.jpg'],
            ['judul' => 'Pintu Masuk Gua', 'deskripsi' => 'Gerbang menuju keajaiban alam.', 'gambar_path' => 'public/seeders/galeri/2.jpg'],
            ['judul' => 'Aliran Sungai Purba', 'deskripsi' => 'Sungai yang mengalir di dasar gua.','gambar_path' => 'public/seeders/galeri/3.jpg'],
            ['judul' => 'Pemandangan dari Atas', 'deskripsi' => 'Kawasan Goa Sentono dilihat dari drone.', 'gambar_path' => 'public/seeders/galeri/4.jpg'],
            ['judul' => 'Fauna Gua', 'deskripsi' => 'Kelelawar yang bergelantungan di atap gua.', 'gambar_path' => 'public/seeders/galeri/5.jpg'],
            ['judul' => 'Formasi Batuan Unik', 'deskripsi' => 'Bebatuan yang terbentuk secara alami.','gambar_path' => 'public/seeders/galeri/6.jpg'],
        ];

        foreach ($items as $item) {
            GaleriItem::create($item);
        }
    }
}
