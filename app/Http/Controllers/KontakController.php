<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\KknMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KontakController extends Controller
{
    public function index( )
    {
        $dpl = KknMember::where('is_dpl', true)->first();

        $members = KknMember::where('is_dpl', false)
                            ->orderBy('order', 'asc')
                            ->get();

        return view('kontak', [
            'dpl' => $dpl,
            'members' => $members,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'pesan' => 'required|string|min:10',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal harus terdiri dari 10 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('kontak')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal mengirim pesan. Silakan periksa kembali isian Anda.')
                ->withFragment('form-kontak');
        }

        // Validasi kata terlarang
        $pesan = strtolower($request->pesan);
        $prohibitedWords = config('prohibited');

        foreach ($prohibitedWords as $kata) {
            // Menggunakan word boundary untuk mendeteksi kata utuh
            if (preg_match('/\b' . preg_quote($kata, '/') . '\b/i', $pesan)) {
                return redirect()->route('kontak')
                    ->withInput()
                    ->with('error', 'Pesan mengandung kata yang tidak pantas. Harap periksa kembali.')
                    ->withFragment('form-kontak');
            }
        }

        Message::create([
            'name' => $request->nama,
            'email' => $request->email,
            'phone' => $request->telepon,
            'body' => $request->pesan,
        ]);

        return redirect()->route('kontak')
                    ->with('success', 'Pesan Anda telah berhasil terkirim. Terima kasih!')
                    ->withFragment('form-kontak');
    }

}
