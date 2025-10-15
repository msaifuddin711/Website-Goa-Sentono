{{-- resources/views/partials/galeri-item.blade.php --}}
<div class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
    <div class="block relative h-64 group cursor-pointer" onclick="openImageModal({{ $item->id }})">
        <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity duration-300" loading="lazy">
    </div>
    <div class="p-6 text-center">
        <h3 class="text-2xl font-semibold text-primary truncate">{{ $item->judul }}</h3>
    </div>
</div>