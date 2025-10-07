<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\GaleriItem;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Kata kunci pencarian tidak boleh kosong.');
        }

        // Search articles
        $articles = Artikel::where('judul', 'like', "%{$query}%")
            ->orWhere('isi_konten', 'like', "%{$query}%")
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(6, ['*'], 'articles_page');

        // Search gallery items
        $galleryItems = GaleriItem::search($query)
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'gallery_page');

        return view('search.results', compact('query', 'articles', 'galleryItems'));
    }

    // API endpoint for AJAX search suggestions
    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $articles = Artikel::where('judul', 'like', "%{$query}%")
            ->where('published_at', '<=', now())
            ->select('judul', 'slug')
            ->limit(5)
            ->get()
            ->map(function($article) {
                return [
                    'title' => $article->judul,
                    'url' => route('artikel.show', $article->slug),
                    'type' => 'artikel'
                ];
            });

        $galleryItems = GaleriItem::where('judul', 'like', "%{$query}%")
            ->select('judul', 'id')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'title' => $item->judul,
                    'url' => route('galeri') . '#gallery-item-' . $item->id,
                    'type' => 'galeri'
                ];
            });

        $suggestions = $articles->concat($galleryItems);

        return response()->json($suggestions);
    }
}