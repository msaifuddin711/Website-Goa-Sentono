{{-- resources\views\partials\navbar.blade.php --}}
@php
    $current = Route::currentRouteName();
    $isSolidHeader = in_array($current, ['artikel.show', 'search']); 
@endphp

<header class="header {{ $isSolidHeader ? 'header-solid' : '' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- LOGO -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img id="logo-img" src="{{ asset('images/logofix.png') }}" alt="Logo Goa Sentono" class="h-8 w-auto transition-all duration-300">
                    <span class="logo text-white text-xl font-bold tracking-wide">Goa Sentono</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                class="nav-link text-white {{ $current === 'home' ? 'active' : '' }}">
                    BERANDA
                </a>
                <a href="{{ route('tentang') }}"
                class="nav-link text-white {{ $current === 'tentang' ? 'active' : '' }}">
                    TENTANG
                </a>
                <a href="{{ route('galeri') }}"
                class="nav-link text-white {{ $current === 'galeri' ? 'active' : '' }}">
                    GALERI
                </a>
                <a href="{{ route('artikel') }}"
                class="nav-link text-white {{ $current === 'artikel' ? 'active' : '' }}">
                    ARTIKEL
                </a>
                <a href="{{ route('kontak') }}"
                class="nav-link text-white {{ $current === 'kontak' ? 'active' : '' }}">
                    KONTAK
                </a>
                
                <!-- Desktop Search -->
                <div class="search-container">
                    <div class="relative">
                        <input type="text" placeholder="CARI..." class="search-input bg-transparent border border-white/30 text-white placeholder-white/80 px-4 py-2 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/10 w-32 focus:w-48 transition-all duration-300">
                        <svg class="absolute right-3 top-2.5 w-4 h-4 text-white/80 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </nav>

            <!-- Mobile menu button -->
            <div class="hamburger md:hidden" id="mobile-menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-menu" id="mobile-menu">
            <div class="mobile-menu-content">
                <!-- Mobile Search -->
                <div class="mobile-search-container px-4">
                    <div class="relative">
                        <input type="text" placeholder="CARI..." class="mobile-search-input w-full bg-white/10 border border-white/30 text-white placeholder-white/80 px-4 py-3 mb-4 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20">
                        <svg class="absolute right-4 top-3.5 w-4 h-4 text-white/80 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            
                <a href="{{ route('home') }}" style="border-top: 1px solid rgba(76, 61, 25, 0.1);"
                class="mobile-nav-link {{ $current === 'home' ? 'active' : '' }}">
                    BERANDA
                </a>
                <a href="{{ route('tentang') }}"
                class="mobile-nav-link {{ $current === 'tentang' ? 'active' : '' }}">
                    TENTANG
                </a>
                <a href="{{ route('galeri') }}"
                class="mobile-nav-link {{ $current === 'galeri' ? 'active' : '' }}">
                    GALERI
                </a>
                <a href="{{ route('artikel') }}"
                class="mobile-nav-link {{ $current === 'artikel' ? 'active' : '' }}">
                    ARTIKEL
                </a>
                <a href="{{ route('kontak') }}"
                class="mobile-nav-link {{ $current === 'kontak' ? 'active' : '' }}">
                    KONTAK
                </a>
                
            </div>
        </div>
    </div>
</header>