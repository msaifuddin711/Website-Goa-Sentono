<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TentangSetting;
use App\Models\SejarahSlider;
use App\Models\Item;

class TentangController extends Controller
{
    public function tentang()
    {
        // Ambil setting dan ubah menjadi koleksi yang mudah diakses
        $settings = TentangSetting::all()->pluck('value', 'key');

        // Ambil data lainnya dengan accessor URL gambar
        $sejarahSliders = SejarahSlider::orderBy('urutan')->get();
        $keunikanItems = Item::where('tipe', 'keunikan')->orderBy('urutan')->get();
        $fasilitasItems = Item::where('tipe', 'fasilitas')->orderBy('urutan')->get();
        $wisataItems = Item::where('tipe', 'wisata_sekitar')->orderBy('urutan')->get();

        return view('tentang', compact(
            'settings',
            'sejarahSliders',
            'keunikanItems',
            'fasilitasItems',
            'wisataItems'
        ));
    }
}
