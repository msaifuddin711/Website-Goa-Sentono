<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GaleriItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GaleriPageController extends Controller
{
    public function index()
    {
        $galeriItems = GaleriItem::latest()->paginate(20);
        
        return view('admin.galeri.index', compact('galeriItems'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = $request->file('gambar')->store('galeri', 'public');

        GaleriItem::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar_path' => $gambarPath
        ]);

        return redirect()->back()->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    public function update(Request $request, GaleriItem $galeri)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store('galeri', 'public');
        }

        $galeri->update($data);

        return redirect()->back()->with('success', 'Foto galeri berhasil diperbarui!');
    }

    public function destroy(GaleriItem $galeri)
    {
        if ($galeri->gambar_path) {
            Storage::disk('public')->delete($galeri->gambar_path);
        }
        
        $galeri->delete();

        return redirect()->back()->with('success', 'Foto galeri berhasil dihapus!');
    }

    public function reorder(Request $request)
    {
        // Karena tidak ada kolom urutan, fungsi ini akan mengurutkan berdasarkan ID atau timestamp
        return response()->json(['success' => true, 'message' => 'Fitur reorder tidak tersedia tanpa kolom urutan']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu foto untuk dihapus.');
        }

        $items = GaleriItem::whereIn('id', $ids)->get();
        
        foreach ($items as $item) {
            if ($item->gambar_path) {
                Storage::disk('public')->delete($item->gambar_path);
            }
            $item->delete();
        }

        return redirect()->back()->with('success', count($ids) . ' foto berhasil dihapus!');
    }
}