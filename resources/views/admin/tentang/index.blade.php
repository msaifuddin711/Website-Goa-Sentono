{{-- resources/views/admin/tentang/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Halaman Tentang')
@section('page-title', 'Kelola Halaman Tentang')
@section('page-subtitle', 'Kelola konten, gambar, dan informasi halaman tentang Goa Sentono')

@section('content')
<div class="space-y-8">
    <!-- Settings Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-light-beige overflow-hidden card-hover">
        <div class="bg-primary-green px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cream bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cogs text-cream"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-cream">Pengaturan Umum</h2>
                        <p class="text-light-beige text-sm">Konfigurasi dasar halaman tentang</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.tentang.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="sejarah_deskripsi" class="block text-sm font-semibold text-primary-dark mb-2">
                            <i class="fas fa-history text-accent-green mr-2"></i>Deskripsi Sejarah
                        </label>
                        <textarea class="w-full px-4 py-3 border border-light-beige rounded-xl focus:ring-2 focus:ring-accent-green focus:border-transparent bg-soft-cream transition-all duration-200 resize-none" 
                                  id="sejarah_deskripsi" name="sejarah_deskripsi" rows="6" required 
                                  placeholder="Masukkan deskripsi sejarah Goa Sentono...">{{ $settings['sejarah_deskripsi'] ?? '' }}</textarea>
                        <p class="text-xs text-accent-green mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Gunakan Enter untuk membuat baris baru
                        </p>
                    </div>
                    
<div>
    <label for="video_url" class="block text-sm font-semibold text-primary-dark mb-2">
        <i class="fab fa-youtube text-medium-brown mr-2"></i>URL Video YouTube
    </label>
    <div class="relative">
        <input type="url" 
               class="w-full px-4 py-3 pr-12 border border-light-beige rounded-xl focus:ring-2 focus:ring-accent-green focus:border-transparent bg-soft-cream transition-all duration-200" 
               id="video_url" name="video_url" value="{{ $settings['video_url'] ?? '' }}" required
               placeholder="Masukkan URL YouTube (akan otomatis dikonversi ke format embed)">
        
        <!-- Tombol Convert Manual (opsional) -->
        <button type="button" 
                onclick="manualConvertUrl()" 
                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-accent-green hover:bg-primary-green text-white p-2 rounded-lg transition-colors duration-200"
                title="Konversi manual ke format embed">
            <i class="fas fa-sync-alt text-sm"></i>
        </button>
    </div>
    
    <div class="mt-2 space-y-1">
        <p class="text-xs text-accent-green flex items-center">
            <i class="fas fa-info-circle mr-1"></i>
            Format yang didukung:
        </p>
        <ul class="text-xs text-gray-600 ml-4 space-y-1">
            <li>• https://www.youtube.com/watch?v=VIDEO_ID</li>
            <li>• https://youtu.be/VIDEO_ID</li>
            <li>• https://m.youtube.com/watch?v=VIDEO_ID</li>
        </ul>
        <p class="text-xs text-green-600 flex items-center">
            <i class="fas fa-magic mr-1"></i>
            URL akan otomatis dikonversi ke format embed saat Anda mengetik atau menyimpan
        </p>
    </div>
    
    <!-- Preview iframe (opsional) -->
    <div id="video-preview" class="mt-4 hidden">
        <p class="text-sm font-medium text-primary-dark mb-2">Preview Video:</p>
        <div class="relative pb-[56.25%] rounded-lg overflow-hidden bg-gray-100">
            <iframe id="preview-iframe" 
                    class="absolute inset-0 w-full h-full" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
                </div>
                
                <div class="flex justify-end pt-4 border-t border-light-beige">
                    <button type="submit" class="bg-primary-green text-cream px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sejarah Slider Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-light-beige overflow-hidden card-hover">
        <div class="bg-accent-green px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cream bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-images text-cream"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-cream">Slider Gambar Sejarah</h2>
                        <p class="text-light-beige text-sm">Kelola gambar-gambar pada slider sejarah</p>
                    </div>
                </div>
                <button class="bg-cream bg-opacity-20 hover:bg-opacity-30 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                        onclick="showAddSejarahPopup()">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">Tambah Gambar</span>
                </button>
            </div>
        </div>
        <div class="p-6">
            @if($sejarahSliders->count() > 0)
                <div id="sejarah-sortable" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sejarahSliders as $slider)
                        <div class="bg-soft-cream rounded-xl overflow-hidden card-hover cursor-move border border-light-beige" data-id="{{ $slider->id }}">
                            <div class="relative">
                                <img src="{{ $slider->gambar_url }}" 
                                     alt="{{ $slider->alt_text ?? 'Gambar Sejarah' }}" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute top-2 right-2 bg-cream rounded-lg px-2 py-1 text-xs font-semibold text-primary-dark">
                                    #{{ $slider->urutan }}
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-accent-green mb-3 line-clamp-2">
                                    {{ $slider->alt_text ?: 'Tidak ada deskripsi' }}
                                </p>
                                <div class="flex space-x-2">
                                    <button class="flex-1 bg-warm-beige hover:bg-light-beige text-primary-dark px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200 edit-sejarah-btn"
                                            data-id="{{ $slider->id }}"
                                            data-alt="{{ $slider->alt_text }}"
                                            data-urutan="{{ $slider->urutan }}">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <button type="button" 
                                            class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="deleteSejarahSlider({{ $slider->id }})">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-light-beige rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-images text-4xl text-accent-green"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-2">Belum Ada Gambar</h3>
                    <p class="text-accent-green mb-4">Tambahkan gambar pertama untuk slider sejarah</p>
                    <button class="bg-primary-green text-cream px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark hover:shadow-lg transform hover:scale-105 transition-all duration-200" 
                            onclick="showAddSejarahPopup()">
                        <i class="fas fa-plus mr-2"></i>Tambah Gambar
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Dynamic Sections -->
    @foreach([
        ['title' => 'Fasilitas', 'items' => $fasilitasItems, 'type' => 'fasilitas', 'bg_color' => 'bg-medium-brown', 'icon' => 'fas fa-tools'],
        ['title' => 'Wisata Sekitar', 'items' => $wisataItems, 'type' => 'wisata_sekitar', 'bg_color' => 'bg-sage-green', 'icon' => 'fas fa-map-marked-alt']
    ] as $section)
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige overflow-hidden card-hover">
            <div class="{{ $section['bg_color'] }} px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-cream bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="{{ $section['icon'] }} text-cream"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-cream">{{ $section['title'] }}</h2>
                            <p class="text-light-beige text-sm">Kelola data {{ strtolower($section['title']) }}</p>
                        </div>
                    </div>
                    <button class="bg-cream bg-opacity-20 hover:bg-opacity-30 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                            onclick="openItemModal('{{ $section['type'] }}')">
                        <i class="fas fa-plus"></i>
                        <span class="hidden sm:inline">Tambah {{ $section['title'] }}</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                @if($section['items']->count() > 0)
                    <div class="{{ $section['type'] }}-items grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($section['items'] as $item)
                            <div class="bg-soft-cream rounded-xl overflow-hidden card-hover border border-light-beige">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h3 class="font-bold text-primary-dark mb-2 line-clamp-1">{{ $item->judul }}</h3>
                                    <p class="text-sm text-accent-green mb-3 line-clamp-2">{{ $item->deskripsi }}</p>
                                    @if($item->info_tambahan)
                                        <p class="text-xs text-primary-green mb-2 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $item->info_tambahan }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between text-xs text-accent-green mb-3">
                                        <span>Urutan: {{ $item->urutan }}</span>
                                        <span>{{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="flex-1 bg-warm-beige hover:bg-light-beige text-primary-dark px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                                onclick="editItem('{{ $section['type'] }}', {{ $item->toJson() }})">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                                onclick="deleteItem({{ $item->id }})">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-light-beige rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="{{ $section['icon'] }} text-4xl text-accent-green"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-primary-dark mb-2">Belum Ada {{ $section['title'] }}</h3>
                        <p class="text-accent-green mb-4">Tambahkan {{ strtolower($section['title']) }} pertama</p>
                        <button class="bg-primary-green text-cream px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark hover:shadow-lg transform hover:scale-105 transition-all duration-200" 
                                onclick="openItemModal('{{ $section['type'] }}')">
                            <i class="fas fa-plus mr-2"></i>Tambah {{ $section['title'] }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Pop-up Overlay (sama seperti di galeri) -->
<div id="popup-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="popup-content" class="transform scale-95 transition-transform duration-300"></div>
    </div>
</div>

<script>
// Define routes for JavaScript to use
window.adminRoutes = {
    sejarahReorder: "{{ route('admin.tentang.sejarah.reorder') }}",
    sejarahStore: "{{ route('admin.tentang.sejarah.store') }}",
    sejarahUpdate: "{{ route('admin.tentang.sejarah.update', ['slider' => ':id']) }}",
    sejarahDelete: "{{ route('admin.tentang.sejarah.delete', ['slider' => ':id']) }}",
    itemStore: "{{ route('admin.tentang.item.store') }}",
    itemUpdate: "{{ route('admin.tentang.item.update', ['item' => ':id']) }}",
    itemDelete: "{{ route('admin.tentang.item.delete', ['item' => ':id']) }}"
};
</script>
<script>
// Tambahkan ke script yang sudah ada
document.addEventListener('DOMContentLoaded', function() {
    const videoUrlInput = document.getElementById('video_url');
    const videoPreview = document.getElementById('video-preview');
    const previewIframe = document.getElementById('preview-iframe');
    
    if (videoUrlInput) {
        // Fungsi untuk update preview
        function updatePreview(url) {
            if (url && isValidYouTubeUrl(url)) {
                const embedUrl = convertToEmbedUrl(url);
                previewIframe.src = embedUrl;
                videoPreview.classList.remove('hidden');
            } else {
                videoPreview.classList.add('hidden');
                previewIframe.src = '';
            }
        }
        
        // Auto convert dan preview saat user mengetik
        let timeout;
        videoUrlInput.addEventListener('input', function(e) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const originalUrl = e.target.value.trim();
                if (originalUrl && isValidYouTubeUrl(originalUrl)) {
                    const embedUrl = convertToEmbedUrl(originalUrl);
                    if (embedUrl !== originalUrl) {
                        e.target.value = embedUrl;
                        showUrlConvertedNotification();
                    }
                    updatePreview(embedUrl);
                } else if (originalUrl) {
                    videoPreview.classList.add('hidden');
                }
            }, 1000);
        });
        
        // Load preview saat halaman pertama kali dimuat
        if (videoUrlInput.value) {
            updatePreview(videoUrlInput.value);
        }
        
        // Convert saat form di-submit
        const form = videoUrlInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const originalUrl = videoUrlInput.value.trim();
                if (originalUrl && isValidYouTubeUrl(originalUrl)) {
                    videoUrlInput.value = convertToEmbedUrl(originalUrl);
                }
            });
        }
    }
});

// Fungsi untuk mengkonversi URL YouTube ke format embed
function convertToEmbedUrl(url) {
    if (!url) return '';
    
    const patterns = [
        /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
        /(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/,
        /(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
        /(?:https?:\/\/)?m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/
    ];
    
    for (let pattern of patterns) {
        const match = url.match(pattern);
        if (match && match[1]) {
            const videoId = match[1];
            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1`;
        }
    }
    
    if (url.includes('youtube.com/embed/') && url.includes('rel=0') && url.includes('modestbranding=1')) {
        return url;
    }
    
    return url;
}

function isValidYouTubeUrl(url) {
    const patterns = [
        /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
        /(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/,
        /(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
        /(?:https?:\/\/)?m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/
    ];
    
    return patterns.some(pattern => pattern.test(url));
}

function manualConvertUrl() {
    const videoUrlInput = document.getElementById('video_url');
    if (videoUrlInput) {
        const originalUrl = videoUrlInput.value.trim();
        if (originalUrl && isValidYouTubeUrl(originalUrl)) {
            const embedUrl = convertToEmbedUrl(originalUrl);
            videoUrlInput.value = embedUrl;
            showUrlConvertedNotification();
            // Update preview
            const previewIframe = document.getElementById('preview-iframe');
            const videoPreview = document.getElementById('video-preview');
            if (previewIframe && videoPreview) {
                previewIframe.src = embedUrl;
                videoPreview.classList.remove('hidden');
            }
        } else if (originalUrl) {
            alert('URL YouTube tidak valid. Pastikan format URL benar.');
        }
    }
}

function showUrlConvertedNotification() {
    let notification = document.getElementById('url-converted-notification');
    
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'url-converted-notification';
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>URL YouTube berhasil dikonversi!</span>
            </div>
        `;
        document.body.appendChild(notification);
    }
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('script/admin-script.js') }}"></script>
@endpush