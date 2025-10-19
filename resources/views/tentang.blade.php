{{-- resources\views\tentang.blade.php --}}
@extends('layouts.app')

@section('title', 'Goa Sentono')
@section('description', 'Pelajari sejarah lengkap Goa Sentono, situs bersejarah di Kradenan Blora. Temukan informasi lokasi, fasilitas, wisata sekitar, dan cara menuju destinasi wisata alam yang menakjubkan ini.')
@section('keywords', 'tentang Goa Sentono, sejarah Goa Sentono, lokasi Goa Sentono, fasilitas wisata Blora, situs bersejarah Kradenan, peta lokasi Goa Sentono, wisata sekitar Blora, transportasi ke Goa Sentono, alamat Goa Sentono')


@section('content')
<body class="bg-white">
    <section class="hero-video text-white py-16 sm:py-24 lg:py-32 relative min-h-[60vh] sm:min-h-screen flex items-center bg-cover bg-center" style="background-image: url('{{ asset('images/IMG_0721.jpg') }}');">
        <div class="video-fallback opacity-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full flex flex-col justify-center space-y-2 hero-content animate-fade-in sm:text-left">
            <span class="text-base sm:text-xl md:text-2xl font-medium">
                MENELUSURI JEJAK
            </span>
            <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold leading-tight">
                SITUS GOA SENTONO
            </h1>
        </div>
    </section>

    <section class="py-12 sm:py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
            
            @if($sejarahSliders->isNotEmpty())
            <div class="relative order-2 lg:order-1">
                <div class="overflow-hidden rounded-2xl sm:rounded-3xl shadow-xl">
                    <div class="flex transition-transform duration-500 ease-in-out" 
                        id="sejarah-images" data-count="{{ $sejarahSliders->count() }}" data-current="0">
                        
                        @foreach($sejarahSliders as $slide)
                            <div class="flex-shrink-0 w-full">
                                <div class="aspect-square">
                                    <img src="{{ $slide->gambar_url }}" 
                                        alt="{{ $slide->alt_text ?? 'Gambar Sejarah Goa Sentono' }}" 
                                        class="w-full h-full object-cover rounded-2xl sm:rounded-3xl" loading="lazy">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button id="sejarah-prev" class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 shadow-lg rounded-full p-2 sm:p-3 hover:bg-opacity-100 z-10">
                    <i class="fas fa-chevron-left text-primary text-base sm:text-lg"></i>
                </button>
                <button id="sejarah-next" class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 shadow-lg rounded-full p-2 sm:p-3 hover:bg-opacity-100 z-10">
                    <i class="fas fa-chevron-right text-primary text-base sm:text-lg"></i>
                </button>
                <div class="flex justify-center mt-4 sm:mt-6 space-x-2">
                    @foreach($sejarahSliders as $index => $slide)
                    <button data-slide="{{ $index }}" class="sejarah-dot w-2 sm:w-3 h-2 sm:h-3 rounded-full {{ $index == 0 ? 'bg-primary' : 'bg-gray-300' }} transition duration-300"></button>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="order-1 lg:order-2 text-center lg:text-left">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6 lg:mb-8">
                    Sejarah Goa Sentono
                </h2>
                <div class="space-y-4 sm:space-y-6 text-accent leading-relaxed text-sm sm:text-base lg:text-lg">
                    {!! nl2br(e($settings['sejarah_deskripsi'] ?? 'Deskripsi sejarah belum diatur.')) !!}
                </div>
            </div>
            </div>
        </div>
    </section>

    <section class="py-12 sm:py-16 lg:py-24 section-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6">
                    Video Profil
                </h2>
            </div>
            <div class="group bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden transform transition hover:scale-[1.02] hover:shadow-2xl duration-300">
                <div class="relative pb-[56.25%]">
                    <iframe
                    class="absolute inset-0 w-full h-full"
                    src="{{ $settings['video_url'] ?? '' }}"
                    title="Video Cerita Sejarah Goa Sentono"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                    </iframe>
                    <div class="absolute bottom-0 left-0 right-0 h-16 sm:h-32 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                </div>
                <div class="p-4 sm:p-6 lg:p-8">
                    <p class="text-accent text-center leading-relaxed text-sm sm:text-base flex items-center justify-center">
                        <i class="fas fa-play-circle text-primary mr-2"></i>
                        Saksikan video profil Goa Sentono
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 sm:py-16 lg:py-24 bg-white" id="peta-lokasi">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6">
                    Peta Lokasi
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-accent max-w-2xl mx-auto px-4 sm:px-0">
                    Temukan lokasi Goa Sentono dan rencanakan perjalanan
                </p>
            </div>
            
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden">
                <div class="relative bg-gradient-to-b from-blue-50 to-green-50">
                    <div class="absolute top-4 left-4 z-20 bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg p-3 sm:p-4">
                        <h3 class="text-sm sm:text-base font-bold text-primary mb-2 flex items-center">
                            <i class="fas fa-cubes mr-2"></i>
                            Kontrol 3D
                        </h3>
                        <div class="space-y-2">
                            <div class="grid grid-cols-3 gap-1">
                                <button id="view-top" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    Atas
                                </button>
                                <button id="view-side" class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    Samping
                                </button>
                                <button id="view-iso" class="bg-purple-500 text-white px-2 py-1 rounded text-xs hover:bg-purple-600 transition">
                                    <i class="fas fa-cube mr-1"></i>
                                    Reset View
                                </button>
                            </div>
                            
                            <label class="flex items-center justify-between text-xs sm:text-sm text-accent cursor-pointer">
                                <span class="flex items-center">
                                    <i class="fas fa-sync-alt mr-1"></i>
                                    Auto Rotate
                                </span>
                                <input type="checkbox" id="auto-rotate-toggle" class="toggle-checkbox" checked>
                            </label>
                        </div>
                    </div>

                    <div class="absolute top-4 right-4 z-20 bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg p-3 sm:p-4 max-w-xs">
                        <h3 class="text-sm sm:text-base font-bold text-primary mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Panduan
                        </h3>
                        <div class="text-xs sm:text-sm text-accent space-y-1">
                            <p class="flex items-center">
                                <i class="fas fa-mouse-pointer mr-2 text-primary"></i>
                                Klik & drag untuk memutar
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-search mr-2 text-primary"></i>
                                Scroll untuk zoom
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-hand-paper mr-2 text-primary"></i>
                                2 jari untuk pan (mobile)
                            </p>
                        </div>
                    </div>

                    <div id="model-loading" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-30">
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-4"></div>
                            <p class="text-accent font-medium">Memuat Model 3D...</p>
                            <p class="text-sm text-gray-500 mt-1">Harap tunggu sebentar</p>
                        </div>
                    </div>

                    <model-viewer 
                        id="map-3d-viewer"
                        src="{{ asset('models/model-web-1.glb') }}"
                        alt="Model 3D Goa Sentono sebagai Peta"
                        camera-controls
                        enable-pan
                        touch-action="pan-y"
                        auto-rotate
                        auto-rotate-delay="3000"
                        rotation-per-second="30deg"
                        min-camera-orbit="auto 0deg auto"
                        max-camera-orbit="auto 90deg auto"
                        camera-orbit="45deg 75deg 5m"
                        field-of-view="30deg"
                        min-field-of-view="5deg"
                        max-field-of-view="20deg"
                        shadow-intensity="1"
                        shadow-softness="0.5"
                        exposure="1"
                        environment-image="neutral"
                        style="width: 100%; height: 400px; --poster-color: #f0f8ff;"
                        poster="{{ asset('images/model-poster.jpg') }}"
                        loading="eager">
                        
                        <div slot="poster" class="flex items-center justify-center h-full bg-gradient-to-b from-blue-50 to-green-50">
                            <div class="text-center">
                                <i class="fas fa-mountain text-4xl text-primary mb-4"></i>
                                <p class="text-accent font-medium">Klik untuk memuat model 3D</p>
                            </div>
                        </div>

                        <div slot="error" class="flex items-center justify-center h-full bg-red-50">
                            <div class="text-center text-red-600">
                                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                                <p class="font-medium">Gagal memuat model 3D</p>
                                <p class="text-sm mt-2">Periksa koneksi internet Anda</p>
                            </div>
                        </div>
                    </model-viewer>

                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-20 bg-white bg-opacity-90 backdrop-blur-sm rounded-full shadow-lg px-4 py-2">
                        <div class="flex items-center space-x-3">
                            <button id="zoom-in" class="p-2 text-primary hover:bg-primary hover:text-white rounded-full transition duration-300">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                            <button id="zoom-out" class="p-2 text-primary hover:bg-primary hover:text-white rounded-full transition duration-300">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            
                            <div class="w-px h-6 bg-gray-300"></div>
                            
                            <button id="fullscreen-toggle" class="p-2 text-primary hover:bg-primary hover:text-white rounded-full transition duration-300">
                                <i class="fas fa-expand text-sm"></i>
                            </button>
                            
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 my-6 sm:my-8 justify-center px-4">
                    <a href="https://maps.google.com?q=Goa+Sentono" target="_blank"
                        class="w-full sm:w-auto min-w-[300px] bg-primary text-white px-6 sm:px-8 py-3 rounded-full font-bold text-sm sm:text-base hover:bg-opacity-90 transition duration-300 transform hover:scale-105 shadow-lg text-center flex items-center justify-center">
                        <i class="fab fa-google text-white mr-2"></i>
                        Lihat di Google Maps
                    </a>

                    <button onclick="openPeta2DModal()"
                        class="w-full sm:w-auto min-w-[300px] bg-primary text-white px-6 sm:px-8 py-3 rounded-full font-bold text-sm sm:text-base hover:bg-opacity-90 transition duration-300 transform hover:scale-105 shadow-lg text-center flex items-center justify-center">
                        <i class="fas fa-map text-white mr-2"></i>
                        Lihat Peta 2D
                    </button>
                </div>
            </div>
            
            <div class="py-6 sm:py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    <div class="bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-lg hover:shadow-2xl transition duration-300">
                        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                            <div class="bg-primary text-white p-2 sm:p-3 rounded-full shadow-md">
                                <i class="fas fa-map-pin text-base sm:text-xl"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-primary">Alamat Lengkap</h3>
                        </div>
                        <p class="text-accent leading-relaxed mb-3 sm:mb-4 text-sm sm:text-base">
                            Goa Sentono<br>
                            Nglaren, Mendenrejo, Kradenan,<br>
                            Blora, Jawa Tengah 58383<br>
                            Indonesia
                        </p>
                        <div class="space-y-1 sm:space-y-2 text-accent text-sm sm:text-base">
                            <p class="flex items-center">
                                <i class="fas fa-envelope text-primary mr-2"></i>
                                <strong>Email:</strong> 
                                <span class="ml-1">exploresentono2k25@gmail.com</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-lg hover:shadow-2xl transition duration-300">
                        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                            <div class="bg-primary text-white p-2 sm:p-3 rounded-full shadow-md">
                                <i class="fas fa-bus text-base sm:text-xl"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-primary">Akses Transportasi</h3>
                        </div>
                        <ul class="space-y-1 sm:space-y-2 text-accent text-sm sm:text-base">
                            <li class="flex items-center">
                                <i class="fas fa-route text-primary mr-2 text-xs"></i>
                                40 km dari Alun-alun Blora
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-building text-primary mr-2 text-xs"></i>
                                1 km dari Kantor Kecamatan Kradenan, Blora
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-bus-alt text-primary mr-2 text-xs"></i>
                                Dapat Diakses Menggunakan Tranportasi Umum
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div id="peta-2d-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 transition-opacity duration-300">
        <div class="w-full h-full flex items-center justify-center p-2 sm:p-4 md:p-8" id="peta-modal-backdrop">
            <button id="peta-modal-close" class="absolute top-4 right-4 sm:top-6 sm:right-6 text-white hover:text-red-400 transition-all duration-300 z-30 group">
                <i class="fa-solid fa-circle-xmark fa-2xl"></i>
            </button>
            
            <div id="peta-modal-content" class="relative w-full max-w-6xl max-h-full bg-white rounded-2xl overflow-hidden shadow-2xl">
                <div class="bg-primary text-white p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold flex items-center">
                            <i class="fas fa-map mr-3"></i>
                            Peta Lokasi Goa Sentono
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="downloadPeta2D()" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg text-sm font-medium transition duration-300">
                                <i class="fas fa-download mr-1"></i>
                                Unduh Peta
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="relative overflow-auto max-h-[80vh]" id="peta-container">
                    <img id="peta-image" 
                         src="{{ asset('images/peta_2d_new.png') }}" 
                         alt="Peta 2D Goa Sentono" 
                         class="w-full h-auto"
                         draggable="false">
                    
                    <div id="peta-loading" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-2"></div>
                            <p class="text-gray-600">Memuat peta...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-12 sm:py-16 lg:py-24 section-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6">
                    Fasilitas
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-accent max-w-2xl mx-auto px-4 sm:px-0">Nikmati fasilitas untuk pengalaman wisata yang nyaman dan berkesan</p>
            </div>

            @if($fasilitasItems->isNotEmpty())
                @php $chunks = $fasilitasItems->chunk(4); @endphp
                <div class="relative min-h-[200px] sm:min-h-[250px] md:min-h-[350px]">
                    <div class="overflow-hidden rounded-2xl sm:rounded-3xl h-[450px]">
                        <div id="fasilitas-carousel" class="flex transition-transform duration-500 ease-in-out h-full" data-pages="{{ count($chunks) }}">
                            @foreach($chunks as $chunk)
                                <div class="flex-shrink-0 w-full grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 p-1 auto-rows-fr">
                                    @foreach($chunk as $item)
                                        <div class="bg-cream rounded-2xl md:rounded-3xl shadow-xl overflow-hidden flex flex-col h-full max-w-xs mx-auto">
                                            <div class="aspect-[4/3] overflow-hidden">
                                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover" loading="lazy">
                                            </div>
                                            <div class="p-3 sm:p-4 md:p-6 flex-1 flex flex-col">
                                                <h3 class="text-sm sm:text-lg md:text-2xl font-bold text-primary mb-1 sm:mb-2 line-clamp-2 flex items-center">
                                                    {{ $item->judul }}
                                                </h3>
                                                <p class="text-accent leading-relaxed text-xs sm:text-sm md:text-base line-clamp-3 flex-1">{{ $item->deskripsi }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @for($i = count($chunk); $i < 4; $i++)
                                        <div class="max-w-xs mx-auto"></div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button id="fasilitas-prev" class="slider-nav-btn absolute left-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 sm:p-3 rounded-full shadow-xl hover:bg-accent transition duration-300 z-10 -ml-2 sm:-ml-4">
                        <i class="fas fa-chevron-left text-base sm:text-lg"></i>
                    </button>
                    <button id="fasilitas-next" class="slider-nav-btn absolute right-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 sm:p-3 rounded-full shadow-xl hover:bg-accent transition duration-300 z-10 -mr-2 sm:-mr-4">
                        <i class="fas fa-chevron-right text-base sm:text-lg"></i>
                    </button>
                </div>
            @endif
        </div>
    </section>

    <section class="py-12 sm:py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 sm:mb-6">
                    Wisata Sekitar
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-accent max-w-2xl mx-auto px-4 sm:px-0">Kunjungi wisata menarik lainnya di sekitar Goa Sentono</p>
            </div>

            @if($wisataItems->isNotEmpty())
                @php $chunks = $wisataItems->chunk(4); @endphp
                <div class="relative min-h-[200px] sm:min-h-[250px] md:min-h-[350px]">
                    <div class="overflow-hidden rounded-2xl sm:rounded-3xl h-[450px]">
                        <div id="wisata-carousel" class="flex transition-transform duration-500 ease-in-out h-full" data-pages="{{ count($chunks) }}">
                            @foreach($chunks as $chunk)
                                <div class="flex-shrink-0 w-full grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 p-1 auto-rows-fr">
                                    @foreach($chunk as $item)
                                        <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden flex flex-col h-full max-w-xs mx-auto">
                                            <div class="aspect-[4/3] overflow-hidden">
                                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover" loading="lazy">
                                            </div>
                                            <div class="p-3 sm:p-4 md:p-6 flex-1 flex flex-col">
                                                <h3 class="text-sm sm:text-lg md:text-2xl font-bold text-primary mb-1 sm:mb-2 flex items-center">
                                                    {{ $item->judul }}
                                                </h3>
                                                <p class="text-accent leading-relaxed mb-1 sm:mb-2 text-xs sm:text-sm md:text-base">{{ $item->deskripsi }}</p>
                                                @if($item->info_tambahan)
                                                    <span class="text-xs sm:text-sm text-gray-500 mb-1 sm:mb-2 flex items-center">
                                                        <i class="fas fa-info-circle text-primary mr-1"></i>
                                                        {{ $item->info_tambahan }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @for($i = count($chunk); $i < 4; $i++)
                                        <div class="max-w-xs mx-auto"></div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button id="wisata-prev" class="slider-nav-btn absolute left-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 sm:p-3 rounded-full shadow-xl hover:bg-accent transition duration-300 z-10 -ml-2 sm:-ml-4">
                        <i class="fas fa-chevron-left text-base sm:text-lg"></i>
                    </button>
                    <button id="wisata-next" class="slider-nav-btn absolute right-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 sm:p-3 rounded-full shadow-xl hover:bg-accent transition duration-300 z-10 -mr-2 sm:-mr-4">
                        <i class="fas fa-chevron-right text-base sm:text-lg"></i>
                    </button>
                </div>
            @endif
        </div>
    </section>

@endsection

<style>
.toggle-checkbox {
    appearance: none;
    width: 2rem;
    height: 1rem;
    background-color: #d1d5db;
    border-radius: 9999px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s;
}

.toggle-checkbox:checked {
    background-color: #10b981;
}

.toggle-checkbox::before {
    content: '';
    position: absolute;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: white;
    top: 0.125rem;
    left: 0.125rem;
    transition: transform 0.3s;
}

.toggle-checkbox:checked::before {
    transform: translateX(1rem);
}

model-viewer {
    --progress-bar-color: #3b82f6;
    --progress-bar-height: 4px;
}

.fullscreen-active {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    background: white !important;
}

.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 640px) {
    .absolute.top-4.left-4,
    .absolute.top-4.right-4 {
        position: relative;
        top: auto;
        left: auto;
        right: auto;
        margin: 1rem;
        max-width: none;
    }
    
    model-viewer {
        height: 300px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modelViewer = document.getElementById('map-3d-viewer');
    const loadingOverlay = document.getElementById('model-loading');
    const autoRotateToggle = document.getElementById('auto-rotate-toggle');
    
    if (modelViewer) {
        modelViewer.addEventListener('load', () => {
            loadingOverlay.style.display = 'none';
            console.log('3D Model loaded successfully');
        });

        modelViewer.addEventListener('error', (error) => {
            loadingOverlay.innerHTML = `
                <div class="text-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p class="font-medium">Gagal memuat model 3D</p>
                    <p class="text-sm mt-2">Periksa koneksi internet Anda</p>
                </div>
            `;
            console.error('Model loading error:', error);
        });

        document.getElementById('view-top')?.addEventListener('click', () => {
            modelViewer.cameraOrbit = '0deg 0deg 8m';
        });

        document.getElementById('view-side')?.addEventListener('click', () => {
            modelViewer.cameraOrbit = '60deg 90deg 3m';
        });

        document.getElementById('view-iso')?.addEventListener('click', () => {
            modelViewer.cameraOrbit = '45deg 75deg 5m';
            modelViewer.fieldOfView = '30deg';
        });

        autoRotateToggle?.addEventListener('change', (e) => {
            if (e.target.checked) {
                modelViewer.setAttribute('auto-rotate', '');
            } else {
                modelViewer.removeAttribute('auto-rotate');
            }
        });

        document.getElementById('zoom-in')?.addEventListener('click', () => {
            const currentFOV = parseFloat(modelViewer.fieldOfView);
            const newFOV = Math.max(currentFOV - 5, 10);
            modelViewer.fieldOfView = newFOV + 'deg';
        });

        document.getElementById('zoom-out')?.addEventListener('click', () => {
            const currentFOV = parseFloat(modelViewer.fieldOfView);
            const newFOV = Math.min(currentFOV + 5, 90);
            modelViewer.fieldOfView = newFOV + 'deg';
        });

        let isFullscreen = false;
        document.getElementById('fullscreen-toggle')?.addEventListener('click', () => {
            const container = modelViewer.parentElement;
            const button = document.getElementById('fullscreen-toggle');
            
            if (!isFullscreen) {
                container.classList.add('fullscreen-active');
                modelViewer.style.height = '100vh';
                button.innerHTML = '<i class="fas fa-compress text-sm"></i>';
                isFullscreen = true;
            } else {
                container.classList.remove('fullscreen-active');
                modelViewer.style.height = '400px';
                button.innerHTML = '<i class="fas fa-expand text-sm"></i>';
                isFullscreen = false;
            }
        });

        document.addEventListener('keydown', (e) => {
            if (!modelViewer.matches(':focus-within')) return;
            
            switch(e.key) {
                case 'r':
                case 'R':
                    document.getElementById('reset-view')?.click();
                    break;
                case ' ':
                    e.preventDefault();
                    autoRotateToggle.checked = !autoRotateToggle.checked;
                    autoRotateToggle.dispatchEvent(new Event('change'));
                    break;
                case '+':
                case '=':
                    document.getElementById('zoom-in')?.click();
                    break;
                case '-':
                    document.getElementById('zoom-out')?.click();
                    break;
                case 'f':
                case 'F':
                    document.getElementById('fullscreen-toggle')?.click();
                    break;
            }
        });

        let touchStartY = 0;
        modelViewer.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                touchStartY = e.touches[0].clientY;
            }
        });

        modelViewer.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2) {
                const touchCurrentY = e.touches[0].clientY;
                const deltaY = touchStartY - touchCurrentY;
                
                if (Math.abs(deltaY) > 10) {
                    const currentFOV = parseFloat(modelViewer.fieldOfView);
                    const newFOV = deltaY > 0 ? 
                        Math.max(currentFOV - 2, 10) : 
                        Math.min(currentFOV + 2, 90);
                    modelViewer.fieldOfView = newFOV + 'deg';
                    touchStartY = touchCurrentY;
                }
            }
        });
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        notification.className += ` ${colors[type] || colors.info}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isFullscreen) {
            document.getElementById('fullscreen-toggle')?.click();
        }
    });

    console.log('Enhanced 3D Map controls initialized');
});
</script>

<script>
function openPeta2DModal() {
    const modal = document.getElementById('peta-2d-modal');
    const petaImage = document.getElementById('peta-image');
    const loading = document.getElementById('peta-loading');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    if (petaImage.complete && petaImage.naturalHeight !== 0) {
        loading.classList.add('hidden');
    } else {
        loading.classList.remove('hidden');
        
        petaImage.onload = function() {
            loading.classList.add('hidden');
        };
        
        petaImage.onerror = function() {
            loading.classList.add('hidden');
            console.error('Failed to load map image');
        };
        
        setTimeout(() => {
            loading.classList.add('hidden');
        }, 3000);
    }
    
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function closePeta2DModal() {
    const modal = document.getElementById('peta-2d-modal');
    
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 300);
}

function downloadPeta2D() {
    const link = document.createElement('a');
    link.href = "{{ asset('images/IMG_3726.jpg') }}";
    link.download = 'Peta-2D-Goa-Sentono.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('peta-2d-modal');
    const modalClose = document.getElementById('peta-modal-close');
    const modalBackdrop = document.getElementById('peta-modal-backdrop');
    
    if (!modal || !modalClose || !modalBackdrop) {
        console.error('Modal elements not found');
        return;
    }
    
    modalClose.addEventListener('click', closePeta2DModal);
    
    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) {
            closePeta2DModal();
        }
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closePeta2DModal();
        }
    });
    
    modal.style.transition = 'opacity 0.3s ease-in-out';
    modal.style.opacity = '0';
    
    console.log('Peta 2D modal initialized successfully');
});
</script>