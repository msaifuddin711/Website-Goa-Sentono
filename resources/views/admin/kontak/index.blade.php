{{-- resources/views/admin/kontak/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Kontak')
@section('page-title', 'Kelola Kontak')
@section('page-subtitle', 'Kelola pesan masuk dan data anggota KKN')

@section('content')
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-soft-cream rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-primary-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Total Pesan</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $messages->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-cream rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye text-sage-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Sudah Dibaca</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $messages->where('is_read', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-warm-beige rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye-slash text-medium-brown text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Belum Dibaca</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $messages->where('is_read', false)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-light-beige p-6 card-hover">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-light-beige rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-primary-green text-xl"></i>
                </div>
                <div>
                    <p class="text-accent-green text-sm">Anggota KKN</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $kknMembers->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-2xl shadow-sm border border-light-beige overflow-hidden">
        <div class="border-b border-light-beige">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button class="tab-button active border-b-2 border-primary-green py-4 px-1 text-sm font-medium text-primary-green" 
                        data-tab="messages">
                    <i class="fas fa-envelope mr-2"></i>Pesan Masuk
                </button>
                <button class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-accent-green hover:text-primary-green hover:border-light-beige" 
                        data-tab="members">
                    <i class="fas fa-users mr-2"></i>Anggota KKN
                </button>
            </nav>
        </div>

        <!-- Messages Tab -->
        <div id="messages-tab" class="tab-content p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-primary-dark">Pesan Masuk</h3>
                <div class="flex items-center space-x-3">
                    <button class="bg-red-800 hover:bg-red-900 text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                            onclick="bulkDeleteMessages()">
                        <i class="fas fa-trash"></i>
                        <span class="hidden sm:inline">Hapus Terpilih</span>
                    </button>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="select-all-messages" class="w-4 h-4 text-primary-green bg-gray-100 border-gray-300 rounded focus:ring-primary-green">
                        <label for="select-all-messages" class="text-sm font-medium text-primary-dark">Pilih Semua</label>
                    </div>
                    <select id="status-filter" class="border border-light-beige rounded-lg px-3 py-2 text-sm bg-soft-cream text-primary-dark">
                        <option value="">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Sudah Dibaca</option>
                    </select>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" id="search-messages" placeholder="Cari pesan..." 
                               class="pl-10 pr-4 py-2 border border-light-beige rounded-lg focus:ring-2 focus:ring-primary-green focus:border-transparent bg-soft-cream text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-accent-green"></i>
                    </div>
                </div>
            </div>

            @if($messages->count() > 0)
                <div class="space-y-4">
                    @foreach($messages as $message)
                        <div class="message-item bg-soft-cream rounded-xl p-6 {{ !$message->is_read ? 'border-l-4 border-primary-green bg-cream' : 'border border-light-beige' }}" 
                             data-id="{{ $message->id }}" 
                             data-search="{{ strtolower($message->name . ' ' . $message->email . ' ' . $message->body) }}"
                             data-status="{{ $message->is_read ? 'read' : 'unread' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <input type="checkbox" name="selected_messages[]" value="{{ $message->id }}" 
                                           class="w-4 h-4 text-primary-green bg-white border-gray-300 rounded focus:ring-primary-green message-checkbox mt-1">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-bold text-primary-dark">{{ $message->name }}</h4>
                                            @if(!$message->is_read)
                                                <span class="bg-primary-green text-cream text-xs px-2 py-1 rounded-full">Baru</span>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-accent-green mb-3">
                                            <div><i class="fas fa-envelope mr-2"></i>{{ $message->email }}</div>
                                            @if($message->phone)
                                                <div><i class="fas fa-phone mr-2"></i>{{ $message->phone }}</div>
                                            @endif
                                        </div>
                                        <p class="text-primary-dark bg-white p-3 rounded-lg border border-light-beige">{{ $message->body }}</p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-xs text-accent-green">
                                                <i class="fas fa-clock mr-1"></i>{{ $message->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    @if($message->is_read)
                                        <button class="bg-warm-beige hover:bg-light-beige text-primary-dark px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                                onclick="markAsUnread({{ $message->id }})">
                                            <i class="fas fa-eye-slash mr-1"></i>Belum Dibaca
                                        </button>
                                    @else
                                        <button class="bg-sage-green hover:bg-primary-green text-cream px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                                onclick="markAsRead({{ $message->id }})">
                                            <i class="fas fa-eye mr-1"></i>Tandai Dibaca
                                        </button>
                                    @endif
                                    <button class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                            onclick="showDeleteConfirmation('message', {{ $message->id }}, '{{ $message->name }}')">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-light-beige rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-4xl text-accent-green"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-2">Belum Ada Pesan</h3>
                    <p class="text-accent-green">Pesan dari pengunjung akan muncul di sini</p>
                </div>
            @endif
        </div>

        <!-- Members Tab -->
        <div id="members-tab" class="tab-content p-6 hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-primary-dark">Anggota KKN</h3>
                <button class="bg-primary-green hover:bg-primary-dark text-cream px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2" 
                        onclick="showAddMemberPopup()">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">Tambah Anggota</span>
                </button>
            </div>

            @if($kknMembers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($kknMembers as $member)
                        <div class="bg-soft-cream rounded-xl overflow-hidden card-hover border border-light-beige">
                            <div class="relative">
                                <img src="{{ $member->photo_url }}" 
                                     alt="{{ $member->name }}" 
                                     class="w-full h-48 object-cover" loading="lazy">
                                @if($member->is_dpl)
                                    <div class="absolute top-2 left-2 bg-medium-brown text-cream text-xs px-2 py-1 rounded-full font-semibold">
                                        DPL
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 bg-cream rounded-lg px-2 py-1 text-xs font-semibold text-primary-dark">
                                    #{{ $member->order }}
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-primary-dark mb-1">{{ $member->name }}</h3>
                                <p class="text-sm text-accent-green mb-3">{{ $member->role }}</p>
                                <div class="flex space-x-2">
                                    <button class="flex-1 bg-warm-beige hover:bg-light-beige text-primary-dark px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="showEditMemberPopup({{ $member->toJson() }})">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <button class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200"
                                            onclick="showDeleteConfirmation('member', {{ $member->id }}, '{{ $member->name }}')">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-light-beige rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-4xl text-accent-green"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-2">Belum Ada Anggota</h3>
                    <p class="text-accent-green mb-4">Tambahkan anggota KKN untuk ditampilkan di halaman kontak</p>
                    <button class="bg-primary-green text-cream px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark hover:shadow-lg transform hover:scale-105 transition-all duration-200" 
                            onclick="showAddMemberPopup()">
                        <i class="fas fa-plus mr-2"></i>Tambah Anggota
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

<!-- Delete Confirmation Pop-up -->
<div id="delete-confirmation-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 transform scale-95 transition-transform duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-primary-dark mb-2">Konfirmasi Hapus</h3>
                <p class="text-accent-green mb-6" id="delete-message">
                    Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat diurungkan.
                </p>
                <div class="flex space-x-3">
                    <button onclick="hideDeleteConfirmation()" 
                            class="flex-1 bg-light-beige hover:bg-warm-beige text-primary-dark px-4 py-2 rounded-xl font-semibold transition-colors duration-200">
                        Batal
                    </button>
                    <button onclick="confirmDelete()" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold transition-colors duration-200">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Pop-up -->
<div id="bulk-delete-confirmation-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 transform scale-95 transition-transform duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-primary-dark mb-2">Konfirmasi Hapus Massal</h3>
                <p class="text-accent-green mb-6" id="bulk-delete-message">
                    Apakah Anda yakin ingin menghapus <span id="bulk-count">0</span> item yang dipilih? Tindakan ini tidak dapat diurungkan.
                </p>
                <div class="flex space-x-3">
                    <button onclick="hideBulkDeleteConfirmation()" 
                            class="flex-1 bg-light-beige hover:bg-warm-beige text-primary-dark px-4 py-2 rounded-xl font-semibold transition-colors duration-200">
                        Batal
                    </button>
                    <button onclick="confirmBulkDelete()" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold transition-colors duration-200">
                        Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Define routes for JavaScript to use
window.adminKontakRoutes = {
    destroyMessage: "{{ route('admin.kontak.message.destroy', ['message' => ':id']) }}",
    bulkDeleteMessages: "{{ route('admin.kontak.messages.bulk-delete') }}",
    markAsRead: "{{ route('admin.kontak.message.mark-read', ['message' => ':id']) }}",
    markAsUnread: "{{ route('admin.kontak.message.mark-unread', ['message' => ':id']) }}",
    storeMember: "{{ route('admin.kontak.member.store') }}",
    updateMember: "{{ route('admin.kontak.member.update', ['member' => ':id']) }}",
    destroyMember: "{{ route('admin.kontak.member.destroy', ['member' => ':id']) }}",
    reorderMembers: "{{ route('admin.kontak.members.reorder') }}",
};

// Delete confirmation variables
let deleteType = '';
let deleteId = '';
let deleteName = '';

// Show delete confirmation popup
function showDeleteConfirmation(type, id, name) {
    deleteType = type;
    deleteId = id;
    deleteName = name;
    
    const message = document.getElementById('delete-message');
    if (type === 'message') {
        message.textContent = `Apakah Anda yakin ingin menghapus pesan dari "${name}"? Tindakan ini tidak dapat diurungkan.`;
    } else if (type === 'member') {
        message.textContent = `Apakah Anda yakin ingin menghapus anggota "${name}"? Tindakan ini tidak dapat diurungkan.`;
    }
    
    const overlay = document.getElementById('delete-confirmation-overlay');
    overlay.classList.remove('hidden');
    setTimeout(() => {
        overlay.classList.remove('opacity-0');
        overlay.querySelector('.transform').classList.remove('scale-95');
    }, 10);
}

// Hide delete confirmation popup
function hideDeleteConfirmation() {
    const overlay = document.getElementById('delete-confirmation-overlay');
    overlay.classList.add('opacity-0');
    overlay.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => {
        overlay.classList.add('hidden');
    }, 300);
}

// Confirm delete action
function confirmDelete() {
    if (deleteType === 'message') {
        deleteMessage(deleteId);
    } else if (deleteType === 'member') {
        deleteMember(deleteId);
    }
    hideDeleteConfirmation();
}

// Show bulk delete confirmation
function showBulkDeleteConfirmation(count) {
    document.getElementById('bulk-count').textContent = count;
    
    const overlay = document.getElementById('bulk-delete-confirmation-overlay');
    overlay.classList.remove('hidden');
    setTimeout(() => {
        overlay.classList.remove('opacity-0');
        overlay.querySelector('.transform').classList.remove('scale-95');
    }, 10);
}

// Hide bulk delete confirmation
function hideBulkDeleteConfirmation() {
    const overlay = document.getElementById('bulk-delete-confirmation-overlay');
    overlay.classList.add('opacity-0');
    overlay.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => {
        overlay.classList.add('hidden');
    }, 300);
}

// Modified bulk delete function to show confirmation
function bulkDeleteMessages() {
    const selectedCheckboxes = document.querySelectorAll('.message-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        return;
    }
    
    showBulkDeleteConfirmation(selectedCheckboxes.length);
}

// Confirm bulk delete
function confirmBulkDelete() {
    const selectedCheckboxes = document.querySelectorAll('.message-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    // Make AJAX request to delete messages
    fetch(window.adminKontakRoutes.bulkDeleteMessages, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            ids: selectedIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove deleted messages from DOM
            selectedIds.forEach(id => {
                const messageElement = document.querySelector(`[data-id="${id}"]`);
                if (messageElement) {
                    messageElement.remove();
                }
            });
            
            // Optionally reload the page to update statistics
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
    
    hideBulkDeleteConfirmation();
}
</script>
@endsection

@push('scripts')
<script src="{{ asset('script/admin-kontak-script.js') }}"></script>
@endpush