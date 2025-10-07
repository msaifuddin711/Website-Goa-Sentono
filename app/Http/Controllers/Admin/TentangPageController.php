<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TentangSetting;
use App\Models\SejarahSlider;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TentangPageController extends Controller
{
    public function index()
    {
        // Ambil semua data untuk dashboard admin
        $settings = TentangSetting::all()->pluck('value', 'key');
        $sejarahSliders = SejarahSlider::orderBy('urutan')->get();
        $keunikanItems = Item::where('tipe', 'keunikan')->orderBy('urutan')->get();
        $fasilitasItems = Item::where('tipe', 'fasilitas')->orderBy('urutan')->get();
        $wisataItems = Item::where('tipe', 'wisata_sekitar')->orderBy('urutan')->get();

        return view('admin.tentang.index', compact(
            'settings',
            'sejarahSliders', 
            'keunikanItems',
            'fasilitasItems',
            'wisataItems'
        ));
    }

    // === SETTINGS MANAGEMENT ===
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sejarah_deskripsi' => 'required|string',
            'video_url' => 'required|string',
            'peta_model_src' => 'nullable|string'
        ], [
            'sejarah_deskripsi.required' => 'Deskripsi sejarah harus diisi.',
            'video_url.required' => 'URL video YouTube harus diisi.',
        ]);

        // Custom validation untuk YouTube URL
        $validator->after(function ($validator) use ($request) {
            $videoUrl = $request->input('video_url');
            if ($videoUrl && !$this->isValidYouTubeUrl($videoUrl)) {
                $validator->errors()->add('video_url', 'URL YouTube tidak valid. Gunakan format yang didukung.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Konversi URL YouTube ke format embed
        $videoUrl = $this->convertToEmbedUrl($request->input('video_url'));

        $settingsData = [
            'sejarah_deskripsi' => $request->input('sejarah_deskripsi'),
            'video_url' => $videoUrl,
        ];

        if ($request->has('peta_model_src')) {
            $settingsData['peta_model_src'] = $request->input('peta_model_src');
        }

        foreach ($settingsData as $key => $value) {
            TentangSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'label' => ucfirst(str_replace('_', ' ', $key))]
            );
        }

        return redirect()->back()->with('success', 'Settings berhasil diperbarui! URL YouTube telah dikonversi ke format embed.');
    }

    /**
     * Konversi URL YouTube ke format embed
     */
    private function convertToEmbedUrl($url)
    {
        if (empty($url)) {
            return '';
        }

        // Pola regex untuk berbagai format URL YouTube
        $patterns = [
            // https://www.youtube.com/watch?v=VIDEO_ID
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            // https://youtu.be/VIDEO_ID
            '/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/',
            // https://www.youtube.com/embed/VIDEO_ID (sudah embed format)
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            // https://m.youtube.com/watch?v=VIDEO_ID
            '/(?:https?:\/\/)?m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $videoId = $matches[1];
                return "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1";
            }
        }

        // Jika sudah dalam format embed yang benar, kembalikan apa adanya
        if (strpos($url, 'youtube.com/embed/') !== false && 
            strpos($url, 'rel=0') !== false && 
            strpos($url, 'modestbranding=1') !== false) {
            return $url;
        }

        // Jika tidak cocok dengan pola manapun, kembalikan URL asli
        return $url;
    }

    /**
     * Validasi apakah URL adalah URL YouTube yang valid
     */
    private function isValidYouTubeUrl($url)
    {
        $patterns = [
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ekstrak Video ID dari URL YouTube
     */
    private function extractYouTubeVideoId($url)
    {
        $patterns = [
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    // === SEJARAH SLIDER MANAGEMENT ===
    public function storeSejarahSlider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif',
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = $request->file('gambar')->store('sejarah-slider', 'public');

        SejarahSlider::create([
            'gambar_path' => $gambarPath,
            'alt_text' => $request->alt_text,
            'urutan' => $request->urutan
        ]);

        return redirect()->back()->with('success', 'Gambar slider sejarah berhasil ditambahkan!');
    }

    public function updateSejarahSlider(Request $request, SejarahSlider $slider)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'alt_text' => $request->alt_text,
            'urutan' => $request->urutan
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($slider->gambar_path) {
                Storage::disk('public')->delete($slider->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store('sejarah-slider', 'public');
        }

        $slider->update($data);

        return redirect()->back()->with('success', 'Slider sejarah berhasil diperbarui!');
    }

    public function deleteSejarahSlider(SejarahSlider $slider)
    {
        if ($slider->gambar_path) {
            Storage::disk('public')->delete($slider->gambar_path);
        }
        
        $slider->delete();

        return redirect()->back()->with('success', 'Slider sejarah berhasil dihapus!');
    }

    // === ITEM MANAGEMENT (Keunikan, Fasilitas, Wisata) ===
    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipe' => 'required|in:keunikan,fasilitas,wisata_sekitar',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'info_tambahan' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = $request->file('gambar')->store($request->tipe, 'public');

        Item::create([
            'tipe' => $request->tipe,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar_path' => $gambarPath,
            'info_tambahan' => $request->info_tambahan,
            'urutan' => $request->urutan
        ]);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan!');
    }

    public function updateItem(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'info_tambahan' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'info_tambahan' => $request->info_tambahan,
            'urutan' => $request->urutan
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($item->gambar_path) {
                Storage::disk('public')->delete($item->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store($item->tipe, 'public');
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Item berhasil diperbarui!');
    }

    public function deleteItem(Item $item)
    {
        if ($item->gambar_path) {
            Storage::disk('public')->delete($item->gambar_path);
        }
        
        $item->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus!');
    }

    // === AJAX ENDPOINTS FOR REORDERING ===
    public function reorderSejarahSlider(Request $request)
    {
        $items = $request->input('items');
        
        foreach ($items as $index => $id) {
            SejarahSlider::where('id', $id)->update(['urutan' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function reorderItems(Request $request)
    {
        $items = $request->input('items');
        
        foreach ($items as $index => $id) {
            Item::where('id', $id)->update(['urutan' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    // === AJAX ENDPOINT FOR URL CONVERSION ===
    public function convertYouTubeUrl(Request $request)
    {
        $url = $request->input('url');
        
        if (!$this->isValidYouTubeUrl($url)) {
            return response()->json([
                'success' => false,
                'message' => 'URL YouTube tidak valid'
            ], 400);
        }

        $embedUrl = $this->convertToEmbedUrl($url);
        $videoId = $this->extractYouTubeVideoId($url);

        return response()->json([
            'success' => true,
            'embed_url' => $embedUrl,
            'video_id' => $videoId,
            'thumbnail_url' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg"
        ]);
    }
}