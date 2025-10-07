<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GaleriItem;

class GaleriApiController extends Controller
{
    /**
     * Mengambil item galeri dengan paginasi untuk fitur "Load More".
     */
    public function index(Request $request)
    {
        // Validasi input 'page' untuk keamanan
        $request->validate([
            'page' => 'sometimes|integer|min:1',
        ]);

        // Ambil item galeri, 8 item per halaman (sesuai jumlah awal)
        $galeriItems = GaleriItem::latest()->paginate(6);

        // Mengubah data agar sesuai dengan format yang mudah digunakan di frontend
        // Kita menambahkan 'gambar_url' ke setiap item
        $galeriItems->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'gambar_url' => $item->gambar_url, // Menggunakan accessor dari model
            ];
        });

        return response()->json($galeriItems);
    }
}
