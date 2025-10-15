<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArtikelPageController extends Controller
{
    public function index()
    {
        // Urutkan berdasarkan published_at, kemudian created_at
        $artikels = Artikel::orderByRaw('published_at DESC, created_at DESC')->paginate(20);
        
        return view('admin.artikel.index', compact('artikels'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = $request->file('gambar')->store('artikel', 'public');

        // Generate slug from title
        $slug = Str::slug($request->judul);
        $originalSlug = $slug;
        $counter = 1;
        
        // Ensure unique slug
        while (Artikel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // If is_featured is true, remove featured status from other articles
        if ($request->is_featured) {
            Artikel::where('is_featured', true)->update(['is_featured' => false]);
        }

        Artikel::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'isi_konten' => $request->isi_konten,
            'gambar_path' => $gambarPath,
            'is_featured' => $request->is_featured ?? false,
            'is_visible' => $request->is_visible ?? true,
            'published_at' => $request->published_at ?? now()
        ]);

        return redirect()->back()->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function update(Request $request, Artikel $artikel)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'judul' => $request->judul,
            'isi_konten' => $request->isi_konten,
            'is_featured' => $request->is_featured ?? false,
            'is_visible' => $request->is_visible ?? $artikel->is_visible,
            'published_at' => $request->published_at ?? $artikel->published_at
        ];

        // Update slug if title changed
        if ($request->judul !== $artikel->judul) {
            $slug = Str::slug($request->judul);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Artikel::where('slug', $slug)->where('id', '!=', $artikel->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        }

        // If is_featured is true, remove featured status from other articles
        if ($request->is_featured) {
            Artikel::where('is_featured', true)->where('id', '!=', $artikel->id)->update(['is_featured' => false]);
        }

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($artikel->gambar_path) {
                Storage::disk('public')->delete($artikel->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store('artikel', 'public');
        }

        $artikel->update($data);

        return redirect()->back()->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->gambar_path) {
            Storage::disk('public')->delete($artikel->gambar_path);
        }
        
        $artikel->delete();

        return redirect()->back()->with('success', 'Artikel berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu artikel untuk dihapus.');
        }

        $items = Artikel::whereIn('id', $ids)->get();
        
        foreach ($items as $item) {
            if ($item->gambar_path) {
                Storage::disk('public')->delete($item->gambar_path);
            }
            $item->delete();
        }

        return redirect()->back()->with('success', count($ids) . ' artikel berhasil dihapus!');
    }

    public function toggleFeatured(Artikel $artikel)
    {
        if (!$artikel->is_featured) {
            // Remove featured status from other articles
            Artikel::where('is_featured', true)->update(['is_featured' => false]);
            $artikel->update(['is_featured' => true]);
            $message = 'Artikel berhasil dijadikan featured!';
        } else {
            $artikel->update(['is_featured' => false]);
            $message = 'Status featured artikel berhasil dihapus!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * PERUBAHAN DI SINI
     * Mengubah fungsi ini agar mengembalikan JSON, bukan redirect.
     */
    public function toggleVisibility(Artikel $artikel)
    {
        // Ubah status visibilitas
        $artikel->is_visible = !$artikel->is_visible;
        $artikel->save();
        
        // Siapkan pesan notifikasi
        $message = $artikel->is_visible 
            ? 'Artikel sekarang ditampilkan.' 
            : 'Artikel sekarang disembunyikan.';

        // Kembalikan respons dalam format JSON
        return response()->json([
            'success' => true, 
            'message' => $message, 
            'is_visible' => $artikel->is_visible
        ]);
    }
}