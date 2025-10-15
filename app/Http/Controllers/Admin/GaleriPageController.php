<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GaleriItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GaleriPageController extends Controller
{
    public function index()
    {
        $query = GaleriItem::latest();
        $galeriItems = $query->paginate(20);
        $totalVisible = GaleriItem::where('is_visible', true)->count();
        $totalHidden = GaleriItem::where('is_visible', false)->count();

        return view('admin.galeri.index', compact('galeriItems', 'totalVisible', 'totalHidden'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_visible' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = Str::slug($request->judul) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'galeri/' . $fileName;

            // --- PERUBAHAN MENGGUNAKAN IMAGE MANAGER (CARA BARU) ---
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            // Untuk galeri, kita bisa pakai resolusi lebih besar
            $image->scale(width: 1920); 
            $encodedImage = $image->toJpeg(80); // Kualitas sedikit lebih tinggi
            Storage::disk('public')->put($filePath, $encodedImage);
            
            $gambarPath = $filePath;
            // --- AKHIR PERUBAHAN ---
        }

        GaleriItem::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar_path' => $gambarPath,
            'is_visible' => $request->has('is_visible') ? 1 : 0
        ]);
        
        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    public function update(Request $request, GaleriItem $galeri)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_visible' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'is_visible' => $request->has('is_visible') ? 1 : 0
        ];

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            
            $file = $request->file('gambar');
            $fileName = Str::slug($request->judul) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'galeri/' . $fileName;

            // --- PERUBAHAN MENGGUNAKAN IMAGE MANAGER (CARA BARU) ---
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 1920);
            $encodedImage = $image->toJpeg(80);
            Storage::disk('public')->put($filePath, $encodedImage);

            $data['gambar_path'] = $filePath;
            // --- AKHIR PERUBAHAN ---
        }

        $galeri->update($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil diperbarui!');
    }

    // METHOD BARU UNTUK TOGGLE VISIBILITY
    public function toggleVisibility(GaleriItem $galeri)
    {
        $galeri->is_visible = !$galeri->is_visible;
        $galeri->save();

        $message = $galeri->is_visible ? 'Foto sekarang ditampilkan.' : 'Foto sekarang disembunyikan.';
        
        // --- PERUBAHAN DI SINI: HITUNG ULANG TOTAL ---
        $totalVisible = GaleriItem::where('is_visible', true)->count();
        $totalHidden = GaleriItem::where('is_visible', false)->count();

        return response()->json([
            'success' => true, 
            'message' => $message, 
            'is_visible' => $galeri->is_visible,
            'totalVisible' => $totalVisible, // Kirim total baru
            'totalHidden' => $totalHidden   // Kirim total baru
        ]);
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