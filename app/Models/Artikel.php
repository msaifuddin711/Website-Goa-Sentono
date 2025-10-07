<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';

    protected $fillable = [
        'judul',
        'slug',
        'isi_konten',
        'gambar_path',
        'is_featured',
        'published_at',
    ];

    // Mengubah tipe data kolom agar mudah digunakan
    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Accessor untuk mendapatkan URL lengkap dari gambar
    public function getGambarUrlAttribute(): string
    {
        $path = $this->gambar_path;
        
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Gunakan asset() untuk membuat URL
        if ($this->gambar_path && Storage::disk('public')->exists($this->gambar_path)) {
            return asset('storage/' . $this->gambar_path);
        }
        
        // Mengembalikan gambar placeholder jika file tidak ditemukan
        return asset('images/placeholder.jpg'); 
    }

    public function getRingkasanPotongAttribute(): string
    {
        return Str::limit(strip_tags($this->isi_konten), 110);
    }
}
