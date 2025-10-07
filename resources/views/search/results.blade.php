{{-- resources/views/search/results.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $query)

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24">
        
        <!-- Search Header -->
        <div class="text-center mb-12 sm:mb-14 lg:mb-16">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-primary mb-4 sm:mb-6">Hasil Pencarian</h1>
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                Menampilkan hasil untuk: <span class="font-semibold text-primary">"{{ $query }}"</span>
            </p>
        </div>

        <!-- Articles Section -->
        @if($articles->count() > 0)
        <div class="mb-16 sm:mb-20 lg:mb-24">
            <div class="flex items-center mb-12 sm:mb-14 lg:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-primary mr-4 sm:mr-6">Artikel</h2>
                <div class="bg-primary text-white px-3 sm:px-4 py-1 sm:py-2 rounded-full text-sm sm:text-base">
                    {{ $articles->total() }} hasil
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 mb-12 sm:mb-14 lg:mb-16">
                @foreach($articles as $article)
                <article class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden card-hover border border-gray-100 flex flex-col">
                    <a href="{{ route('artikel.show', $article->slug) }}" class="block h-32 md:h-48 overflow-hidden">
                        <img src="{{ $article->gambar_url }}" 
                             alt="{{ $article->judul }}" 
                             class="w-full h-full object-cover transition duration-300 hover:scale-105">
                    </a>
                    <div class="px-3 md:px-8 py-3 md:py-6 flex flex-col flex-grow">
                        <div class="flex items-center mb-2 md:mb-4">
                            <span class="text-xs md:text-sm text-gray-500">{{ $article->published_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-sm md:text-xl font-bold text-primary mb-2 md:mb-4 flex-grow line-clamp-2 md:line-clamp-none">
                            <a href="{{ route('artikel.show', $article->slug) }}" class="hover:text-opacity-80">{{ $article->judul }}</a>
                        </h3>
                        <p class="text-xs md:text-base text-accent leading-relaxed mb-3 md:mb-6 line-clamp-2 md:line-clamp-3">{{ $article->ringkasan_potong }}</p>
                        <a href="{{ route('artikel.show', $article->slug) }}" class="text-xs md:text-base text-primary font-bold hover:text-accent transition duration-300 flex items-center mt-auto">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Articles Pagination -->
            <div class="flex justify-center">
                {{ $articles->appends(['q' => $query])->links() }}
            </div>
        </div>
        @endif

        <!-- Gallery Section -->
        @if($galleryItems->count() > 0)
        <div class="mb-16 sm:mb-20 lg:mb-24">
            <div class="flex items-center mb-12 sm:mb-14 lg:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-primary mr-4 sm:mr-6">Galeri</h2>
                <div class="bg-primary text-white px-3 sm:px-4 py-1 sm:py-2 rounded-full text-sm sm:text-base">
                    {{ $galleryItems->total() }} hasil
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-7 lg:mb-8">
                @foreach($galleryItems as $item)
                <div class="gallery-item bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300" 
                     id="gallery-item-{{ $item->id }}">
                    <div class="relative h-32 sm:h-40 lg:h-48 overflow-hidden group cursor-pointer" 
                         onclick="openImageModal({{ $item->id }})">
                        <img src="{{ $item->gambar_url }}" 
                             alt="{{ $item->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                    </div>
                    <div class="p-2 sm:p-3 lg:p-4">
                        <h4 class="font-bold text-primary mb-1 text-center truncate text-xs sm:text-sm lg:text-base">
                            {{ $item->judul }}
                        </h4>
                        @if($item->deskripsi)
                        <p class="text-xs text-gray-500 text-center line-clamp-2 hidden sm:block">
                            {{ $item->deskripsi }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Gallery Pagination -->
            <div class="flex justify-center">
                {{ $galleryItems->appends(['q' => $query])->links() }}
            </div>
        </div>
        @endif

        <!-- No Results -->
        @if($articles->count() == 0 && $galleryItems->count() == 0)
        <div class="text-center py-16 sm:py-20 lg:py-24">
            <div class="max-w-md mx-auto">
                <svg class="w-20 h-20 sm:w-24 sm:h-24 text-gray-300 mx-auto mb-6 sm:mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-600 mb-4 sm:mb-6">Tidak Ada Hasil</h3>
                <p class="text-base sm:text-lg text-gray-500 mb-8 sm:mb-12 px-4 max-w-2xl mx-auto">
                    Tidak ditemukan hasil untuk pencarian "<span class="font-semibold">{{ $query }}</span>". 
                    Coba gunakan kata kunci yang berbeda.
                </p>
            </div>
        </div>
        @endif

        <!-- Back to Home -->
        <div class="text-center mt-12 sm:mt-16 lg:mt-20">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-8 md:px-12 py-3 md:py-4 bg-primary text-white font-semibold rounded-full text-sm md:text-base hover:bg-opacity-90 transform hover:scale-105 transition duration-300 shadow-lg">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- Image Modal (responsif) -->
<div id="image-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 transition-opacity duration-300">
    <div class="w-full h-full flex items-center justify-center p-2 sm:p-4 md:p-8" id="modal-backdrop">
        
        <!-- Close Button yang Diperbaiki -->
        <button id="modal-close" class="absolute top-4 right-4 sm:top-6 sm:right-6 text-white hover:text-red-400 transition-all duration-300 z-30 group">
            <i class="fa-solid fa-circle-xmark fa-2xl"></i>
        </button>
        
        <!-- Konten Gambar -->
        <div id="modal-content" class="w-full h-full flex justify-center items-center">
            <!-- Gambar akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize gallery modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Gallery data for modal
    const galleryData = {
        @foreach($galleryItems as $item)
        {{ $item->id }}: {
            id: {{ $item->id }},
            judul: "{{ addslashes($item->judul) }}",
            deskripsi: "{{ addslashes($item->deskripsi ?? '') }}",
            gambar_url: "{{ $item->gambar_url }}"
        },
        @endforeach
    };

    const imageModal = document.getElementById('image-modal');
    const modalContent = document.getElementById('modal-content');
    const modalCloseBtn = document.getElementById('modal-close');
    const modalBackdrop = document.getElementById('modal-backdrop');

    window.openImageModal = function(itemId) {
        const item = galleryData[itemId];
        if (!item) return;

        // Check if mobile device
        const isMobile = window.innerWidth < 768;
        const modalWidth = isMobile ? 'w-full max-w-sm' : 'w-[800px]';
        const modalHeight = isMobile ? 'h-[400px]' : 'h-[600px]';

        modalContent.innerHTML = `
            <div class="relative w-[800px] sm:min-h-[300px] md:min-h-[400px] lg:min-h-[500px] shadow-2xl rounded-lg overflow-hidden">
                <img
                src="${item.gambar_url}"
                alt="${item.judul}"
                class="w-full h-full object-cover"
                >
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4">
                <h3 class="text-lg font-bold">${item.judul}</h3>
                ${item.deskripsi ? `<p class="text-sm mt-1">${item.deskripsi}</p>` : ''}
                </div>
            </div>
        `;
        
        imageModal.classList.remove('hidden');
        imageModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    function closeImageModal() {
        imageModal.classList.add('hidden');
        imageModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        modalContent.innerHTML = '';
    }

    modalCloseBtn.addEventListener('click', closeImageModal);
    modalBackdrop.addEventListener('click', closeImageModal);
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
            closeImageModal();
        }
    });
});
</script>
@endpush
@endsection