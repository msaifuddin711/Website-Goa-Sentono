<?php

use App\Models\Artikel;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\Admin\GaleriPageController;
use App\Http\Controllers\Admin\KontakPageController;
use App\Http\Controllers\Admin\ArtikelPageController;
use App\Http\Controllers\Admin\TentangPageController;

require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [TentangController::class, 'tentang'])->name('tentang');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel');
Route::get('/artikel/{slug}', [ArtikelController::class, 'show'])->name('artikel.show');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/api/galeri-items', [GaleriController::class, 'apiIndex'])->name('api.galeri.index');

Route::get('/sitemap.xml', function () {
    $urls = [];

    // Halaman statis
    $urls[] = [
        'loc' => URL::to('/'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'weekly',
        'priority' => '1.0'
    ];
    $urls[] = [
        'loc' => URL::to('/tentang'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'monthly',
        'priority' => '0.8'
    ];
    $urls[] = [
        'loc' => URL::to('/galeri'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'monthly',
        'priority' => '0.8'
    ];
    $urls[] = [
        'loc' => URL::to('/kontak'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'yearly',
        'priority' => '0.5'
    ];

    // Halaman artikel dinamis
    $artikels = Artikel::whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->orderBy('published_at', 'desc')
        ->get();

    foreach ($artikels as $artikel) {
        $urls[] = [
            'loc' => URL::to('/artikel/' . $artikel->slug),
            'lastmod' => optional($artikel->updated_at)->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ];
    }

    return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
});

Route::middleware('auth')->group(function () {
    // Rute admin Tentang
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::redirect('/', '/admin/tentang');
        Route::get('/tentang', [TentangPageController::class, 'index'])->name('tentang.index');
        
        // Jika ada aksi tambahan
        Route::post('/tentang/settings', [TentangPageController::class, 'updateSettings'])->name('tentang.settings.update');
        Route::post('/tentang/sejarah-slider', [TentangPageController::class, 'storeSejarahSlider'])->name('tentang.sejarah.store');
        Route::put('/tentang/sejarah-slider/{slider}', [TentangPageController::class, 'updateSejarahSlider'])->name('tentang.sejarah.update');
        Route::delete('/tentang/sejarah-slider/{slider}', [TentangPageController::class, 'deleteSejarahSlider'])->name('tentang.sejarah.delete');

        Route::post('/tentang/item', [TentangPageController::class, 'storeItem'])->name('tentang.item.store');
        Route::put('/tentang/item/{item}', [TentangPageController::class, 'updateItem'])->name('tentang.item.update');
        Route::delete('/tentang/item/{item}', [TentangPageController::class, 'deleteItem'])->name('tentang.item.delete');

        Route::post('/tentang/reorder-sliders', [TentangPageController::class, 'reorderSejarahSlider'])->name('tentang.sejarah.reorder');
        Route::post('/tentang/reorder-items', [TentangPageController::class, 'reorderItems'])->name('tentang.items.reorder');

        Route::post('/convert-youtube-url', [TentangPageController::class, 'convertYouTubeUrl'])->name('convert.youtube.url');
        
        Route::get('/galeri', [GaleriPageController::class, 'index'])->name('galeri.index');
        Route::post('/galeri', [GaleriPageController::class, 'store'])->name('galeri.store');
        Route::put('/galeri/{galeri}', [GaleriPageController::class, 'update'])->name('galeri.update');
        Route::delete('/galeri/{galeri}', [GaleriPageController::class, 'destroy'])->name('galeri.destroy');
        Route::post('/galeri/reorder', [GaleriPageController::class, 'reorder'])->name('galeri.reorder');
        Route::post('/galeri/bulk-delete', [GaleriPageController::class, 'bulkDelete'])->name('galeri.bulk-delete');
        Route::post('/galeri/{galeri}/toggle-visibility', [GaleriPageController::class, 'toggleVisibility'])->name('galeri.toggleVisibility');

        Route::get('/artikel', [ArtikelPageController::class, 'index'])->name('artikel.index');
        Route::post('/artikel', [ArtikelPageController::class, 'store'])->name('artikel.store');
        Route::put('/artikel/{artikel}', [ArtikelPageController::class, 'update'])->name('artikel.update');
        Route::delete('/artikel/{artikel}', [ArtikelPageController::class, 'destroy'])->name('artikel.destroy');
        Route::post('/artikel/bulk-delete', [ArtikelPageController::class, 'bulkDelete'])->name('artikel.bulk-delete');
        Route::put('/artikel/{artikel}/toggle-featured', [ArtikelPageController::class, 'toggleFeatured'])->name('artikel.toggle-featured');
        Route::post('/artikel/{artikel}/toggle-visibility', [ArtikelPageController::class, 'toggleVisibility'])->name('artikel.toggleVisibility');

        Route::get('/kontak', [KontakPageController::class, 'index'])->name('kontak.index');
        
        // Kelola pesan
        Route::delete('/kontak/message/{message}', [KontakPageController::class, 'destroyMessage'])->name('kontak.message.destroy');
        Route::post('/kontak/messages/bulk-delete', [KontakPageController::class, 'bulkDeleteMessages'])->name('kontak.messages.bulk-delete');
        Route::patch('/kontak/message/{message}/mark-read', [KontakPageController::class, 'markAsRead'])->name('kontak.message.mark-read');
        Route::patch('/kontak/message/{message}/mark-unread', [KontakPageController::class, 'markAsUnread'])->name('kontak.message.mark-unread');
        
        // Kelola anggota KKN
        Route::post('/kontak/member', [KontakPageController::class, 'storeMember'])->name('kontak.member.store');
        Route::put('/kontak/member/{member}', [KontakPageController::class, 'updateMember'])->name('kontak.member.update');
        Route::delete('/kontak/member/{member}', [KontakPageController::class, 'destroyMember'])->name('kontak.member.destroy');
        Route::post('/kontak/members/reorder', [KontakPageController::class, 'reorderMembers'])->name('kontak.members.reorder');
    });
});