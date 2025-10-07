<?php
namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    protected $fillable = ['tipe', 'judul', 'deskripsi', 'gambar_path', 'info_tambahan', 'urutan'];

    // Accessor untuk mendapatkan URL lengkap gambar
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
}
