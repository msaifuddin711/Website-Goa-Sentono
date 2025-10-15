{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Panel Admin') - Goa Sentono</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>

    <link rel="stylesheet" href="/styles/style.css">

    <link rel="icon" href="{{ asset('images/logofixfix-color-bg.png') }}" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        /* Admin specific styles menggunakan CSS variables dari style.css */
        body.admin-body {
            background-color: #f0e8d9 !important;
            background: #f0e8d9 !important;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            background-color: var(--primary-green) !important;
            background: var(--primary-green) !important;
        }
        
        .sidebar-active {
            background-color: var(--primary-dark) !important;
            background: var(--primary-dark) !important;
        }
        
        /* Header Styles */
        .admin-header {
            background-color: var(--cream) !important;
            background: var(--cream) !important;
            border-bottom: 1px solid var(--light-beige) !important;
        }
        
        /* Card hover effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(76, 61, 25, 0.15);
        }
        
        /* Button styles */
        .btn-primary-admin {
            background-color: var(--primary-green) !important;
            background: var(--primary-green) !important;
            color: var(--cream) !important;
        }
        
        .btn-primary-admin:hover {
            background-color: var(--primary-dark) !important;
            background: var(--primary-dark) !important;
        }
        
        /* Text colors using CSS variables */
        .text-cream-admin { color: var(--cream) !important; }
        .text-light-beige-admin { color: var(--light-beige) !important; }
        .text-primary-dark-admin { color: var(--primary-dark) !important; }
        .text-primary-green-admin { color: var(--primary-green) !important; }
        .text-accent-green-admin { color: var(--accent-green) !important; }
        
        /* Background colors using CSS variables */
        .bg-cream-admin { 
            background-color: var(--cream) !important; 
            background: var(--cream) !important; 
        }
        .bg-soft-cream-admin { 
            background-color: #f0e8d9 !important; 
            background: #f0e8d9 !important; 
        }
        .bg-primary-green-admin { 
            background-color: var(--primary-green) !important; 
            background: var(--primary-green) !important; 
        }
        .bg-light-beige-admin { 
            background-color: var(--light-beige) !important; 
            background: var(--light-beige) !important; 
        }
        
        /* Border colors */
        .border-accent-green-admin { border-color: var(--accent-green) !important; }
        .border-light-beige-admin { border-color: var(--light-beige) !important; }
        
        /* Custom scrollbar menggunakan earthy colors */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--cream) !important;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--accent-green) !important;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-green) !important;
        }

        /* Override any conflicting Tailwind classes */
        .bg-gray-50.admin-override {
            background-color: #f0e8d9 !important;
        }

        .bg-white.admin-override {
            background-color: var(--cream) !important;
        }

        /* Navigation hover effects */
        .nav-item:hover {
            background-color: rgba(136, 144, 99, 0.3) !important;
        }

        .nav-item.active {
            background-color: var(--primary-dark) !important;
        }

        /* Icon color untuk active state menggunakan CSS */
        .nav-item.active i {
            color: var(--primary-dark) !important;
        }

        /* Icon default color dan transitions */
        .nav-item i {
            transition: color 0.2s ease-in-out;
        }

        /* Glass effect dengan earthy colors */
        .glass-effect-admin {
            background: rgba(229, 215, 196, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(207, 187, 153, 0.2);
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-dark': '#4c3d19',
                        'primary-green': '#354024',
                        'accent-green': '#889063',
                        'light-beige': '#cfbb99',
                        'cream': '#e5d7c4',
                        'dark-brown': '#3a2f17',
                        'medium-brown': '#5c4a28',
                        'sage-green': '#7a8b5a',
                        'light-sage': '#9aab7a',
                        'warm-beige': '#d4c7ae',
                        'soft-cream': '#f0e8d9'
                    }
                }
            }
        }
    </script>
</head>
<body class="admin-body">

    <div id="loading-screen" class="fixed inset-0 flex flex-col items-center justify-center bg-white z-[1000]">
        <!-- Logo kecil -->
        <img src="{{ asset('images/logofixfix-color.png') }}" alt="Logo" class="mb-2 h-24 w-24 animate-bounce-slow" loading="lazy">

        <!-- Gelombang titik -->
        <div class="flex space-x-2">
            <div class="w-4 h-4 bg-primary-orange rounded-full animate-ping-dot"></div>
            <div class="w-4 h-4 bg-primary rounded-full animate-ping-dot animation-delay-200"></div>
            <div class="w-4 h-4 bg-primary-orange rounded-full animate-ping-dot animation-delay-400"></div>
        </div>
    </div>

    <!-- Mobile menu overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
    
    <!-- Sidebar -->
    <div id="sidebar" class="fixed left-0 top-0 h-full w-64 admin-sidebar shadow-xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <!-- Logo Section -->
        <div class="flex items-center justify-between p-6" style="border-bottom: 1px solid rgba(136, 144, 99, 0.3);">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-cream-admin rounded-xl flex items-center justify-center p-1">
                    <img src="{{ asset('images/logofixfix-color.png') }}" alt="Goa Sentono Logo" class="w-6 h-auto object-contain" loading="lazy">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-cream-admin">Goa Sentono</h1>
                    <p class="text-sm text-light-beige-admin">Admin</p>
                </div>
            </div>
            <button id="close-sidebar" class="lg:hidden p-2 rounded-lg hover:bg-accent-green hover:bg-opacity-30">
                <i class="fas fa-times text-cream-admin"></i>
            </button>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="p-4">
            <div class="space-y-2">
                <!-- Tentang Section -->
                <a href="{{ route('admin.tentang.index') }}" 
                    class="nav-item flex items-center space-x-3 p-3 rounded-xl transition-colors duration-200 group {{ request()->is('admin/tentang*') ? 'active' : '' }}">
                    <div class="w-10 h-10 {{ request()->is('admin/tentang*') ? 'bg-cream-admin bg-opacity-20' : 'bg-light-beige-admin bg-opacity-20' }} rounded-lg flex items-center justify-center group-hover:bg-cream-admin group-hover:bg-opacity-30 transition-all duration-200">
                        <i class="fas fa-info-circle text-primary-dark-admin group-hover:text-cream-admin"></i>
                    </div>
                    <span class="font-medium {{ request()->is('admin/tentang*') ? 'text-cream-admin' : 'text-light-beige-admin group-hover:text-cream-admin' }}">Kelola Tentang</span>
                </a>
                
                <!-- Galeri -->
                <a href="{{ route('admin.galeri.index') }}" 
                    class="nav-item flex items-center space-x-3 p-3 rounded-xl transition-colors duration-200 group {{ request()->is('admin/galeri*') ? 'active' : '' }}">
                    <div class="w-10 h-10 {{ request()->is('admin/galeri*') ? 'bg-cream-admin bg-opacity-20' : 'bg-light-beige-admin bg-opacity-20' }} rounded-lg flex items-center justify-center group-hover:bg-cream-admin group-hover:bg-opacity-30 transition-all duration-200">
                        <i class="fas fa-images text-primary-dark-admin group-hover:text-cream-admin"></i>
                    </div>
                    <span class="font-medium {{ request()->is('admin/galeri*') ? 'text-cream-admin' : 'text-light-beige-admin group-hover:text-cream-admin' }}">Galeri</span>
                </a>
                
                <!-- Artikel -->
                <a href="{{ route('admin.artikel.index') }}" 
                    class="nav-item flex items-center space-x-3 p-3 rounded-xl transition-colors duration-200 group {{ request()->is('admin/artikel*') ? 'active' : '' }}">
                    <div class="w-10 h-10 {{ request()->is('admin/artikel*') ? 'bg-cream-admin bg-opacity-20' : 'bg-light-beige-admin bg-opacity-20' }} rounded-lg flex items-center justify-center group-hover:bg-cream-admin group-hover:bg-opacity-30 transition-all duration-200">
                        <i class="fas fa-newspaper text-primary-dark-admin group-hover:text-cream-admin"></i>
                    </div>
                    <span class="font-medium {{ request()->is('admin/artikel*') ? 'text-cream-admin' : 'text-light-beige-admin group-hover:text-cream-admin' }}">Artikel</span>
                </a>
                
                <!-- Kontak -->
                <a href="{{ route('admin.kontak.index') }}" 
                    class="nav-item flex items-center space-x-3 p-3 rounded-xl transition-colors duration-200 group {{ request()->is('admin/kontak*') ? 'active' : '' }}">
                    <div class="w-10 h-10 {{ request()->is('admin/kontak*') ? 'bg-cream-admin bg-opacity-20' : 'bg-light-beige-admin bg-opacity-20' }} rounded-lg flex items-center justify-center group-hover:bg-cream-admin group-hover:bg-opacity-30 transition-all duration-200">
                        <i class="fas fa-envelope text-primary-dark-admin group-hover:text-cream-admin"></i>
                    </div>
                    <span class="font-medium {{ request()->is('admin/kontak*') ? 'text-cream-admin' : 'text-light-beige-admin group-hover:text-cream-admin' }}">Kontak</span>
                </a>
            </div>
        </nav>
        
        <!-- User Profile Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4" style="border-top: 1px solid rgba(136, 144, 99, 0.3);">
            <div class="flex items-center space-x-3">
                <div class="flex-1">
                    <p class="font-medium text-sm text-cream-admin">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-light-beige-admin">{{ Auth::user()->email }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg hover:bg-red-800 hover:bg-opacity-30 hover:text-red-300 text-light-beige-admin transition-colors duration-200" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Top Navigation -->
        <header class="admin-header shadow-sm sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <button id="menu-toggle" class="lg:hidden p-2 rounded-lg hover:bg-light-beige-admin hover:bg-opacity-50">
                        <i class="fas fa-bars text-primary-dark-admin"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-primary-dark-admin">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-primary-dark-admin">@yield('page-subtitle', 'Kelola konten website Goa Sentono')</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="{{ route('tentang') }}" target="_blank" 
                           class="btn-primary-admin inline-flex items-center px-4 py-2 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat Website
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-6">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-soft-cream-admin border border-accent-green-admin rounded-xl p-4 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cream-admin rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-primary-green-admin"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-primary-dark-admin">Berhasil!</h4>
                        <p class="text-primary-green-admin">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-auto p-1 hover:bg-light-beige-admin hover:bg-opacity-50 rounded">
                        <i class="fas fa-times text-accent-green-admin"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-300 rounded-xl p-4 flex items-start space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-red-800 mb-2">Terjadi Kesalahan!</h4>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="p-1 hover:bg-red-100 rounded">
                        <i class="fas fa-times text-red-600"></i>
                    </button>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        const closeSidebar = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        menuToggle?.addEventListener('click', toggleSidebar);
        closeSidebar?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('[class*="bg-soft-cream-admin"], [class*="bg-red-50"]').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')

    <script>
        // Tunggu semua aset (video, gambar, dll.) selesai load
        window.addEventListener('load', function() {
            const loader = document.getElementById('loading-screen');
            if (!loader) return;
            // Fade-out
            loader.style.transition = 'opacity 0.5s ease';
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.remove();
            }, 500);
        });
    </script>

</body>
</html>