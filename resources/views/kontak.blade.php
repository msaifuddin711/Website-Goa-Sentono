{{-- resources\views\kontak.blade.php --}}

@extends('layouts.app')

@section('title', 'Kontak - Goa Sentono')

@section('content')
    <!-- Hero Section untuk Kontak -->
    <section class="hero-video text-white py-16 sm:py-24 lg:py-32 relative min-h-[60vh] sm:min-h-screen flex items-center bg-cover bg-center" style="background-image: url('{{ asset('images/IMG_3959.jpg') }}');">
        <div class="video-fallback opacity-0"></div>
        <!-- Container identik dengan Home -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full flex flex-col justify-center space-y-2 hero-content animate-fade-in">
            <span class="text-base sm:text-xl md:text-2xl font-medium">
                PERTANYAAN DAN MASUKAN
            </span>
            <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold leading-tight">
                HUBUNGI KAMI
            </h1>
        </div>
    </section>
    
<!-- Informasi Kontak & Form -->
    <section id="form-kontak" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16">
                
                <!-- Form Kontak - PINDAH KE ATAS UNTUK MOBILE -->
                <div class="order-1 lg:order-2">
                    <div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-4 sm:mb-6 lg:mb-8">Informasi Kontak</h2>
                        <p class="text-sm sm:text-base lg:text-lg text-accent leading-relaxed mb-6 sm:mb-8">
                            Tim kami siap membantu Anda dengan segala pertanyaan mengenai kunjungan ke Goa Sentono. Jangan ragu untuk menghubungi kami!
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-6 lg:p-8 xl:p-12 border border-gray-100">
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-primary mb-4 sm:mb-6 lg:mb-8">Kirim Pesan</h3>

                        {{-- Notifikasi Sukses atau Error --}}
                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg" role="alert">
                                <p class="font-bold text-sm sm:text-base">Berhasil!</p>
                                <p class="text-sm sm:text-base">{{ session('success') }}</p>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg" role="alert">
                                <p class="font-bold text-sm sm:text-base">Oops!</p>
                                <p class="text-sm sm:text-base">{{ session('error') }}</p>
                            </div>
                        @endif
                        
                        <form action="{{ route('kontak.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                            @csrf
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-xs sm:text-sm font-semibold text-primary mb-1 sm:mb-2">Nama *</label>
                                <input 
                                    type="text" 
                                    id="nama" 
                                    name="nama" 
                                    value="{{ old('nama') }}"
                                    required
                                    class="w-full px-3 sm:px-4 lg:px-6 py-2 sm:py-3 lg:py-4 border-2 @error('nama') border-red-500 @enderror rounded-xl sm:rounded-2xl focus:border-primary focus:outline-none transition duration-300 bg-gray-50 focus:bg-white text-sm sm:text-base"
                                    placeholder="Masukkan nama Anda"
                                >
                                @error('nama') <span class="text-red-500 text-xs sm:text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs sm:text-sm font-semibold text-primary mb-1 sm:mb-2">Email *</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full px-3 sm:px-4 lg:px-6 py-2 sm:py-3 lg:py-4 border-2 @error('email') border-red-500 @enderror rounded-xl sm:rounded-2xl focus:border-primary focus:outline-none transition duration-300 bg-gray-50 focus:bg-white text-sm sm:text-base"
                                    placeholder="nama@email.com"
                                >
                                @error('email') <span class="text-red-500 text-xs sm:text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Telepon -->
                            <div>
                                <label for="telepon" class="block text-xs sm:text-sm font-semibold text-primary mb-1 sm:mb-2">Nomor Telepon *</label>
                                <input 
                                    type="tel" 
                                    id="telepon" 
                                    name="telepon"
                                    value="{{ old('telepon') }}"
                                    required
                                    class="w-full px-3 sm:px-4 lg:px-6 py-2 sm:py-3 lg:py-4 border-2 @error('telepon') border-red-500 @enderror rounded-xl sm:rounded-2xl focus:border-primary focus:outline-none transition duration-300 bg-gray-50 focus:bg-white text-sm sm:text-base"
                                    placeholder="081234567890"
                                >
                                @error('telepon') <span class="text-red-500 text-xs sm:text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Pesan -->
                            <div>
                                <label for="pesan" class="block text-xs sm:text-sm font-semibold text-primary mb-1 sm:mb-2">Pesan *</label>
                                <textarea 
                                    id="pesan" 
                                    name="pesan" 
                                    required
                                    rows="4"
                                    class="w-full px-3 sm:px-4 lg:px-6 py-2 sm:py-3 lg:py-4 border-2 @error('pesan') border-red-500 @enderror rounded-xl sm:rounded-2xl focus:border-primary focus:outline-none transition duration-300 bg-gray-50 focus:bg-white resize-none text-sm sm:text-base"
                                    placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."
                                >{{ old('pesan') }}</textarea>
                                @error('pesan') <span class="text-red-500 text-xs sm:text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit"
                                class="w-full bg-primary text-white py-3 sm:py-4 px-6 sm:px-8 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base lg:text-lg hover:bg-opacity-90 transition duration-300 transform hover:scale-105 shadow-lg"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Pesan
                            </button>

                            <!-- Info tambahan -->
                            <p class="text-xs sm:text-sm text-gray-500 text-left mt-3 sm:mt-4">
                                * Wajib diisi
                            </p>
                        </form>
                    </div>
                </div>
                
                <!-- Informasi Kontak - PINDAH KE BAWAH UNTUK MOBILE -->
                <div class="order-2 lg:order-1 space-y-6 sm:space-y-8">

                    <!-- Cards Informasi Kontak -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Alamat -->
                        <a href="https://maps.app.goo.gl/wWYezxfXh7bWJsLH7" 
                        target="_blank" 
                        class="block bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 card-hover transition transform hover:scale-[1.02]">
                            <div class="flex items-start space-x-3 sm:space-x-4">
                                <div class="bg-primary text-white p-2 sm:p-3 lg:p-4 rounded-full flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-lg sm:text-xl lg:text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-primary mb-1 sm:mb-2">Alamat Lokasi</h3>
                                    <p class="text-sm sm:text-base text-accent leading-relaxed">
                                        Goa Sentono<br>
                                        Nglaren, Mendenrejo, Kradenan,<br>
                                        Blora, Jawa Tengah 58383<br>
                                        Indonesia
                                    </p>
                                </div>
                            </div>
                        </a>

                        <!-- Email -->
                        <a href="mailto:exploresentono2k25@gmail.com" 
                        class="block bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 card-hover transition transform hover:scale-[1.02]">
                            <div class="flex items-start space-x-3 sm:space-x-4">
                                <div class="bg-green-500 text-white p-2 sm:p-3 lg:p-4 rounded-full flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-envelope text-lg sm:text-xl lg:text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-primary mb-1 sm:mb-2">Email</h3>
                                    <p class="text-sm sm:text-base text-accent leading-relaxed">exploresentono2k25@gmail.com</p>
                                </div>
                            </div>
                        </a>

                        <!-- Jam Operasional -->
                        <div class="bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 card-hover">
                            <div class="flex items-start space-x-3 sm:space-x-4">
                                <div class="bg-blue-500 text-white p-2 sm:p-3 lg:p-4 rounded-full flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-clock text-lg sm:text-xl lg:text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-primary mb-1 sm:mb-2">Jam Operasional</h3>
                                    <div class="text-sm sm:text-base text-accent leading-relaxed space-y-1">
                                        <p><strong>Senin - Minggu:</strong> 07:00 - 17:00 WIB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media Sosial -->
                        <div class="bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 card-hover">
                            <div class="flex items-start space-x-3 sm:space-x-4">
                                <div class="bg-purple-500 text-white p-2 sm:p-3 lg:p-4 rounded-full flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-share-alt text-lg sm:text-xl lg:text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-primary mb-1 sm:mb-2">Media Sosial</h3>
                                    <div class="text-sm sm:text-base text-accent leading-relaxed space-y-2">
                                        <a href="https://instagram.com/explore.sentono" 
                                        target="_blank" 
                                        class="flex items-center space-x-2 hover:text-pink-600 transition duration-300 group">
                                            <i class="fab fa-instagram text-pink-500 group-hover:text-pink-600 text-lg"></i>
                                            <span class="no-underline">@explore.sentono</span>
                                        </a>
                                        
                                        <a href="https://tiktok.com/@explore.sentono" 
                                        target="_blank" 
                                        class="flex items-center space-x-2 hover:text-black transition duration-300 group">
                                            <i class="fab fa-tiktok text-black group-hover:text-gray-800 text-lg"></i>
                                            <span class="no-underline">@explore.sentono</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section with Font Awesome icons -->
    <section class="py-12 sm:py-16 lg:py-20 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-10 lg:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 sm:mb-4 lg:mb-6">Pertanyaan Umum</h2>
                <p class="text-base sm:text-lg lg:text-xl text-accent">
                    Jawaban untuk pertanyaan yang sering ditanyakan pengunjung
                </p>
            </div>

            <div class="space-y-3 sm:space-y-4">
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden card-hover">
                    <button class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 text-left flex items-center justify-between focus:outline-none group" 
                            onclick="toggleFAQ('faq1')">
                        <h3 class="text-sm sm:text-base lg:text-xl font-bold text-primary group-hover:text-opacity-80 transition duration-300 pr-2 flex items-center gap-2">
                            <i class="fa-solid fa-ticket text-primary"></i>
                            Berapa harga tiket masuk Goa Sentono?
                        </h3>
                        <div class="text-primary text-xl sm:text-2xl transition-transform duration-300 flex-shrink-0" id="faq1-icon">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="hidden px-4 sm:px-6 lg:px-8 pb-4 sm:pb-5 lg:pb-6" id="faq1-content">
                        <p class="text-sm sm:text-base text-accent leading-relaxed">
                            Harga tiket parkir motor Rp3000; parkir mobil Rp5000.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden card-hover">
                    <button class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 text-left flex items-center justify-between focus:outline-none group" 
                            onclick="toggleFAQ('faq2')">
                        <h3 class="text-sm sm:text-base lg:text-xl font-bold text-primary group-hover:text-opacity-80 transition duration-300 pr-2 flex items-center gap-2">
                            <i class="fa-regular fa-clock text-primary"></i>
                            Berapa lama durasi eksplorasi gua?
                        </h3>
                        <div class="text-primary text-xl sm:text-2xl transition-transform duration-300 flex-shrink-0" id="faq2-icon">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="hidden px-4 sm:px-6 lg:px-8 pb-4 sm:pb-5 lg:pb-6" id="faq2-content">
                        <p class="text-sm sm:text-base text-accent leading-relaxed">
                            Durasi standar adalah 1 jam.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden card-hover">
                    <button class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 text-left flex items-center justify-between focus:outline-none group" 
                            onclick="toggleFAQ('faq3')">
                        <h3 class="text-sm sm:text-base lg:text-xl font-bold text-primary group-hover:text-opacity-80 transition duration-300 pr-2 flex items-center gap-2">
                            <i class="fa-solid fa-shirt text-primary"></i>
                            Apa yang perlu dibawa saat berkunjung?
                        </h3>
                        <div class="text-primary text-xl sm:text-2xl transition-transform duration-300 flex-shrink-0" id="faq3-icon">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="hidden px-4 sm:px-6 lg:px-8 pb-4 sm:pb-5 lg:pb-6" id="faq3-content">
                        <p class="text-sm sm:text-base text-accent leading-relaxed">
                            Pakai alas kaki yang nyaman, topi, dan kamera.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden card-hover">
                    <button class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 text-left flex items-center justify-between focus:outline-none group" 
                            onclick="toggleFAQ('faq4')">
                        <h3 class="text-sm sm:text-base lg:text-xl font-bold text-primary group-hover:text-opacity-80 transition duration-300 pr-2 flex items-center gap-2">
                            <i class="fa-solid fa-ban text-primary"></i>
                            Apakah ada batasan usia untuk masuk gua?
                        </h3>
                        <div class="text-primary text-xl sm:text-2xl transition-transform duration-300 flex-shrink-0" id="faq4-icon">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="hidden px-4 sm:px-6 lg:px-8 pb-4 sm:pb-5 lg:pb-6" id="faq4-content">
                        <p class="text-sm sm:text-base text-accent leading-relaxed">
                            Tidak ada, semua usia boleh masuk, tapi anak di bawah 5 tahun harus didampingi orang dewasa.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden card-hover">
                    <button class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 text-left flex items-center justify-between focus:outline-none group" 
                            onclick="toggleFAQ('faq5')">
                        <h3 class="text-sm sm:text-base lg:text-xl font-bold text-primary group-hover:text-opacity-80 transition duration-300 pr-2 flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check text-primary"></i>
                            Apakah perlu reservasi sebelumnya?
                        </h3>
                        <div class="text-primary text-xl sm:text-2xl transition-transform duration-300 flex-shrink-0" id="faq5-icon">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div class="hidden px-4 sm:px-6 lg:px-8 pb-4 sm:pb-5 lg:pb-6" id="faq5-content">
                        <p class="text-sm sm:text-base text-accent leading-relaxed">
                            Tidak wajib untuk kunjungan individual, tapi sangat direkomendasikan untuk weekend dan hari libur. Wajib reservasi untuk grup 20+ orang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Anggota KKN Section -->
    @if(false)
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary mb-3 sm:mb-4 lg:mb-6">Website Ini Dikembangkan Oleh</h2>
                <p class="text-sm sm:text-base lg:text-xl text-accent max-w-3xl mx-auto">
                    Kuliah Kerja Nyata (KKN) Universitas Sebelas Maret (UNS) Kelompok 18 <br class="hidden sm:block"> 
                    Mendenrejo, Kradenan, Blora. Periode Juli - Agustus 2025.
                </p>
            </div>

            <!-- Dosen Pembimbing Lapangan -->
            @if($dpl)
            <div class="flex justify-center mb-8 sm:mb-10 lg:mb-12">
                <div class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
                    <div class="bg-cream rounded-2xl sm:rounded-3xl p-4 sm:p-5 lg:p-6 text-center card-hover shadow-xl transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ $dpl->photo_url }}" alt="Foto {{ $dpl->name }}" class="w-24 h-24 sm:w-32 sm:h-32 lg:w-36 lg:h-36 rounded-full mx-auto mb-3 sm:mb-4 border-4 border-primary shadow-md object-cover" loading="lazy">
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-primary">{{ $dpl->name }}</h3>
                        <p class="text-sm sm:text-base lg:text-lg text-accent font-semibold">{{ $dpl->role }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Anggota Tim KKN -->
            @if($members->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($members as $member)
                <div class="bg-cream rounded-2xl sm:rounded-3xl p-3 sm:p-4 lg:p-6 text-center card-hover shadow-lg">
                    <img src="{{ $member->photo_url }}" alt="Foto {{ $member->name }}" class="w-20 h-20 sm:w-24 sm:h-24 lg:w-32 lg:h-32 rounded-full mx-auto mb-2 sm:mb-3 lg:mb-4 border-4 border-primary object-cover" loading="lazy">
                    <h3 class="text-xs sm:text-sm lg:text-lg font-bold text-primary truncate">{{ $member->name }}</h3>
                    <p class="text-xs sm:text-sm text-accent">{{ $member->role }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif
<script>
function toggleFAQ(faqId) {
    const content = document.getElementById(faqId + '-content');
    const icon = document.getElementById(faqId + '-icon');
    
    if (content.classList.contains('hidden')) {
        // Close all other FAQs first
        const allFAQs = document.querySelectorAll('[id$="-content"]');
        const allIcons = document.querySelectorAll('[id$="-icon"]');
        
        allFAQs.forEach(faq => {
            if (faq !== content) {
                faq.classList.add('hidden');
            }
        });
        
        allIcons.forEach(iconEl => {
            if (iconEl !== icon) {
                iconEl.querySelector('svg').style.transform = 'rotate(0deg)';
            }
        });
        
        // Open current FAQ
        content.classList.remove('hidden');
        icon.querySelector('svg').style.transform = 'rotate(180deg)';
    } else {
        // Close current FAQ
        content.classList.add('hidden');
        icon.querySelector('svg').style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection