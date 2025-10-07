<?php

// app/Models/KknMember.php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KknMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'photo_path',
        'is_dpl',
        'order',
    ];

    // Tambahkan photo_url ke appends agar bisa diakses
    protected $appends = ['photo_url'];

    /**
     * Accessor untuk mendapatkan URL gambar yang lengkap.
     * Menggunakan photo_url (bukan gambar_url) untuk konsistensi dengan view
     */
    public function getPhotoUrlAttribute(): string
    {
        $path = $this->photo_path;
        
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Gunakan asset() untuk membuat URL
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return asset('storage/' . $this->photo_path);
        }
        
        // Mengembalikan gambar placeholder jika file tidak ditemukan
        return asset('images/placeholder.jpg'); 
    }

    /**
     * Accessor alternatif untuk backward compatibility
     */
    public function getGambarUrlAttribute(): string
    {
        return $this->getPhotoUrlAttribute();
    }

    /**
     * Scope untuk DPL
     */
    public function scopeDpl($query)
    {
        return $query->where('is_dpl', true);
    }

    /**
     * Scope untuk anggota (bukan DPL)
     */
    public function scopeMembers($query)
    {
        return $query->where('is_dpl', false);
    }

    /**
     * Scope untuk urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('is_dpl', 'desc')->orderBy('order', 'asc');
    }
}