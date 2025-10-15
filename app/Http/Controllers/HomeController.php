<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\GaleriItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 3 item galeri terbaru
        $galeriItems = GaleriItem::visible()->latest()->limit(6)->get();

        // Ambil 3 artikel terbaru yang sudah dipublikasikan
        $artikels = Artikel::whereNotNull('published_at')
            ->where('is_visible', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Kirim data ke view 'home'
        return view('home', compact('galeriItems', 'artikels'));
    }
}
