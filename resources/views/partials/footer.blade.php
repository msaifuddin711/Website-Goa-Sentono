{{-- resources\views\partials\footer.blade.php --}}
<footer class="relative bg-cream from-primary via-primary-green to-accent-green text-white overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16 lg:pt-24 pb-8 sm:pb-12">
        {{-- Main footer content --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 lg:gap-12 mb-8 sm:mb-12 lg:mb-16">
            {{-- Brand section --}}
            <div class="sm:col-span-2 lg:col-span-1 text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start space-x-3 mb-4 sm:mb-6">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <img src="{{ asset('images/logofix.png') }}" alt="Logo Goa Sentono" class="h-4 sm:h-6 w-auto">
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold">Goa Sentono</h3>
                </div>
                <p class="text-white text-opacity-90 leading-relaxed mb-4 sm:mb-6 text-sm sm:text-base px-4 sm:px-0">
                    Temukan keajaiban alam dan sejarah yang memukau di Goa Sentono.
                </p>
                {{-- Social media icons --}}
                <div class="flex space-x-3 sm:space-x-4 justify-center sm:justify-start">
                    <a href="https://www.instagram.com/explore.sentono" target="blank" class="w-8 sm:w-10 h-8 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition duration-300 transform hover:scale-110 backdrop-blur-sm">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@explore.sentono" target="blank" class="w-8 sm:w-10 h-8 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition duration-300 transform hover:scale-110 backdrop-blur-sm">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="text-center sm:text-left">
                <h4 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 flex items-center justify-center sm:justify-start">
                    <div class="w-6 sm:w-8 h-6 sm:h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 sm:mr-3 backdrop-blur-sm">
                        <i class="fas fa-circle-info"></i>
                    </div>
                    Menu Utama
                </h4>
                <ul class="space-y-2 sm:space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-white text-opacity-90 hover:text-opacity-100 hover:text-cream transition duration-300 flex items-center justify-center sm:justify-start group text-sm sm:text-base">
                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white bg-opacity-40 rounded-full mr-2 sm:mr-3 group-hover:bg-cream transition duration-300"></span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tentang') }}" class="text-white text-opacity-90 hover:text-opacity-100 hover:text-cream transition duration-300 flex items-center justify-center sm:justify-start group text-sm sm:text-base">
                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white bg-opacity-40 rounded-full mr-2 sm:mr-3 group-hover:bg-cream transition duration-300"></span>
                            Tentang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('galeri') }}" class="text-white text-opacity-90 hover:text-opacity-100 hover:text-cream transition duration-300 flex items-center justify-center sm:justify-start group text-sm sm:text-base">
                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white bg-opacity-40 rounded-full mr-2 sm:mr-3 group-hover:bg-cream transition duration-300"></span>
                            Galeri
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('artikel') }}" class="text-white text-opacity-90 hover:text-opacity-100 hover:text-cream transition duration-300 flex items-center justify-center sm:justify-start group text-sm sm:text-base">
                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white bg-opacity-40 rounded-full mr-2 sm:mr-3 group-hover:bg-cream transition duration-300"></span>
                            Artikel
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reservasi') }}" class="text-white text-opacity-90 hover:text-opacity-100 hover:text-cream transition duration-300 flex items-center justify-center sm:justify-start group text-sm sm:text-base">
                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white bg-opacity-40 rounded-full mr-2 sm:mr-3 group-hover:bg-cream transition duration-300"></span>
                            Reservasi
                        </a>
                    </li>
                    @if(false)
                    <li>
                        <a href="{{ route('kontak') }}" class="text-white text-opacity-90 hover:text-opacity-100 hover:text-cream transition duration-300 flex items-center justify-center sm:justify-start group text-sm sm:text-base">
                            <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 bg-white bg-opacity-40 rounded-full mr-2 sm:mr-3 group-hover:bg-cream transition duration-300"></span>
                            Kontak
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- Contact Info --}}
            <div class="text-center sm:text-left">
                <h4 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 flex items-center justify-center sm:justify-start">
                    <div class="w-6 sm:w-8 h-6 sm:h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 sm:mr-3 backdrop-blur-sm">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    Kontak Kami
                </h4>
                <ul class="space-y-3 sm:space-y-4">
                    <li class="flex items-start justify-center sm:justify-start">
                        <div class="w-5 sm:w-6 h-5 sm:h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2 sm:mr-3 mt-0.5 flex-shrink-0 backdrop-blur-sm">
                            <i class="fas fa-location-pin fa-xs"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <span class="text-white text-opacity-90 text-xs sm:text-sm block">Alamat:</span>
                            <p class="text-white font-medium text-sm sm:text-base leading-relaxed">
                                Goa Sentono<br>
                                Nglaren, Mendenrejo, Kradenan,<br>
                                Blora, Jawa Tengah 58383<br>
                                Indonesia
                            </p>
                        </div>
                    </li>
                    <li class="flex items-center justify-center sm:justify-start">
                        <div class="w-5 sm:w-6 h-5 sm:h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0 backdrop-blur-sm">
                            <i class="fas fa-envelope fa-xs"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <span class="text-white text-opacity-90 text-xs sm:text-sm block">Email:</span>
                            <p class="text-white font-medium text-sm sm:text-base">exploresentono2k25@gmail.com</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom section --}}
        <div class="border-t border-white border-opacity-20 pt-6 sm:pt-8">
            <p class="text-center text-white text-opacity-90 text-sm sm:text-base">
                © 2025 Goa Sentono. All rights reserved.
            </p>
        </div>
    </div>
</footer>