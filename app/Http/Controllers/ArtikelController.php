<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Menampilkan halaman daftar artikel dengan artikel utama.
     */
    public function index()
    {
        // Ambil artikel yang ditandai sebagai 'featured' - yang terbaru berdasarkan published_at
        $featuredArtikel = Artikel::where('is_featured', true)
                                  ->whereNotNull('published_at')
                                  ->where('published_at', '<=', now())
                                  ->orderBy('published_at', 'desc')
                                  ->first();

        // Ambil artikel lainnya dengan paginasi, KECUALI yang sudah jadi featured
        // Urutkan berdasarkan published_at (terbaru dulu)
        $query = Artikel::whereNotNull('published_at')
                        ->where('published_at', '<=', now())
                        ->orderBy('published_at', 'desc');

        // Jika ada featured artikel, exclude dari grid
        if ($featuredArtikel) {
            $query->where('id', '!=', $featuredArtikel->id);
        }

        $artikels = $query->paginate(6); // 6 artikel per halaman

        return view('artikel', compact('featuredArtikel', 'artikels'));
    }

    /**
     * Menampilkan halaman detail satu artikel.
     */
    public function show($slug)
    {
        $artikel = Artikel::where('slug', $slug)
                          ->whereNotNull('published_at')
                          ->where('published_at', '<=', now())
                          ->firstOrFail(); // Gagal jika tidak ditemukan

        // Artikel terkait (3 artikel terbaru, exclude artikel saat ini)
        $relatedArtikels = Artikel::where('id', '!=', $artikel->id)
                                 ->whereNotNull('published_at')
                                 ->where('published_at', '<=', now())
                                 ->orderBy('published_at', 'desc')
                                 ->limit(3)
                                 ->get();

        return view('artikel-detail', compact('artikel', 'relatedArtikels'));
    }
}