<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\GaleriItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $galeriItems = GaleriItem::visible()->latest()->limit(6)->get();

        $artikels = Artikel::whereNotNull('published_at')
            ->where('is_visible', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(6)
            ->get();

        return view('home', compact('galeriItems', 'artikels'));
    }
}
