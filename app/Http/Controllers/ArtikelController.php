<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        $featuredArtikel = Artikel::where('is_featured', true)
                                  ->where('is_visible', true)
                                  ->whereNotNull('published_at')
                                  ->where('published_at', '<=', now())
                                  ->orderBy('published_at', 'desc')
                                  ->first();

        $query = Artikel::where('is_visible', true)
                        ->whereNotNull('published_at')
                        ->where('published_at', '<=', now())
                        ->orderBy('published_at', 'desc');

        if ($featuredArtikel) {
            $query->where('id', '!=', $featuredArtikel->id);
        }

        $artikels = $query->paginate(6); 

        return view('artikel', compact('featuredArtikel', 'artikels'));
    }

    public function show($slug)
    {
        $artikel = Artikel::where('slug', $slug)
                          ->where('is_visible', true)
                          ->whereNotNull('published_at')
                          ->where('published_at', '<=', now())
                          ->firstOrFail();

        $relatedArtikels = Artikel::where('id', '!=', $artikel->id)
                                 ->where('is_visible', true)
                                 ->whereNotNull('published_at')
                                 ->where('published_at', '<=', now())
                                 ->orderBy('published_at', 'desc')
                                 ->limit(3)
                                 ->get();

        return view('artikel-detail', compact('artikel', 'relatedArtikels'));
    }
}