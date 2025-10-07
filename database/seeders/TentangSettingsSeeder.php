<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TentangSetting;

class TentangSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk memastikan kebersihan data
        // TentangSetting::truncate(); // Opsional, jika Anda ingin memulai dari nol setiap kali seeding

        TentangSetting::updateOrInsert(
            ['key' => 'sejarah_deskripsi'],
            [
                'value' => "Goa Sentono memiliki sejarah yang sangat panjang dalam pembentukan geologisnya. Jutaan tahun yang lalu, kawasan ini merupakan dasar laut yang kemudian terangkat akibat pergerakan tektonik. Proses karstifikasi yang berlangsung selama ribuan tahun menciptakan formasi gua yang menakjubkan ini.\n\nSecara historis, gua ini telah dikenal oleh masyarakat lokal sejak berabad-abad lalu. Nama “Sentono” sendiri berasal dari bahasa Jawa yang memiliki makna filosofis mendalam, mencerminkan keagungan dan kesakralan tempat ini dalam kepercayaan setempat.",
                'label' => 'Deskripsi Sejarah'
            ]
        );

        TentangSetting::updateOrInsert(
            ['key' => 'video_url'],
            [
                'value' => 'https://www.youtube.com/embed/CJzv9Fq0ju8?rel=0&modestbranding=1',
                'label' => 'URL Video YouTube'
            ]
         );

        TentangSetting::updateOrInsert(
            ['key' => 'peta_model_src'],
            [
                'value' => 'models/Ujicoba 1.glb',
                'label' => 'Path Model 3D Peta'
            ]
        );
    }
}
