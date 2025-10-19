<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GaleriItem;

class GaleriApiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'sometimes|integer|min:1',
        ]);

        $galeriItems = GaleriItem::latest()->paginate(6);

        $galeriItems->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'gambar_url' => $item->gambar_url,
            ];
        });

        return response()->json($galeriItems);
    }
}
