<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KknMember;
use Illuminate\Support\Facades\DB;

class KknMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Kosongkan tabel terlebih dahulu untuk menghindari duplikasi data
        //    jika seeder dijalankan lebih dari sekali.
        DB::table('kkn_members')->truncate();

        // 2. Definisikan data anggota dalam bentuk array.
        //    Ini membuatnya mudah untuk dikelola.
        $members = [
            // Dosen Pembimbing Lapangan
            [
                'name' => 'Dr. Nama Dosen, S.T., M.Eng.',
                'role' => 'Dosen Pembimbing Lapangan',
                'photo_path' => 'kkn-photos/dpl.jpg', // Ganti dengan path foto asli
                'is_dpl' => true,
                'order' => 0, // DPL selalu di urutan pertama
            ],
            // Anggota Tim KKN
            [
                'name' => 'Budi Santoso',
                'role' => 'Ketua',
                'photo_path' => 'kkn-photos/ketua.jpg',
                'is_dpl' => false,
                'order' => 1,
            ],
            [
                'name' => 'Citra Lestari',
                'role' => 'Wakil Ketua',
                'photo_path' => 'kkn-photos/wakil.jpg',
                'is_dpl' => false,
                'order' => 2,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'role' => 'Sekretaris',
                'photo_path' => 'kkn-photos/sekretaris.jpg',
                'is_dpl' => false,
                'order' => 3,
            ],
            [
                'name' => 'Dewi Anggraini',
                'role' => 'Bendahara',
                'photo_path' => 'kkn-photos/bendahara.jpg',
                'is_dpl' => false,
                'order' => 4,
            ],
            [
                'name' => 'Eko Prasetyo',
                'role' => 'Publikasi, Desain, dan Dokumentasi',
                'photo_path' => 'kkn-photos/Publikasi, Desain, dan Dokumentasi.jpg',
                'is_dpl' => false,
                'order' => 5,
            ],
            [
                'name' => 'Fitriani',
                'role' => 'Hubungan Masyarakat',
                'photo_path' => 'kkn-photos/Hubungan Masyarakat.jpg',
                'is_dpl' => false,
                'order' => 6,
            ],
            [
                'name' => 'Gilang Ramadhan',
                'role' => 'Logistik',
                'photo_path' => 'kkn-photos/logistik.jpg',
                'is_dpl' => false,
                'order' => 7,
            ],
        ];

        // 3. Loop melalui array dan masukkan setiap anggota ke dalam database.
        foreach ($members as $member) {
            KknMember::create($member);
        }
    }
}
