<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TentangSettingsSeeder::class,
            SejarahSlidersSeeder::class,
            ItemsSeeder::class,
            GaleriItemsSeeder::class,
            ArtikelSeeder::class,
            KknMemberSeeder::class,
        ]);
    }
}
