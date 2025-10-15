{{-- resources\views\home.blade.php --}}

@extends('layouts.app')

@section('title', 'Goa Sentono')
@section('description', 'Jelajahi keindahan Goa Sentono, destinasi wisata alam dan situs bersejarah yang menakjubkan di Kradenan, Blora, Jawa Tengah. Nikmati pengalaman wisata yang tak terlupakan dengan fasilitas lengkap.')
@section('keywords', 'Goa Sentono, wisata Blora, wisata Jawa Tengah, gua alam, situs bersejarah, wisata alam Indonesia, destinasi wisata Kradenan, tempat wisata Blora')

@section('content')
    {{-- Hero Section (Tetap statis sesuai desain) --}}
    <section class="hero-video text-white py-16 sm:py-24 lg:py-32 relative min-h-[60vh] sm:min-h-screen flex items-center bg-cover bg-center" style="background-image: url('{{ asset('images/IMG_3699x.jpg') }}');">
        <div class="video-fallback opacity-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full flex flex-col justify-center space-y-2 hero-content animate-fade-in sm:text-left">
            <span class="text-base sm:text-xl md:text-2xl font-medium">
                SELAMAT DATANG DI
            </span>
            <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold leading-tight">
                SITUS GOA SENTONO
            </h1>
        </div>
    </section>

    <!-- Galeri Section -->
    <section id="galeri" class="py-24 section-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-primary mb-6">Galeri Keindahan</h2>
                <p class="text-xl text-accent max-w-2xl mx-auto">Kumpulan foto-foto menakjubkan dari Goa Sentono</p>
            </div>
            
            {{-- Grid Galeri Dinamis - Updated for responsive layout --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 mb-16">
                @forelse($galeriItems as $item)
                    <div class="bg-white rounded-2xl md:rounded-3xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="block relative h-48 md:h-64 group cursor-pointer" onclick="openImageModal({{ $item->id }})">
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity duration-300" loading="lazy">
                        </div>
                        <div class="p-3 md:p-6 text-center">
                            <h3 class="text-sm md:text-2xl font-semibold text-primary truncate">{{ $item->judul }}</h3>
                        </div>
                    </div>
                @empty
                    <p class="col-span-2 md:col-span-3 text-center text-gray-500">Galeri belum memiliki foto.</p>
                @endforelse
            </div>
            
            <div class="text-center">
                <a href="{{ route('galeri') }}" class="inline-block bg-primary text-white px-8 md:px-12 py-3 md:py-4 rounded-full text-sm md:text-base font-semibold hover:bg-opacity-90 transform hover:scale-105 transition duration-300 shadow-lg" loading="lazy">
                    Lihat Semua Galeri
                </a>
            </div>
        </div>
    </section>

    <!-- Artikel Section -->
    <section id="artikel" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-primary mb-6">Artikel & Berita</h2>
                <p class="text-xl text-accent max-w-2xl mx-auto">Informasi terkini seputar Situs Goa Sentono</p>
            </div>
            
            {{-- Grid Artikel Dinamis - Updated for responsive layout --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 mb-16">
                @forelse($artikels as $artikel)
                    <article class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden card-hover border border-gray-100 flex flex-col">
                        <a href="{{ route('artikel.show', $artikel->slug) }}" class="block h-32 md:h-48 overflow-hidden">
                            <img src="{{ $artikel->gambar_url }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover transition duration-300 hover:scale-105">
                        </a>
                        <div class="px-3 md:px-8 py-3 md:py-6 flex flex-col flex-grow">
                            <div class="flex items-center mb-2 md:mb-4">
                                <span class="text-xs md:text-sm text-gray-500">{{ $artikel->published_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-sm md:text-xl font-bold text-primary mb-2 md:mb-4 flex-grow line-clamp-2 md:line-clamp-none">
                                <a href="{{ route('artikel.show', $artikel->slug) }}" class="hover:text-opacity-80">{{ $artikel->judul }}</a>
                            </h3>
                            <p class="text-xs md:text-base text-accent leading-relaxed mb-3 md:mb-6 line-clamp-2 md:line-clamp-3">{{ $artikel->ringkasan_potong }}</p>
                            <a href="{{ route('artikel.show', $artikel->slug) }}" class="text-xs md:text-base text-primary font-bold hover:text-accent transition duration-300 flex items-center mt-auto">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-2 md:col-span-3 text-center text-gray-500">Belum ada artikel yang dipublikasikan.</p>
                @endforelse
            </div>
            
            <div class="text-center">
                <a href="{{ route('artikel') }}" class="inline-block bg-primary text-white px-8 md:px-12 py-3 md:py-4 rounded-full text-sm md:text-base font-semibold hover:bg-opacity-90 transform hover:scale-105 transition duration-300 shadow-lg">
                    Lihat Semua Artikel
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 lg:py-24 relative overflow-hidden bg-cover bg-center bg-fixed" 
             style="background-image: url('{{ asset('images/goa-sentono.jpeg') }}');">
        
        <!-- Floating Elements (Optional untuk dekorasi) -->
        <div class="floating-elements">
            <div class="floating-element-light" style="width: 40px; height: 40px; top: 10%; left: 10%; animation-delay: 1s;"></div>
            <div class="floating-element-light" style="width: 30px; height: 30px; top: 70%; left: 85%; animation-delay: 3s;"></div>
            <div class="floating-element-light" style="width: 50px; height: 50px; top: 40%; left: 90%; animation-delay: 5s;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 md:mb-6 lg:mb-8 text-white drop-shadow-lg">
                Kunjungi Sekarang!
            </h2>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 md:mb-8 max-w-3xl mx-auto leading-relaxed px-4 text-white drop-shadow-md">
                Temukan keindahan alam yang tak terlupakan di Goa Sentono
            </p>

            <div class="flex flex-row gap-2 sm:gap-3 md:gap-4 justify-center items-center max-w-sm sm:max-w-md md:max-w-none mx-auto">
                <a href="{{ route('kontak') }}"
                    class="flex-1 sm:flex-none bg-white bg-opacity-90 text-primary px-3 sm:px-6 md:px-8 lg:px-12 py-3 md:py-4 rounded-full font-bold text-xs sm:text-sm md:text-base lg:text-lg hover:bg-opacity-100 transition duration-300 transform hover:scale-105 shadow-2xl text-center backdrop-blur-sm">
                    <i class="fa-solid fa-phone text-primary mr-2"></i>
                    Hubungi Kami
                </a>

                <a href="{{ route('tentang') }}#peta-lokasi"
                    class="flex-1 sm:flex-none bg-white bg-opacity-90 text-primary px-3 sm:px-6 md:px-8 lg:px-12 py-3 md:py-4 rounded-full font-bold text-xs sm:text-sm md:text-base lg:text-lg hover:bg-opacity-100 transition duration-300 transform hover:scale-105 shadow-2xl text-center backdrop-blur-sm">
                    <i class="fa-solid fa-location-dot text-primary mr-2"></i>
                    Lihat Lokasi
                </a>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal untuk Galeri -->
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

@php
    $homeGalleryData = $galeriItems->map(function($item) {
        return [
            'id'         => $item->id,
            'judul'      => $item->judul,
            'deskripsi'  => $item->deskripsi,
            'gambar_url' => $item->gambar_url,
        ];
    })->keyBy('id');
@endphp

@push('scripts')
<script>
    // Data galeri untuk modal di halaman home
    const homeGalleryData = {!! $homeGalleryData->toJson() !!};
    
    // Initialize home gallery modal functionality
    document.addEventListener("DOMContentLoaded", function() {
        const imageModal = document.getElementById('image-modal');
        const modalContent = document.getElementById('modal-content');
        const modalCloseBtn = document.getElementById('modal-close');
        const modalBackdrop = document.getElementById('modal-backdrop');

        if (!imageModal || !modalContent || !modalCloseBtn || !modalBackdrop) {
            console.error('Modal elements not found on home page');
            return;
        }

        // Make openImageModal function global for home page
        window.openImageModal = function(itemId) {
            console.log('Opening modal for item:', itemId);
            console.log('Available home gallery data:', homeGalleryData);
            
            const item = homeGalleryData[itemId];
            if (!item) {
                console.error('Item not found:', itemId);
                return;
            }

            // Fill modal with image content
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

        // Event listeners for modal
        modalCloseBtn.addEventListener('click', closeImageModal);
        
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) {
                closeImageModal();
            }
        });
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
                closeImageModal();
            }
        });
        
        // Initialize modal styles
        imageModal.style.transition = 'opacity 0.3s ease-in-out';
        imageModal.style.opacity = '0';
        
        console.log('Home gallery modal initialized');
    });
</script>
@endpush
@endsection