{{-- resources/views/admin/galeri/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Galeri')
@section('page-title', 'Kelola Galeri')
@section('page-subtitle', 'Kelola foto dan gambar galeri Goa Sentono')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"> {{-- Diubah menjadi 4 kolom --}}
        {{-- Card Total --}}
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-soft-cream rounded-xl flex items-center justify-center">
                    <i class="fas fa-images text-primary-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Total Foto</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $galeriItems->total() }}</p>
                </div>
            </div>
        </div>
        
        {{-- CARD BARU: FOTO DITAMPILKAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-green-600 text-sm">Ditampilkan</p>
                    <p id="total-visible-count" class="text-2xl font-bold text-primary-dark">{{ $totalVisible }}</p>
                </div>
            </div>
        </div>

        {{-- CARD BARU: FOTO DISEMBUNYIKAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye-slash text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-red-600 text-sm">Disembunyikan</p>
                    <p id="total-hidden-count" class="text-2xl font-bold text-primary-dark">{{ $totalHidden }}</p>
                </div>
            </div>
        </div>
        
        {{-- Card Hari Ini (Disederhanakan) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-cream rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-accent-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Upload Hari Ini</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $galeriItems->where('created_at', '>=', today())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Gallery Management Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-light-beige overflow-hidden card-hover">
        <div class="bg-accent-green px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cream bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-images text-cream"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-cream">Galeri Foto</h2>
                        <p class="text-light-beige text-sm">Kelola foto dan gambar galeri website</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="bulk-delete-btn" class="bg-red-800 bg-opacity-70 hover:bg-opacity-90 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                            onclick="bulkDelete()">
                        <i class="fas fa-trash"></i>
                        <span class="hidden sm:inline">Hapus Terpilih</span>
                    </button>
                    <button class="bg-cream bg-opacity-20 hover:bg-opacity-30 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                            onclick="showAddGaleriPopup()">
                        <i class="fas fa-plus"></i>
                        <span class="hidden sm:inline">Tambah Foto</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Filter and Search -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-accent-green bg-gray-100 border-gray-300 rounded focus:ring-accent-green">
                        <label for="select-all" class="text-sm font-medium text-primary-dark">Pilih Semua</label>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Cari foto..." 
                               class="pl-10 pr-4 py-2 border border-light-beige rounded-lg focus:ring-2 focus:ring-accent-green focus:border-transparent bg-soft-cream text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-accent-green"></i>
                    </div>
                </div>
            </div>

            @if($galeriItems->count() > 0)
                <div id="galeri-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($galeriItems as $item)
                        {{-- Tambahkan class opacity-60 jika tidak visible --}}
                        <div class="bg-soft-cream rounded-xl overflow-hidden card-hover galeri-item border border-light-beige {{ !$item->is_visible ? 'opacity-60' : '' }}" 
                             data-id="{{ $item->id }}" 
                             data-search="{{ strtolower($item->judul . ' ' . $item->deskripsi) }}">
                            
                            <div class="relative">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-48 object-cover">
                                
                                {{-- Badge Status Visibilitas --}}
                                @if($item->is_visible)
                                    <span class="status-badge absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Ditampilkan</span>
                                @else
                                    <span class="status-badge absolute top-2 left-2 bg-gray-500 text-white text-xs font-bold px-2 py-1 rounded-full">Disembunyikan</span>
                                @endif

                                <div class="absolute top-2 right-2">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox ...">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-primary-dark mb-2 line-clamp-1">{{ $item->judul }}</h3>
                                {{-- ... (info deskripsi, tanggal, ukuran file) ... --}}
                                <div class="flex space-x-2 mt-4">
                                    {{-- Tombol Toggle Visibility BARU --}}
                                    <button class="flex-1 {{ $item->is_visible ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-800' : 'bg-green-100 hover:bg-green-200 text-green-800' }} px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="toggleVisibility({{ $item->id }})">
                                        <i class="fas {{ $item->is_visible ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>
                                        <span class="toggle-text">{{ $item->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</span>
                                    </button>
                                    <button class="bg-warm-beige px-3 py-2 rounded-lg hover:bg-light-beige ... " onclick="showEditGaleriPopup({{ $item->toJson() }})">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <button class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="deleteGaleri({{ $item->id }})">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $galeriItems->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-light-beige rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-images text-4xl text-accent-green"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-2">Belum Ada Foto</h3>
                    <p class="text-accent-green mb-4">Tambahkan foto pertama untuk galeri</p>
                    <button class="bg-accent-green text-cream px-6 py-3 rounded-xl font-semibold hover:bg-sage-green hover:shadow-lg transform hover:scale-105 transition-all duration-200" 
                            onclick="showAddGaleriPopup()">
                        <i class="fas fa-plus mr-2"></i>Tambah Foto
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
window.adminGaleriRoutes = {
    store: "{{ route('admin.galeri.store') }}",
    update: "{{ route('admin.galeri.update', ['galeri' => ':id']) }}",
    delete: "{{ route('admin.galeri.destroy', ['galeri' => ':id']) }}",
    toggleVisibility: "{{ route('admin.galeri.toggleVisibility', ['galeri' => ':id']) }}",
    reorder: "{{ route('admin.galeri.reorder') }}",
    bulkDelete: "{{ route('admin.galeri.bulk-delete') }}"
};
</script>
@endsection

@push('scripts')
<script src="{{ asset('script/admin-galeri-script.js') }}"></script>
@endpush