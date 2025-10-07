<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artikel; // Import model Artikel
use Illuminate\Support\Str; // Import Str untuk membuat slug

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hapus data lama untuk memastikan tidak ada duplikasi saat seeder dijalankan lagi
        Artikel::truncate();

        // 2. Siapkan data artikel dalam bentuk array
        $artikels = [
            [
                'judul' => 'Panduan Lengkap Eksplorasi Goa Sentono untuk Pemula',
                'isi_konten' => 'Memasuki dunia bawah tanah yang menakjubkan di Goa Sentono memerlukan persiapan yang matang. Artikel ini akan memandu Anda dari persiapan awal, seperti peralatan yang harus dibawa, hingga tips keselamatan yang wajib diketahui setiap pengunjung. Pelajari rute terbaik, spot foto paling ikonik, dan cara menghormati ekosistem gua yang rapuh.Kami juga akan membahas sejarah geologis singkat tentang bagaimana gua ini terbentuk jutaan tahun yang lalu. Dengan panduan ini, petualangan Anda di Goa Sentono akan menjadi pengalaman yang aman, berkesan, dan tak terlupakan.',
                'gambar_path' => 'public/seeders/artikel/featured.jpg',
                'is_featured' => true, // Artikel ini akan menjadi artikel utama
                'published_at' => now()->subDays(1),
            ],
            [
                'judul' => 'Misteri dan Mitos yang Menyelimuti Goa Sentono',
                'isi_konten' => 'Setiap tempat bersejarah memiliki ceritanya sendiri, dan Goa Sentono tidak terkecuali. Jelajahi berbagai mitos dan legenda lokal yang telah diwariskan dari generasi ke generasi. Apakah benar gua ini adalah tempat pertapaan para sentono keraton? Temukan jawabannya di sini.',
                'gambar_path' => 'public/seeders/artikel/1.jpg',
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Kehidupan Kelelawar di Goa Sentono: Penjaga Ekosistem yang Penting',
                'isi_konten' => 'Mengenal berbagai spesies kelelawar yang menghuni Goa Sentono dan peran penting mereka dalam menjaga keseimbangan ekosistem gua. Pelajari tentang kebiasaan mereka, siklus hidup, dan mengapa keberadaan mereka sangat vital bagi lingkungan sekitar.',
                'gambar_path' => 'public/seeders/artikel/2.jpg',
                'published_at' => now()->subDays(7),
            ],
            [
                'judul' => 'Tips Fotografi di Dalam Gua: Menangkap Keindahan Goa Sentono',
                'isi_konten' => 'Memotret di kondisi minim cahaya seperti di dalam gua adalah sebuah tantangan. Dapatkan tips dan trik dari fotografer profesional tentang cara mengatur kamera Anda, teknik pencahayaan, dan komposisi terbaik untuk menghasilkan foto-foto spektakuler dari dalam Goa Sentono.',
                'gambar_path' => 'public/seeders/artikel/3.jpg',
                'published_at' => now()->subDays(10),
            ],
            [
                'judul' => 'Penemuan Artefak Kuno di Sekitar Kawasan Goa',
                'isi_konten' => 'Tim arkeolog baru-baru ini mengumumkan penemuan penting di dekat Goa Sentono. Beberapa artefak yang diperkirakan berasal dari zaman prasejarah ditemukan, memberikan bukti baru tentang kehidupan manusia purba di wilayah ini. Baca laporan lengkapnya di sini.',
                'gambar_path' => 'public/seeders/artikel/4.jpg',
                'published_at' => now()->subDays(12),
            ],
            [
                'judul' => 'Flora Unik yang Tumbuh di Mulut Goa Sentono',
                'isi_konten' => 'Kondisi mikroklimat di sekitar pintu masuk gua menciptakan habitat yang sempurna untuk beberapa spesies tumbuhan langka. Mari kita kenali lebih dekat flora unik yang hanya bisa ditemukan di kawasan Goa Sentono.',
                'gambar_path' => 'public/seeders/artikel/5.jpg',
                'published_at' => now()->subDays(15),
            ],
            [
                'judul' => 'Upaya Konservasi dan Pelestarian Goa Sentono',
                'isi_konten' => 'Bagaimana pengelola memastikan keindahan alam Goa Sentono tetap terjaga untuk generasi mendatang? Simak berbagai program konservasi yang sedang berjalan, mulai dari pembersihan rutin hingga penelitian ekologi.',
                'gambar_path' => 'public/seeders/artikel/6.jpg',
                'published_at' => now()->subDays(20),
            ],
            [
                'judul' => 'Menikmati Kuliner Lokal Setelah Berpetualang di Goa',
                'isi_konten' => 'Setelah lelah menjelajahi gua, saatnya mengisi perut! Berikut adalah rekomendasi 5 kuliner khas daerah yang wajib Anda coba di warung-warung sekitar Goa Sentono.',
                'gambar_path' => 'public/seeders/artikel/7.jpg',
                'published_at' => now()->subDays(22),
            ],
            [
                'judul' => 'Geologi Goa Sentono: Perjalanan Jutaan Tahun',
                'isi_konten' => 'Bagaimana formasi stalaktit dan stalagmit yang menakjubkan itu terbentuk? Pahami proses geologis di balik pembentukan Goa Sentono dalam artikel yang mudah dipahami ini.',
                'gambar_path' => 'public/seeders/artikel/8.jpg',
                'published_at' => now()->subDays(25),
            ],
            [
                'judul' => 'Acara Tahunan "Festival Cahaya Sentono" Kembali Digelar',
                'isi_konten' => 'Jangan lewatkan kemeriahan Festival Cahaya Sentono tahun ini! Acara yang akan menampilkan pertunjukan seni budaya dan instalasi lampu yang memukau di sekitar kawasan gua akan diadakan bulan depan.',
                'gambar_path' => 'public/seeders/artikel/9.jpg',
                'published_at' => now()->subDays(30),
            ],
            [
                'judul' => 'Kisah Para Penjelajah Pertama Goa Sentono',
                'isi_konten' => 'Dengarkan cerita dari para speleolog dan petualang lokal yang pertama kali memetakan bagian-bagian terdalam dari Goa Sentono pada tahun 1980-an. Sebuah kisah tentang keberanian dan penemuan.',
                'gambar_path' => 'public/seeders/artikel/10.jpg',
                'published_at' => now()->subDays(35),
            ],
            [
                'judul' => 'Aktivitas Seru untuk Keluarga di Kawasan Wisata Goa Sentono',
                'isi_konten' => 'Goa Sentono bukan hanya untuk para petualang. Temukan berbagai aktivitas ramah keluarga yang bisa Anda nikmati, mulai dari area piknik hingga jalur trekking ringan.',
                'gambar_path' => 'public/seeders/artikel/11.jpg',
                'published_at' => now()->subDays(40),
            ],
            [
                'judul' => 'Pentingnya Menjaga Kebersihan Saat Mengunjungi Tempat Wisata Alam',
                'isi_konten' => 'Sebuah pengingat bagi kita semua tentang tanggung jawab sebagai pengunjung. Pelajari dampak sampah terhadap ekosistem gua dan bagaimana kita bisa menjadi wisatawan yang lebih baik.',
                'gambar_path' => 'public/seeders/artikel/12.jpg',
                'published_at' => now()->subDays(45),
            ],
            [
                'judul' => 'Goa Sentono dalam Angka: Fakta Menarik yang Mungkin Belum Anda Tahu',
                'isi_konten' => 'Berapa panjang total lorong gua yang sudah terpetakan? Berapa usia formasi batuan tertua? Temukan jawaban dan fakta-fakta menarik lainnya tentang Goa Sentono di sini.',
                'gambar_path' => 'public/seeders/artikel/13.jpg',
                'published_at' => now()->subDays(50),
            ],
            [
                'judul' => 'Melihat Bintang dari Bukit di Atas Goa Sentono',
                'isi_konten' => 'Selain keindahan bawah tanahnya, kawasan Goa Sentono juga menawarkan pemandangan langit malam yang spektakuler. Jauh dari polusi cahaya kota, ini adalah tempat yang sempurna untuk stargazing.',
                'gambar_path' => 'public/seeders/artikel/14.jpg',
                'published_at' => now()->subDays(60),
            ],
        ];

        // 3. Looping melalui array dan membuat data di database
        foreach ($artikels as $artikel) {
            Artikel::create([
                'judul' => $artikel['judul'],
                'slug' => Str::slug($artikel['judul']), // Membuat slug secara otomatis
                'isi_konten' => $artikel['isi_konten'],
                'gambar_path' => $artikel['gambar_path'],
                'is_featured' => $artikel['is_featured'] ?? false, // Default is_featured ke false jika tidak di-set
                'published_at' => $artikel['published_at'],
            ]);
        }
    }
}
