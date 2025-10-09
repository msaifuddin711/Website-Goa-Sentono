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
        // Ambil semua item untuk admin
        $query = GaleriItem::latest();
        $galeriItems = $query->paginate(20);

        // Tambahkan statistik visibilitas
        $totalVisible = GaleriItem::where('is_visible', true)->count();
        $totalHidden = GaleriItem::where('is_visible', false)->count();

        return view('admin.galeri.index', compact('galeriItems', 'totalVisible', 'totalHidden'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp',
            'is_visible' => 'nullable|boolean' // <-- Tambah validasi
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $gambarPath = $request->file('gambar')->store('galeri', 'public');

        GaleriItem::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar_path' => $gambarPath,
            'is_visible' => $request->has('is_visible') ? $request->is_visible : false // <-- Handle is_visible
        ]);

        return response()->json(['success' => 'Foto galeri berhasil ditambahkan!']);
    }

    public function update(Request $request, GaleriItem $galeri)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_visible' => 'nullable|boolean' // <-- Tambah validasi
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'is_visible' => $request->has('is_visible') ? $request->is_visible : false // <-- Handle is_visible
        ];

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store('galeri', 'public');
        }

        $galeri->update($data);

        return response()->json(['success' => 'Foto galeri berhasil diperbarui!']);
    }

    // METHOD BARU UNTUK TOGGLE VISIBILITY
    public function toggleVisibility(GaleriItem $galeri)
    {
        $galeri->is_visible = !$galeri->is_visible;
        $galeri->save();

        $message = $galeri->is_visible ? 'Foto sekarang ditampilkan.' : 'Foto sekarang disembunyikan.';
        
        return response()->json(['success' => true, 'message' => $message, 'is_visible' => $galeri->is_visible]);
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