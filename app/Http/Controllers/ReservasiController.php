<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function reservasi()
    {
$paketWisata = [
        [
            'nama' => 'Tanpa Paket',
            'harga' => ['weekday' => 3000, 'weekend' => 5000],
            'fasilitas' => ['Tiket Masuk Saja'],
        ],
        [
            'nama' => 'Paket A',
            'harga' => ['weekday' => 8000, 'weekend' => 10000],
            'fasilitas' => ['Tiket Masuk', 'Pemandu Lokal'],
        ],
        [
            'nama' => 'Paket B',
            'harga' => ['weekday' => 15000, 'weekend' => 20000],
            'fasilitas' => ['Tiket Masuk', 'Pemandu Lokal', 'Snack'],
        ],
    ];

        return view('reservasi', compact('paketWisata'));
    }
}
