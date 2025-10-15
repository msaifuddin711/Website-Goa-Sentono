{{-- resources/views/admin/tentang/partials/item-list.blade.php --}}
<div class="row {{ $type }}-items">
    @if($items->count() > 0)
        @foreach($items as $item)
            <div class="col-md-4 mb-3" data-id="{{ $item->id }}">
                <div class="card h-100">
                    <img src="{{ $item->gambar_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $item->judul }}" loading="lazy">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->judul }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($item->deskripsi, 100) }}</p>
                        @if($type === 'wisata_sekitar' && $item->info_tambahan)
                            <p class="card-text"><small class="text-muted">{{ $item->info_tambahan }}</small></p>
                        @endif
                        <p class="card-text"><small class="text-muted">Urutan: {{ $item->urutan }}</small></p>
                        <div class="btn-group btn-group-sm mt-2 w-100">
                            <button class="btn btn-warning" onclick='editItem("{{ $type }}", {{ json_encode($item) }})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteItem({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted">Belum ada item untuk <strong>{{ ucfirst(str_replace('_', ' ', $type)) }}</strong>.</p>
    @endif
</div>
