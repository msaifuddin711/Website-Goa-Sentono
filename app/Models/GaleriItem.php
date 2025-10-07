<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GaleriItem extends Model
{
    use HasFactory;

    protected $table = 'galeri_items';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar_path',
    ];

    protected $appends = ['gambar_url'];
    
    /**
     * Accessor untuk mendapatkan URL lengkap dari gambar.
     * Ini memungkinkan kita memanggil $item->gambar_url di view.
     */
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

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}