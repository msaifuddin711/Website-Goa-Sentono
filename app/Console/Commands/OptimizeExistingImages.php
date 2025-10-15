<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artikel;
use App\Models\GaleriItem;
use App\Models\Item;
use App\Models\SejarahSlider;
use App\Models\KknMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Throwable;

class OptimizeExistingImages extends Command
{
    /**
     * Nama dan signature dari console command.
     */
    protected $signature = 'images:optimize';

    /**
     * Deskripsi dari console command (diperbarui).
     */
    protected $description = 'Optimalkan (resize dan compress) semua gambar yang sudah ada untuk semua model';

    /**
     * Jalankan console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai proses optimasi untuk semua gambar di website...');

        // Inisialisasi Image Manager sekali saja
        $manager = new ImageManager(new Driver());

        // Proses gambar untuk setiap model dengan pengaturan yang sesuai
        $this->processModelImages(Artikel::class, 'Artikel', 1200, 75, $manager);
        $this->processModelImages(GaleriItem::class, 'Galeri', 1920, 80, $manager);
        $this->processModelImages(SejarahSlider::class, 'Slider Sejarah', 1920, 80, $manager);
        $this->processModelImages(KknMember::class, 'Anggota KKN', 800, 80, $manager);

        // Untuk model Item, kita perlu memprosesnya per tipe
        $this->processModelImages(Item::class, 'Keunikan', 1200, 80, $manager, ['tipe' => 'keunikan']);
        $this->processModelImages(Item::class, 'Fasilitas', 1200, 80, $manager, ['tipe' => 'fasilitas']);
        $this->processModelImages(Item::class, 'Wisata Sekitar', 1200, 80, $manager, ['tipe' => 'wisata_sekitar']);

        $this->info('✅ Selesai! Semua gambar telah berhasil dioptimalkan.');
        return 0;
    }

    /**
     * Fungsi generik untuk memproses gambar dari sebuah model, dengan filter opsional.
     *
     * @param string $modelClass Class dari model (e.g., Artikel::class)
     * @param string $displayName Nama untuk ditampilkan di console (e.g., 'Artikel')
     * @param int $maxWidth Lebar maksimal gambar
     * @param int $quality Kualitas kompresi (0-100)
     * @param ImageManager $manager Instance dari ImageManager
     * @param array|null $filter Array untuk klausa where (e.g., ['tipe' => 'keunikan'])
     */
    protected function processModelImages(string $modelClass, string $displayName, int $maxWidth, int $quality, ImageManager $manager, array $filter = null)
    {
        $this->newLine();
        $this->info("Memproses gambar untuk: " . $displayName);
        
        // Cek jika class model ada
        if (!class_exists($modelClass)) {
            $this->error("Model [{$modelClass}] tidak ditemukan. Dilewati.");
            return;
        }

        // Bangun query dasar
        $query = $modelClass::query();

        // Terapkan filter jika ada
        if (!is_null($filter)) {
            $query->where($filter);
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            $this->warn('Tidak ada item ditemukan. Dilewati.');
            return;
        }

        $progressBar = $this->output->createProgressBar($items->count());
        $progressBar->start();

        foreach ($items as $item) {
            // Cek beberapa nama kolom gambar yang umum
            $imagePath = $item->gambar_path ?? $item->photo_path ?? $item->gambar ?? null;

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                try {
                    $fileContent = Storage::disk('public')->get($imagePath);

                    $image = $manager->read($fileContent);
                    $image->scale(width: $maxWidth);
                    $encodedImage = $image->toJpeg($quality);

                    Storage::disk('public')->put($imagePath, $encodedImage);

                } catch (Throwable $e) {
                    $this->error("\n Gagal memproses gambar untuk {$displayName} ID: {$item->id}. Error: " . $e->getMessage());
                }
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
    }
}