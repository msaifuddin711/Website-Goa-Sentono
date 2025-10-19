<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SejarahSlider;

class SejarahSlidersSeeder extends Seeder
{
    public function run(): void
    {
        SejarahSlider::truncate();

        $sliders = [
            ['gambar_path' => 'public/seeders/sejarah/1.jpg', 'alt_text' => 'Formasi Geologis', 'urutan' => 1],
            ['gambar_path' => 'public/seeders/sejarah/2.jpg', 'alt_text' => 'Proses Karstifikasi', 'urutan' => 2],
            ['gambar_path' => 'public/seeders/sejarah/3.jpg', 'alt_text' => 'Warisan Budaya', 'urutan' => 3],
        ];

        foreach ($sliders as $slider) {
            SejarahSlider::create($slider);
        }
    }
}
