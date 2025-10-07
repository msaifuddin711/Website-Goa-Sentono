{{-- resources\views\layouts\app.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Basic SEO Meta Tags --}}
    <title>@yield('title', 'Goa Sentono')</title>
    <meta name="description" content="@yield('description', 'Jelajahi keindahan Goa Sentono, destinasi wisata alam dan situs bersejarah yang menakjubkan di Kradenan, Blora, Jawa Tengah. Nikmati pengalaman wisata yang tak terlupakan dengan fasilitas lengkap dan pemandangan yang memukau.')">
    <meta name="keywords" content="@yield('keywords', 'Goa Sentono, goa sentono, sentono, goa, goa mendenrejo, goa blora, wisata Blora, wisata Jawa Tengah, gua alam, situs bersejarah, wisata alam Indonesia, destinasi wisata Kradenan, tempat wisata Blora, explore sentono, wisata edukatif, wisata keluarga')">
    <meta name="author" content="KKN UNS Kelompok 18 Mendenrejo dan Badan Usaha Milik Desa Mendenrejo">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="language" content="Indonesian">
    <meta name="geo.region" content="ID-JI">
    <meta name="geo.placename" content="Blora, Jawa Tengah">
    <meta name="geo.position" content="-7.123456;111.654321">
    <meta name="ICBM" content="-7.123456, 111.654321">

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', request()->url())">

    {{-- Open Graph Meta Tags (Facebook) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Goa Sentono - Wisata Alam dan Situs Bersejarah di Blora')">
    <meta property="og:description" content="@yield('og_description', 'Jelajahi keindahan Goa Sentono, destinasi wisata alam dan situs bersejarah yang menakjubkan di Kradenan, Blora, Jawa Tengah.')">
    <meta property="og:image" content="@yield('og_image', asset('images/goa-sentono.JPEG'))">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Goa Sentono - Wisata Alam Blora')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="@yield('og_url', request()->url())">
    <meta property="og:site_name" content="Goa Sentono">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Goa Sentono - Wisata Alam dan Situs Bersejarah di Blora')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Jelajahi keindahan Goa Sentono, destinasi wisata alam dan situs bersejarah yang menakjubkan di Kradenan, Blora, Jawa Tengah.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/goa-sentono.JPEG'))">
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt', 'Goa Sentono - Wisata Alam Blora')">
    <meta name="twitter:site" content="@explore.sentono">
    <meta name="twitter:creator" content="@explore.sentono">

    {{-- Article Meta Tags (for article pages) --}}
    @hasSection('article_published_time')
    <meta property="article:published_time" content="@yield('article_published_time')">
    @endif
    @hasSection('article_modified_time')
    <meta property="article:modified_time" content="@yield('article_modified_time')">
    @endif
    @hasSection('article_author')
    <meta property="article:author" content="@yield('article_author')">
    @endif
    @hasSection('article_section')
    <meta property="article:section" content="@yield('article_section')">
    @endif
    @hasSection('article_tag')
    <meta property="article:tag" content="@yield('article_tag')">
    @endif

    {{-- Business/Location Meta Tags --}}
    <meta property="business:contact_data:street_address" content="Nglaren, Mendenrejo, Kradenan">
    <meta property="business:contact_data:locality" content="Blora">
    <meta property="business:contact_data:region" content="Jawa Tengah">
    <meta property="business:contact_data:postal_code" content="58383">
    <meta property="business:contact_data:country_name" content="Indonesia">
    <meta property="business:contact_data:email" content="exploresentono2k25@gmail.com">

    {{-- Favicon and App Icons --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" href="{{ asset('images/logofixfix-color-bg.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('images/logofixfix-color-bg.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('images/logofixfix-color-bg.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('images/logofixfix-color-bg.png') }}">
    <meta name="theme-color" content="#8B4513">
    <meta name="msapplication-TileColor" content="#8B4513">

    {{-- Preload critical resources --}}
    <link rel="preload" href="{{ asset('styles/style.css') }}" as="style">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- DNS Prefetch --}}
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//unpkg.com">

    {{-- External Scripts and Styles --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tiny.cloud/1/ayawtiwyb62qnm0he2qcgukk953gqbugwhqbubxutll7agpp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Additional meta for better indexing --}}
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="revisit-after" content="7 days">
    <meta name="expires" content="never">
    
    <meta name="google-site-verification" content="jBCgou98s1aYhmmJMRjXQ2jcMF3wj1XSTp1sh7P72II" />
</head>
<body class="bg-gray-50">

    <div id="loading-screen" class="fixed inset-0 flex flex-col items-center justify-center bg-white z-[1000]">
        <!-- Logo kecil -->
        <img src="{{ asset('images/logofixfix-color.png') }}" alt="Logo" class="mb-2 h-24 w-24 animate-bounce-slow">

        <!-- Gelombang titik -->
        <div class="flex space-x-2">
            <div class="w-4 h-4 bg-primary-orange rounded-full animate-ping-dot"></div>
            <div class="w-4 h-4 bg-primary rounded-full animate-ping-dot animation-delay-200"></div>
            <div class="w-4 h-4 bg-primary-orange rounded-full animate-ping-dot animation-delay-400"></div>
        </div>
    </div>


    {{-- Navbar include --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer include --}}
    @include('partials.footer')
    
    @stack('scripts')
    <script src="{{ asset('script/script.js') }}"></script>

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