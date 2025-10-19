<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TentangSetting;
use App\Models\SejarahSlider;
use App\Models\Item; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TentangPageController extends Controller
{
    public function index()
    {
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

        $validator->after(function ($validator) use ($request) {
            $videoUrl = $request->input('video_url');
            if ($videoUrl && !$this->isValidYouTubeUrl($videoUrl)) {
                $validator->errors()->add('video_url', 'URL YouTube tidak valid. Gunakan format yang didukung.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

    private function convertToEmbedUrl($url)
    {
        if (empty($url)) {
            return '';
        }

        $patterns = [
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $videoId = $matches[1];
                return "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1";
            }
        }

        if (strpos($url, 'youtube.com/embed/') !== false &&
            strpos($url, 'rel=0') !== false &&
            strpos($url, 'modestbranding=1') !== false) {
            return $url;
        }

        return $url;
    }

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

    public function storeSejarahSlider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alt_text' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = Str::slug($request->alt_text ?: 'sejarah-slider') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'sejarah-slider/' . $fileName;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 1920);
            $encodedImage = $image->toJpeg(80); 
            Storage::disk('public')->put($filePath, $encodedImage);
            $gambarPath = $filePath;
        }

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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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
            if ($slider->gambar_path) {
                Storage::disk('public')->delete($slider->gambar_path);
            }
            
            $file = $request->file('gambar');
            $fileName = Str::slug($request->alt_text ?: 'sejarah-slider') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'sejarah-slider/' . $fileName;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 1920);
            $encodedImage = $image->toJpeg(80);
            Storage::disk('public')->put($filePath, $encodedImage);
            $data['gambar_path'] = $filePath;
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

    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipe' => 'required|in:keunikan,fasilitas,wisata_sekitar',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'info_tambahan' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $folder = $request->tipe; 
            $fileName = Str::slug($request->judul) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $folder . '/' . $fileName;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 1200); 
            $encodedImage = $image->toJpeg(80); 
            Storage::disk('public')->put($filePath, $encodedImage);
            $gambarPath = $filePath;
        }

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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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
            if ($item->gambar_path) {
                Storage::disk('public')->delete($item->gambar_path);
            }
            
            $file = $request->file('gambar');
            $folder = $item->tipe; 
            $fileName = Str::slug($request->judul) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $folder . '/' . $fileName;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scale(width: 1200);
            $encodedImage = $image->toJpeg(80);
            Storage::disk('public')->put($filePath, $encodedImage);
            $data['gambar_path'] = $filePath;
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