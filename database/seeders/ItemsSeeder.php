<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Item::truncate();

        // --- Data Keunikan ---
        Item::create(['tipe' => 'keunikan', 'judul' => '1Formasi Stalaktit Unik', 'deskripsi' => 'Formasi stalaktit dan stalagmit yang terbentuk secara alami selama jutaan tahun.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 1]);
        Item::create(['tipe' => 'keunikan', 'judul' => '2Sungai Bawah Tanah', 'deskripsi' => 'Aliran sungai bawah tanah yang jernih dan sejuk, menciptakan ekosistem unik.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 2]);
        Item::create(['tipe' => 'keunikan', 'judul' => '3Habitat Kelelawar', 'deskripsi' => 'Rumah bagi berbagai spesies kelelawar yang berperan penting dalam ekosistem.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 3]);
        Item::create(['tipe' => 'keunikan', 'judul' => '4Formasi Stalaktit Unik', 'deskripsi' => 'Formasi stalaktit dan stalagmit yang terbentuk secara alami selama jutaan tahun.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 4]);
        Item::create(['tipe' => 'keunikan', 'judul' => '5Formasi Stalaktit Unik', 'deskripsi' => 'Formasi stalaktit dan stalagmit yang terbentuk secara alami selama jutaan tahun.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 5]);
        Item::create(['tipe' => 'keunikan', 'judul' => '6Sungai Bawah Tanah', 'deskripsi' => 'Aliran sungai bawah tanah yang jernih dan sejuk, menciptakan ekosistem unik.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 6]);
        Item::create(['tipe' => 'keunikan', 'judul' => '7Habitat Kelelawar', 'deskripsi' => 'Rumah bagi berbagai spesies kelelawar yang berperan penting dalam ekosistem.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 7]);
        Item::create(['tipe' => 'keunikan', 'judul' => '8Formasi Stalaktit Unik', 'deskripsi' => 'Formasi stalaktit dan stalagmit yang terbentuk secara alami selama jutaan tahun.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 8]);
        Item::create(['tipe' => 'keunikan', 'judul' => '9Sungai Bawah Tanah', 'deskripsi' => 'Aliran sungai bawah tanah yang jernih dan sejuk, menciptakan ekosistem unik.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 9]);
        Item::create(['tipe' => 'keunikan', 'judul' => '10Habitat Kelelawar', 'deskripsi' => 'Rumah bagi berbagai spesies kelelawar yang berperan penting dalam ekosistem.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 10]);

        // --- Data Fasilitas ---
        Item::create(['tipe' => 'fasilitas', 'judul' => '1Area Parkir Luas', 'deskripsi' => 'Area parkir yang luas dan aman untuk kendaraan roda dua maupun roda empat.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 1]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '2Restoran & Café', 'deskripsi' => 'Restoran dengan menu khas daerah dan café dengan pemandangan indah.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 2]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '3Toko Souvenir', 'deskripsi' => 'Toko dengan berbagai oleh-oleh khas daerah dan merchandise Goa Sentono.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 3]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '4Area Parkir Luas', 'deskripsi' => 'Area parkir yang luas dan aman untuk kendaraan roda dua maupun roda empat.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 4]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '5Restoran & Café', 'deskripsi' => 'Restoran dengan menu khas daerah dan café dengan pemandangan indah.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 5]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '6Toko Souvenir', 'deskripsi' => 'Toko dengan berbagai oleh-oleh khas daerah dan merchandise Goa Sentono.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 6]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '7Area Parkir Luas', 'deskripsi' => 'Area parkir yang luas dan aman untuk kendaraan roda dua maupun roda empat.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 7]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '8Restoran & Café', 'deskripsi' => 'Restoran dengan menu khas daerah dan café dengan pemandangan indah.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 8]);
        Item::create(['tipe' => 'fasilitas', 'judul' => '9Toko Souvenir', 'deskripsi' => 'Toko dengan berbagai oleh-oleh khas daerah dan merchandise Goa Sentono.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'urutan' => 9]);

        // --- Data Wisata Sekitar ---
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '1Air Terjun Sekumpul', 'deskripsi' => 'Air terjun spektakuler dengan ketinggian 80 meter yang dikelilingi hutan tropis.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '5 km', 'urutan' => 1]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '2Candi Borobudur', 'deskripsi' => 'Candi Buddha terbesar di dunia dan situs warisan dunia UNESCO.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '45 km', 'urutan' => 2]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '3Keraton Yogyakarta', 'deskripsi' => 'Istana Sultan Yogyakarta yang masih aktif hingga kini.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '25 km', 'urutan' => 3]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '4Air Terjun Sekumpul', 'deskripsi' => 'Air terjun spektakuler dengan ketinggian 80 meter yang dikelilingi hutan tropis.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '5 km', 'urutan' => 4]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '5Candi Borobudur', 'deskripsi' => 'Candi Buddha terbesar di dunia dan situs warisan dunia UNESCO.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '45 km', 'urutan' => 5]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '6Keraton Yogyakarta', 'deskripsi' => 'Istana Sultan Yogyakarta yang masih aktif hingga kini.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '25 km', 'urutan' => 6]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '7Candi Borobudur', 'deskripsi' => 'Candi Buddha terbesar di dunia dan situs warisan dunia UNESCO.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '45 km', 'urutan' => 7]);
        Item::create(['tipe' => 'wisata_sekitar', 'judul' => '8Keraton Yogyakarta', 'deskripsi' => 'Istana Sultan Yogyakarta yang masih aktif hingga kini.', 'gambar_path' => 'https://pbs.twimg.com/media/EGf5x_-UwAEq00Z.jpg:large', 'info_tambahan' => '25 km', 'urutan' => 8]);
    }
}
