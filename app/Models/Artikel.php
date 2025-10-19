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
        'is_visible',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getGambarUrlAttribute(): string
    {
        $path = $this->gambar_path;
        
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if ($this->gambar_path && Storage::disk('public')->exists($this->gambar_path)) {
            return asset('storage/' . $this->gambar_path);
        }
        
        return asset('images/placeholder.jpg'); 
    }

    public function getRingkasanPotongAttribute(): string
    {
        return Str::limit(strip_tags($this->isi_konten), 110);
    }
}
