{{-- resources/views/admin/artikel/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Artikel')
@section('page-title', 'Kelola Artikel')
@section('page-subtitle', 'Kelola artikel dan berita Goa Sentono')


@section('content')
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-soft-cream rounded-xl flex items-center justify-center">
                    <i class="fas fa-newspaper text-primary-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Total Artikel</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $artikels->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-green-600 text-sm">Ditampilkan</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $artikels->where('is_visible', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye-slash text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-red-600 text-sm">Disembunyikan</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $artikels->where('is_visible', false)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-cream rounded-xl flex items-center justify-center">
                    <i class="fas fa-star text-medium-brown text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Unggulan</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $artikels->where('is_featured', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-light-beige rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-sage-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Hari Ini</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $artikels->where('created_at', '>=', today())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Article Management Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-light-beige overflow-hidden card-hover">
        <div class="bg-primary-green px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cream bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-newspaper text-cream"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-cream">Artikel & Berita</h2>
                        <p class="text-light-beige text-sm">Kelola artikel dan berita website</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="bulk-delete-btn" class="bg-red-800 bg-opacity-70 hover:bg-opacity-90 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                            onclick="bulkDelete()">
                        <i class="fas fa-trash"></i>
                        <span class="hidden sm:inline">Hapus Terpilih</span>
                    </button>
                    <button class="bg-cream bg-opacity-20 hover:bg-opacity-30 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                            onclick="showAddArtikelPopup()">
                        <i class="fas fa-plus"></i>
                        <span class="hidden sm:inline">Tambah Artikel</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Filter and Search -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-primary-green bg-gray-100 border-gray-300 rounded focus:ring-primary-green">
                        <label for="select-all" class="text-sm font-medium text-primary-dark">Pilih Semua</label>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Cari artikel..." 
                               class="pl-10 pr-4 py-2 border border-light-beige rounded-lg focus:ring-2 focus:ring-accent-green focus:border-transparent bg-soft-cream text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-accent-green"></i>
                    </div>
                </div>
            </div>

            @if($artikels->count() > 0)
                <div id="artikel-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($artikels as $item)
                        <div class="bg-soft-cream rounded-xl overflow-hidden card-hover artikel-item border-l-4 {{ $item->is_featured ? 'border-l-medium-brown' : 'border-l-light-beige' }} border border-light-beige {{ !$item->is_visible ? 'opacity-60' : '' }}" 
                             data-id="{{ $item->id }}" 
                             data-search="{{ strtolower($item->judul . ' ' . strip_tags($item->isi_konten)) }}">
                            <div class="relative">
                                <img src="{{ $item->gambar_url }}" 
                                     alt="{{ $item->judul }}" 
                                     class="w-full h-48 object-cover">
                                
                                <!-- Visibility Badge -->
                                @if($item->is_visible)
                                    <span class="status-badge absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Ditampilkan</span>
                                @else
                                    <span class="status-badge absolute top-2 left-2 bg-gray-500 text-white text-xs font-bold px-2 py-1 rounded-full">Disembunyikan</span>
                                @endif

                                <!-- Featured Badge -->
                                @if($item->is_featured)
                                <div class="absolute top-2 left-24 bg-medium-brown text-cream rounded-lg px-2 py-1 text-xs font-semibold">
                                    <i class="fas fa-star mr-1"></i>UNGGULAN
                                </div>
                                @endif
                                
                                <!-- Checkbox -->
                                <div class="absolute top-2 right-2">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                           class="w-4 h-4 text-primary-green bg-white border-gray-300 rounded focus:ring-primary-green item-checkbox">
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-bold text-primary-dark mb-2 line-clamp-2">{{ $item->judul }}</h3>
                                <p class="text-sm text-accent-green mb-3 line-clamp-3">{{ $item->ringkasan_potong }}</p>
                                
                                <div class="flex items-center justify-between text-xs text-accent-green mb-3">
                                    <span>{{ $item->published_at->format('d M Y') }}</span>
                                    <span>{{ $item->created_at->format('H:i') }}</span>
                                </div>
                                
                                <div class="flex space-x-2 mb-2">
                                    <button class="flex-1 {{ $item->is_visible ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-800' : 'bg-green-100 hover:bg-green-200 text-green-800' }} px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="toggleVisibility({{ $item->id }})">
                                        <i class="fas {{ $item->is_visible ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>{{ $item->is_visible ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                    <button class="flex-1 bg-light-sage hover:bg-sage-green text-cream px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="toggleFeatured({{ $item->id }}, {{ $item->is_featured ? 'false' : 'true' }})">
                                        <i class="fas fa-star mr-1"></i>{{ $item->is_featured ? 'Tidak Unggulan' : 'Unggulan' }}
                                    </button>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="flex-1 bg-warm-beige hover:bg-light-beige text-primary-dark px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="showEditArtikelPopup({{ $item->toJson() }})">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <button class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="deleteArtikel({{ $item->id }})">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $artikels->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-light-beige rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-newspaper text-4xl text-accent-green"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-2">Belum Ada Artikel</h3>
                    <p class="text-accent-green mb-4">Tambahkan artikel pertama untuk website</p>
                    <button class="bg-primary-green text-cream px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark hover:shadow-lg transform hover:scale-105 transition-all duration-200" 
                            onclick="showAddArtikelPopup()">
                        <i class="fas fa-plus mr-2"></i>Tambah Artikel
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Pop-up Overlay -->
<div id="popup-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="popup-content" class="transform scale-95 transition-transform duration-300"></div>
    </div>
</div>

<script>
// Define routes for JavaScript to use
window.adminArtikelRoutes = {
    store: "{{ route('admin.artikel.store') }}",
    update: "{{ route('admin.artikel.update', ['artikel' => ':id']) }}",
    delete: "{{ route('admin.artikel.destroy', ['artikel' => ':id']) }}",
    bulkDelete: "{{ route('admin.artikel.bulk-delete') }}",
    toggleFeatured: "{{ route('admin.artikel.toggle-featured', ['artikel' => ':id']) }}",
    toggleVisibility: "{{ route('admin.artikel.toggleVisibility', ['artikel' => ':id']) }}"
};
</script>
@endsection

@push('scripts')
<script src="{{ asset('script/admin-artikel-script.js') }}"></script>
@endpush