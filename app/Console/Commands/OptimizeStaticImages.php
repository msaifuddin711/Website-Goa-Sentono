<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Component\Finder\Finder; // <-- Gunakan komponen Finder yang lebih kuat
use Throwable;

class OptimizeStaticImages extends Command
{
    /**
     * Nama dan signature dari console command.
     */
    protected $signature = 'images:optimize-static';

    /**
     * Deskripsi dari console command.
     */
    protected $description = 'Optimalkan semua gambar statis di dalam folder public/images';

    /**
     * Jalankan console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai proses optimasi gambar statis di public/images...');

        // Inisialisasi Image Manager
        $manager = new ImageManager(new Driver());
        
        // --- PERUBAHAN DI SINI: Gunakan public_path() untuk menunjuk folder yang benar ---
        $imageFolderPath = public_path('images');

        // Cek apakah direktori 'public/images' ada
        if (!is_dir($imageFolderPath)) {
            $this->error("Direktori '{$imageFolderPath}' tidak ditemukan. Pastikan folder 'public/images' ada.");
            return 1; // Keluar dengan status error
        }
        
        // Gunakan Finder untuk mencari semua file gambar secara rekursif
        $finder = new Finder();
        $finder->files()
               ->in($imageFolderPath)
               ->name('/\.(jpg|jpeg|png|gif|webp)$/i'); // Cari file dengan ekstensi gambar

        if (!$finder->hasResults()) {
            $this->warn('Tidak ada file gambar yang ditemukan di dalam folder public/images. Tidak ada yang perlu dilakukan.');
            return 0;
        }
        
        $imageFiles = iterator_to_array($finder);
        $this->info(count($imageFiles) . ' file gambar ditemukan. Memulai optimasi...');
        
        $progressBar = $this->output->createProgressBar(count($imageFiles));
        $progressBar->start();

        foreach ($imageFiles as $file) {
            $filePath = $file->getRealPath();
            try {
                // Proses gambar
                $image = $manager->read($filePath);
                
                // Ubah ukuran jika lebih lebar dari 1200px (opsional, sesuaikan)
                $image->scale(width: 1200);

                // Encode ke Jpeg dengan kualitas 80%
                $encodedImage = $image->toJpeg(80);

                // Timpa file lama dengan versi baru
                file_put_contents($filePath, $encodedImage);

            } catch (Throwable $e) {
                $this->error("\n Gagal memproses gambar: {$filePath}. Error: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info('✅ Selesai! Semua gambar statis telah berhasil dioptimalkan.');
        return 0;
    }
}