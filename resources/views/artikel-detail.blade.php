{{-- resources\views\artikel-detail.blade.php --}}

@extends('layouts.app')

@section('title', $artikel->judul . ' - Goa Sentono')
@section('description', $artikel->ringkasan_potong)
@section('keywords', 'Goa Sentono, ' . $artikel->judul . ', wisata Blora, artikel wisata, ' . ($artikel->tags ?? 'wisata alam'))

@section('og_type', 'article')
@section('og_title', $artikel->judul)
@section('og_description', $artikel->ringkasan_potong)
@section('og_image', $artikel->gambar_url)
@section('og_image_alt', $artikel->judul)

@section('twitter_title', $artikel->judul)
@section('twitter_description', $artikel->ringkasan_potong)
@section('twitter_image', $artikel->gambar_url)
@section('twitter_image_alt', $artikel->judul)

@section('article_published_time', $artikel->published_at->toISOString())
@section('article_modified_time', $artikel->updated_at->toISOString())
@section('article_author', 'Goa Sentono')
@section('article_section', 'Wisata')

@section('content')
<div id="toast"
     class="fixed bottom-5 right-5 bg-gray-800 text-white text-sm px-4 py-2 rounded-lg shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 z-50">
</div>

<section class="pt-24 pb-8 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition duration-300">Beranda</a></li>
                <li class="text-gray-400">•</li>
                <li><a href="{{ route('artikel') }}" class="hover:text-primary transition duration-300">Artikel</a></li>
                <li class="text-gray-400">•</li>
                <li class="text-gray-700 font-medium">{{ Str::limit($artikel->judul, 50) }}</li>
            </ol>
        </nav>

        <header class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                @if($artikel->is_featured)
                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">UNGGULAN</span>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
                {{ $artikel->judul }}
            </h1>

            <div class="flex items-center justify-between border-b border-gray-200 pb-6">
                <div class="flex items-center space-x-6 text-gray-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $artikel->published_at->format('d F Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ ceil(str_word_count(strip_tags($artikel->isi_konten)) / 200) }} menit baca</span>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600 hidden sm:block">Bagikan:</span>
                
                    <a href="javascript:void(0);" 
                        onclick="shareWhatsApp('https://wa.me/?text={{ urlencode($artikel->judul . ' - ' . request()->fullUrl()) }}')" 
                        class="text-gray-400 hover:text-green-500 transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                    </a>
                
                    <button onclick="copyToClipboard()" 
                            class="text-gray-400 hover:text-gray-700 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>

            </div>
        </header>
    </div>
</section>

<section class="pb-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ $artikel->gambar_url }}" 
                    alt="{{ $artikel->judul }}" 
                    class="w-full h-64 md:h-80 lg:h-96 object-cover">
        </div>
    </div>
</section>

<article class="pb-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="article-content max-w-none">
            <div class="text-gray-800 prose prose-lg max-w-none">
                {!! $artikel->isi_konten !!}
            </div>
        </div>

        <footer class="mt-8 sm:mt-10 lg:mt-12 pt-6 sm:pt-8 border-t border-gray-200">

            <div class="mb-6 sm:mb-8">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Bagikan melalui:</h4>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                    <a href="javascript:void(0);" 
                        onclick="shareWhatsApp('https://wa.me/?text={{ urlencode($artikel->judul . ' - ' . request()->fullUrl()) }}')" 
                        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300 text-xs sm:text-sm">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        <span class="hidden sm:inline">WhatsApp</span>
                        <span class="sm:hidden">WA</span>
                    </a>
                    <button onclick="copyToClipboard()" 
                        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-300 text-xs sm:text-sm">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Salin Link</span>
                        <span class="sm:hidden">Salin</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-row justify-between items-center gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-200">
                <button onclick="smartGoBack()" 
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300 text-sm sm:text-base">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </button>
                
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition duration-300 text-sm sm:text-base">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Kembali ke Atas
                </button>
            </div>
        </footer>
    </div>
</article>

<section class="py-12 sm:py-14 lg:py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-10 lg:mb-12">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-2 sm:mb-4">Artikel Lainnya</h2>
            <p class="text-sm sm:text-base text-gray-600">Baca juga artikel menarik lainnya</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">
            @php
                $artikelLainnya = App\Models\Artikel::where('id', '!=', $artikel->id)
                                                    ->whereNotNull('published_at')
                                                    ->where('published_at', '<=', now())
                                                    ->latest('published_at')
                                                    ->limit(3)
                                                    ->get();
            @endphp

            @foreach($artikelLainnya as $item)
            <article class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden card-hover border border-gray-100 flex flex-col">
                <a href="{{ route('artikel.show', $item->slug) }}" class="block h-32 md:h-48 overflow-hidden">
                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" 
                            class="w-full h-full object-cover transition duration-300 hover:scale-105">
                </a>

                <div class="px-3 md:px-8 py-3 md:py-6 flex flex-col flex-grow">
                    <div class="flex items-center mb-2 md:mb-4">
                        <span class="text-xs md:text-sm text-gray-500">{{ $item->published_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="text-sm md:text-xl font-bold text-primary mb-2 md:mb-4 flex-grow line-clamp-2 md:line-clamp-none">
                        <a href="{{ route('artikel.show', $item->slug) }}" 
                            class="hover:text-opacity-80">{{ $item->judul }}</a>
                    </h3>
                    <p class="text-xs md:text-base text-accent leading-relaxed mb-3 md:mb-6 line-clamp-2 md:line-clamp-3">{{ $item->ringkasan_potong }}</p>
                    <a href="{{ route('artikel.show', $item->slug) }}" 
                        class="text-xs md:text-base text-primary font-bold hover:text-accent transition duration-300 flex items-center mt-auto">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        @if($artikelLainnya->isEmpty())
        <div class="text-center py-8 sm:py-10 lg:py-12">
            <p class="text-sm sm:text-base text-gray-500">Tidak ada artikel dan berita lainnya saat ini.</p>
        </div>
        @endif
    </div>
</section>

<script>
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Tersalin!';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    });
}
</script>
<script>
function smartGoBack() {
    if (window.history.length > 1 && document.referrer) {
        const referrerHost = new URL(document.referrer).hostname;
        const currentHost = window.location.hostname;
        
        if (referrerHost === currentHost) {
            window.history.back();
            return;
        }
    }
    
    window.location.href = "{{ route('artikel') }}";
}

if (typeof(Storage) !== "undefined") {
    sessionStorage.setItem('previousPage', document.referrer || "{{ route('artikel') }}");
}
</script>

<script>
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');

    setTimeout(() => {
        toast.classList.remove('opacity-100');
        toast.classList.add('opacity-0');
    }, 2000);
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        showToast('Link berhasil disalin!');
    });
}

function shareWhatsApp(url) {
    showToast('Membuka WhatsApp...');
    window.open(url, '_blank');
}
</script>

@endsection