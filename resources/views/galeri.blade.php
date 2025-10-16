{{-- resources\views\galeri.blade.php --}}

@extends('layouts.app')

@section('title', 'Galeri - Goa Sentono')
@section('description', 'Jelajahi koleksi foto menakjubkan dari keindahan alam dan situs bersejarah Goa Sentono. Lihat momen-momen terbaik yang diabadikan di destinasi wisata Blora ini.')
@section('keywords', 'galeri Goa Sentono, foto Goa Sentono, wisata Blora, keindahan alam, situs bersejarah')


@section('description', 'Lihat koleksi foto-foto menakjubkan dari Goa Sentono. Galeri lengkap keindahan alam dan situs bersejarah di Blora, Jawa Tengah.')
@section('keywords', 'galeri Goa Sentono, foto wisata Blora, gambar gua alam, koleksi foto wisata Jawa Tengah')

@section('content')
    <!-- Hero Section -->
    <section class="hero-video text-white py-16 sm:py-24 lg:py-32 relative min-h-[60vh] sm:min-h-screen flex items-center bg-cover bg-center" style="background-image: url('{{ asset('images/DJI_20250719163310_0090_D.jpg') }}');">
        <div class="video-fallback opacity-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full flex flex-col justify-center space-y-2 hero-content animate-fade-in sm:text-left">
            <span class="text-base sm:text-xl md:text-2xl font-medium">KOLEKSI FOTO</span>
            <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold leading-tight">SITUS GOA SENTONO</h1>
        </div>
    </section>

    <!-- Main Gallery Section -->
    <section class="py-12 sm:py-16 lg:py-24 section-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6">Galeri Goa Sentono</h2>
            </div>

            <!-- Galeri Grid Dinamis - Responsive Layout -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 my-6 sm:my-8 lg:my-10" id="gallery-grid">
                @if($galeriItems->isNotEmpty())
                    @foreach($galeriItems as $item)
                        <div class="gallery-item bg-white rounded-2xl md:rounded-3xl shadow-lg overflow-hidden card-hover">
                            <div class="relative h-48 sm:h-56 md:h-64 overflow-hidden group cursor-pointer" onclick="openImageModal({{ $item->id }})">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:opacity-80 transition duration-300">
                            </div>
                            
                            <div class="p-3 sm:p-4 md:p-6">
                                <h4 class="font-bold text-primary mb-1 text-center truncate text-sm sm:text-base md:text-xl">{{ $item->judul }}</h4>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-sm sm:text-base lg:text-lg col-span-2 md:col-span-3 text-center py-8">Saat ini belum ada foto di galeri.</p>
                @endif
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-8 sm:mt-12 lg:mt-16">
                <button id="load-more" class="bg-primary text-white px-8 sm:px-10 md:px-12 py-3 md:py-4 rounded-full font-bold text-sm sm:text-base lg:text-lg hover:bg-opacity-90 transition duration-300 transform hover:scale-105 shadow-lg">
                    Muat Lebih Banyak Foto
                </button>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
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
@endsection

{{-- Data untuk JavaScript --}}
@php
    // Build exactly the shape we need:
    $initialGallery = $galeriItems
        ->getCollection()
        ->map(function($item) {
            return [
                'id'         => $item->id,
                'judul'      => $item->judul,
                'deskripsi'  => $item->deskripsi,
                'gambar_url' => $item->gambar_url,
            ];
        })
        ->keyBy('id');
@endphp

@push('scripts')
<script>
    // Initialize gallery functionality
    document.addEventListener("DOMContentLoaded", function() {
        const initialGalleryData = {
            data: {!! $initialGallery->toJson() !!},
            currentPage: {{ $galeriItems->currentPage() }},
            lastPage: {{ $galeriItems->lastPage() }}
        };
        
        let galleryData = {};
        let currentPage = initialGalleryData.currentPage;
        let lastPage = initialGalleryData.lastPage;
        let isLoading = false;

        // Populate initial gallery data
        if (initialGalleryData.data) {
            Object.values(initialGalleryData.data).forEach(item => {
                galleryData[item.id] = item;
            });
        }

        const galleryGrid = document.getElementById('gallery-grid');
        const loadMoreBtn = document.getElementById('load-more');
        const imageModal = document.getElementById('image-modal');
        const modalContent = document.getElementById('modal-content');
        const modalCloseBtn = document.getElementById('modal-close');
        const modalBackdrop = document.getElementById('modal-backdrop');

        // Hide load more button if no more pages
        if (currentPage >= lastPage) {
            loadMoreBtn.style.display = 'none';
        }

        // Global function for opening image modal - UPDATED TO MATCH HOME MODAL
        window.openImageModal = function(itemId) {
            const item = galleryData[itemId];
            if (!item) {
                console.error('Item not found:', itemId);
                return;
            }

            // Fill modal with responsive image content - SAME AS HOME
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
            
            // Show modal
            imageModal.classList.remove('hidden');
            imageModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Add fade in animation
            setTimeout(() => {
                imageModal.style.opacity = '1';
            }, 10);
        };

        function closeImageModal() {
            // Add fade out animation
            imageModal.style.opacity = '0';
            
            setTimeout(() => {
                imageModal.classList.add('hidden');
                imageModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                modalContent.innerHTML = '';
            }, 300);
        }

        async function loadMoreItems() {
            if (isLoading || currentPage >= lastPage) return;
            
            isLoading = true;
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Memuat...';
            
            const nextPage = currentPage + 1;

            try {
                const response = await fetch(`/api/galeri-items?page=${nextPage}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                
                const newData = await response.json();

                newData.data.forEach(item => {
                    galleryData[item.id] = item;
                    const itemHtml = createGalleryItemHtml(item);
                    galleryGrid.insertAdjacentHTML('beforeend', itemHtml);
                });

                currentPage = newData.current_page;
                lastPage = newData.last_page;
                
                if (currentPage >= lastPage) {
                    loadMoreBtn.style.display = 'none';
                }
                
            } catch (error) {
                console.error('Error saat memuat item:', error);
                loadMoreBtn.textContent = 'Gagal Memuat. Coba Lagi.';
            } finally {
                isLoading = false;
                loadMoreBtn.disabled = false;
                if (currentPage < lastPage) {
                    loadMoreBtn.textContent = 'Muat Lebih Banyak Foto';
                }
            }
        }

        function createGalleryItemHtml(item) {
            return `
                <div class="gallery-item bg-white rounded-2xl md:rounded-3xl shadow-lg overflow-hidden card-hover">
                    <div class="relative h-48 sm:h-56 md:h-64 overflow-hidden group cursor-pointer" onclick="openImageModal(${item.id})">
                        <img src="${item.gambar_url}" alt="${item.judul}" class="w-full h-full object-cover group-hover:opacity-80 transition duration-300">
                    </div>
                    <div class="p-3 sm:p-4 md:p-6">
                        <h4 class="font-bold text-primary mb-1 text-center truncate text-sm sm:text-base md:text-xl">${item.judul}</h4>
                    </div>
                </div>
            `;
        }

        // Event listeners
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', loadMoreItems);
        }

        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', closeImageModal);
        }
        
        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', (e) => {
                if (e.target === modalBackdrop) {
                    closeImageModal();
                }
            });
        }
        
        // Touch/swipe support for mobile - ADDED FROM HOME MODAL
        let touchStartY = 0;
        if (modalContent) {
            modalContent.addEventListener('touchstart', (e) => {
                touchStartY = e.touches[0].clientY;
            });
            
            modalContent.addEventListener('touchend', (e) => {
                const touchEndY = e.changedTouches[0].clientY;
                const deltaY = touchStartY - touchEndY;
                
                // Close modal if swiped down significantly (more than 100px)
                if (deltaY < -100) {
                    closeImageModal();
                }
            });
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
                closeImageModal();
            }
        });
        
        // Initialize modal styles
        if (imageModal) {
            imageModal.style.transition = 'opacity 0.3s ease-in-out';
            imageModal.style.opacity = '0';
        }
        
        console.log('Gallery initialized with data:', galleryData);
    });
</script>
@endpush