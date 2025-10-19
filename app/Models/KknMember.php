<?php

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

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(): string
    {
        $path = $this->photo_path;
        
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return asset('storage/' . $this->photo_path);
        }
        
        return asset('images/placeholder.jpg'); 
    }

    public function getGambarUrlAttribute(): string
    {
        return $this->getPhotoUrlAttribute();
    }

    public function scopeDpl($query)
    {
        return $query->where('is_dpl', true);
    }

    public function scopeMembers($query)
    {
        return $query->where('is_dpl', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('is_dpl', 'desc')->orderBy('order', 'asc');
    }
}