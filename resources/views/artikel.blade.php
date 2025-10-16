{{-- resources\views\artikel.blade.php --}}

@extends('layouts.app')

@section('title', 'Artikel - Goa Sentono')
@section('description', 'Baca artikel, berita, dan informasi terbaru seputar Situs Goa Sentono. Dapatkan wawasan tentang sejarah, keunikan, dan acara yang diselenggarakan.')
@section('keywords', 'artikel Goa Sentono, berita Goa Sentono, blog wisata Blora, sejarah Goa Sentono')


@section('content')
    <!-- Hero Section untuk Artikel -->
    <section class="hero-video text-white py-32 relative min-h-screen flex items-center bg-cover bg-center" style="background-image: url('{{ asset('images/DJI_20250719165955_0104_D.jpg') }}');">
        <div class="video-fallback opacity-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full flex flex-col justify-center space-y-2 hero-content animate-fade-in sm:text-left">
            <span class="text-base sm:text-xl md:text-2xl font-medium">TEMUKAN INFORMASI MENARIK</span>
            <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold leading-tight">ARTIKEL DAN BERITA</h1>
        </div>
    </section>

    <!-- Artikel Utama -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6">Artikel Utama</h2>
            </div>

            <!-- Featured Article Dinamis -->
            @if($featuredArtikel)
            <div class="mb-8 sm:mb-12 lg:mb-16">
                <div class="bg-cream rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden">
                    <div class="md:flex md:h-96 lg:h-[450px]">
                        <!-- Gambar Featured - UKURAN TETAP -->
                        <div class="md:w-1/2 h-64 md:h-full">
                            <a href="{{ route('artikel.show', $featuredArtikel->slug) }}" class="block w-full h-full overflow-hidden relative">
                                <img
                                    src="{{ $featuredArtikel->gambar_url }}"
                                    alt="{{ $featuredArtikel->judul }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                >
                                <!-- Label UNGGULAN -->
                                <div class="absolute top-3 left-3 sm:top-4 sm:left-4 lg:top-6 lg:left-6 z-10">
                                    <span class="bg-red-500 text-white px-2 sm:px-3 lg:px-4 py-1 sm:py-1.5 lg:py-2 rounded-full font-bold text-xs sm:text-sm">UNGGULAN</span>
                                </div>
                            </a>
                        </div>

                        <!-- Konten Featured -->
                        <div class="md:w-1/2 p-4 sm:p-6 md:p-8 lg:p-12 flex flex-col justify-center">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <span class="text-xs sm:text-sm text-gray-500">{{ $featuredArtikel->published_at->format('d F Y') }}</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-primary mb-3 sm:mb-4 lg:mb-6 leading-tight">
                                <a href="{{ route('artikel.show', $featuredArtikel->slug) }}" class="hover:text-opacity-80">{{ $featuredArtikel->judul }}</a>
                            </h2>
                            <p class="text-accent leading-relaxed mb-4 sm:mb-6 lg:mb-8 text-sm sm:text-base lg:text-lg line-clamp-3 md:line-clamp-none">
                                {{ $featuredArtikel->ringkasan_potong }}
                            </p>
                            <a href="{{ route('artikel.show', $featuredArtikel->slug) }}" class="bg-primary text-white px-4 sm:px-6 lg:px-8 py-2 sm:py-2.5 lg:py-3 rounded-full font-bold text-sm sm:text-base hover:bg-opacity-90 transition duration-300 self-start">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-6 sm:py-8 mb-6 sm:mb-8">
                <p class="text-gray-500 text-sm sm:text-base">Saat ini tidak ada artikel utama.</p>
            </div>
            @endif

            <!-- Grid Artikel Dinamis - Responsive Layout -->
            @if($artikels->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                @foreach($artikels as $artikel)
                <article class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden card-hover border border-gray-100 flex flex-col">
                    <!-- Gambar Artikel Grid - UKURAN TETAP -->
                    <a href="{{ route('artikel.show', $artikel->slug) }}" class="block h-32 sm:h-40 md:h-48 lg:h-52 overflow-hidden">
                        <img src="{{ $artikel->gambar_url }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover transition duration-300 hover:scale-105">
                    </a>

                    <div class="px-3 sm:px-4 md:px-6 lg:px-8 py-3 sm:py-4 md:py-6 flex flex-col flex-grow">
                        <div class="flex items-center mb-2 sm:mb-3 md:mb-4">
                            <span class="text-xs md:text-sm text-gray-500">{{ $artikel->published_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-primary mb-2 sm:mb-3 md:mb-4 flex-grow line-clamp-2">
                            <a href="{{ route('artikel.show', $artikel->slug) }}" class="hover:text-opacity-80">{{ $artikel->judul }}</a>
                        </h3>
                        <p class="text-accent leading-relaxed mb-3 sm:mb-4 md:mb-6 text-xs sm:text-sm md:text-base line-clamp-2 md:line-clamp-3">{{ $artikel->ringkasan_potong }}</p>
                        <a href="{{ route('artikel.show', $artikel->slug) }}" class="text-primary font-bold hover:text-accent transition duration-300 flex items-center mt-auto text-xs sm:text-sm md:text-base">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 sm:py-12">
                <p class="text-gray-500 text-sm sm:text-base">Tidak ada artikel lain yang tersedia saat ini.</p>
            </div>
            @endif

            <!-- Pagination Dinamis -->
            <div class="mt-8 sm:mt-12 lg:mt-16">
                <div class="flex justify-center">
                    <div class="pagination-wrapper">
                        {{ $artikels->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection