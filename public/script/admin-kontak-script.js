document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeMessageFilters();
    initializeMessageSearch();
    initializeSelectAll();
});

function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            tabContents.forEach(content => content.classList.add('hidden'));

            button.classList.add('active', 'border-blue-500', 'text-blue-600');
            button.classList.remove('border-transparent', 'text-gray-500');
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });
}

function initializeMessageFilters() {
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterMessages);
    }
}

function initializeMessageSearch() {
    const searchInput = document.getElementById('search-messages');
    if (searchInput) {
        searchInput.addEventListener('input', filterMessages);
    }
}

function filterMessages() {
    const statusFilter = document.getElementById('status-filter')?.value || '';
    const searchQuery = document.getElementById('search-messages')?.value.toLowerCase() || '';
    const messageItems = document.querySelectorAll('.message-item');

    messageItems.forEach(item => {
        const status = item.getAttribute('data-status');
        const searchText = item.getAttribute('data-search');
        
        const statusMatch = !statusFilter || status === statusFilter;
        const searchMatch = !searchQuery || searchText.includes(searchQuery);
        
        if (statusMatch && searchMatch) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function initializeSelectAll() {
    const selectAllMessages = document.getElementById('select-all-messages');
    if (selectAllMessages) {
        selectAllMessages.addEventListener('change', function() {
            const messageCheckboxes = document.querySelectorAll('.message-checkbox');
            messageCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
}

function deleteMessage(id) {
    const url = window.adminKontakRoutes.destroyMessage.replace(':id', id);
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

function bulkDeleteMessages() {
    const selectedCheckboxes = document.querySelectorAll('.message-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('Pilih minimal satu pesan untuk dihapus.');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.adminKontakRoutes.bulkDeleteMessages;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

function markAsRead(id) {
    const url = window.adminKontakRoutes.markAsRead.replace(':id', id);
    submitAction(url, 'PATCH');
}

function markAsUnread(id) {
    const url = window.adminKontakRoutes.markAsUnread.replace(':id', id);
    submitAction(url, 'PATCH');
}

function showAddMemberPopup() {
    const popupContent = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Tambah Anggota KKN</h3>
                    <button onclick="hidePopup()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
                </div>
            </div>
            <form action="${window.adminKontakRoutes.storeMember}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required maxlength="30" oninput="checkNameLength(this)"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi/Role</label>
                    <select name="role" required id="role-select"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            onchange="handleRoleChange(this)">
                        <option value="">Pilih Posisi</option>
                        <option value="Dosen Pembimbing Lapangan">Dosen Pembimbing Lapangan</option>
                        <option value="Ketua">Ketua</option>
                        <option value="Wakil Ketua">Wakil Ketua</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Publikasi, Desain, dan Dokumentasi">Publikasi, Desain, dan Dokumentasi</option>
                        <option value="Hubungan Masyarakat">Hubungan Masyarakat</option>
                        <option value="Logistik">Logistik</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto</label>
                    <input type="file" name="photo" accept="image/*" required 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Max 5MB, format: JPG, PNG, WebP</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="order" min="0" id="order-input" placeholder="Otomatis berdasarkan posisi"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk urutan otomatis berdasarkan posisi</p>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_dpl" value="1" id="is_dpl" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_dpl" class="ml-2 text-sm font-medium text-gray-700">Dosen Pembimbing Lapangan</label>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="hidePopup()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-xl font-semibold transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-xl font-semibold transition-colors duration-200">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    `;
    
    showPopup(popupContent);
}

function checkNameLength(input) {
    const warning = input.parentElement.querySelector('#name-warning');
    if (input.value.length > 30) {
        if (warning) warning.classList.remove('hidden');
    } else {
        if (warning) warning.classList.add('hidden');
    }
}

function showEditMemberPopup(member) {
    const popupContent = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="bg-primary-green px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Edit Anggota KKN</h3>
                    <button onclick="hidePopup()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
                </div>
            </div>
            <form action="${window.adminKontakRoutes.updateMember.replace(':id', member.id)}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="PUT">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" maxlength="30" value="${member.name}" required oninput="checkNameLength(this)"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi/Role</label>
                    <select name="role" required id="edit-role-select"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            onchange="handleRoleChange(this)">
                        <option value="">Pilih Posisi</option>
                        <option value="Dosen Pembimbing Lapangan" ${member.role === 'Dosen Pembimbing Lapangan' ? 'selected' : ''}>Dosen Pembimbing Lapangan</option>
                        <option value="Ketua" ${member.role === 'Ketua' ? 'selected' : ''}>Ketua</option>
                        <option value="Wakil Ketua" ${member.role === 'Wakil Ketua' ? 'selected' : ''}>Wakil Ketua</option>
                        <option value="Sekretaris" ${member.role === 'Sekretaris' ? 'selected' : ''}>Sekretaris</option>
                        <option value="Bendahara" ${member.role === 'Bendahara' ? 'selected' : ''}>Bendahara</option>
                        <option value="Publikasi, Desain, dan Dokumentasi" ${member.role === 'Publikasi, Desain, dan Dokumentasi' ? 'selected' : ''}>Publikasi, Desain, dan Dokumentasi</option>
                        <option value="Hubungan Masyarakat" ${member.role === 'Hubungan Masyarakat' ? 'selected' : ''}>Hubungan Masyarakat</option>
                        <option value="Logistik" ${member.role === 'Logistik' ? 'selected' : ''}>Logistik</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto (Opsional)</label>
                    <input type="file" name="photo" accept="image/*" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="order" value="${member.order}" min="0" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_dpl" value="1" id="edit_is_dpl" ${member.is_dpl ? 'checked' : ''} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="edit_is_dpl" class="ml-2 text-sm font-medium text-gray-700">Dosen Pembimbing Lapangan</label>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="hidePopup()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-xl font-semibold transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    `;
    
    showPopup(popupContent);
}

function deleteMember(id) {
    const url = window.adminKontakRoutes.destroyMember.replace(':id', id);
    submitAction(url, 'DELETE');
}

function showPopup(content) {
    const overlay = document.getElementById('popup-overlay');
    const popupContent = document.getElementById('popup-content');
    
    popupContent.innerHTML = content;
    overlay.classList.remove('hidden');
    
    setTimeout(() => {
        overlay.classList.remove('opacity-0');
        popupContent.querySelector('.bg-white').classList.remove('scale-95');
        popupContent.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function hidePopup() {
    const overlay = document.getElementById('popup-overlay');
    const popupContent = document.getElementById('popup-content');
    
    overlay.classList.add('opacity-0');
    popupContent.querySelector('.bg-white').classList.remove('scale-100');
    popupContent.querySelector('.bg-white').classList.add('scale-95');
    
    setTimeout(() => {
        overlay.classList.add('hidden');
    }, 300);
}

function submitAction(url, method) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    if (method !== 'POST') {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = method;
        form.appendChild(methodInput);
    }
    
    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('click', function(e) {
    const overlay = document.getElementById('popup-overlay');
    if (e.target === overlay) {
        hidePopup();
    }
});

function handleRoleChange(selectElement) {
    const form = selectElement.closest('form');
    const isDplCheckbox = form.querySelector('input[name="is_dpl"]');
    const orderInput = form.querySelector('input[name="order"]');
    const selectedRole = selectElement.value;
    
    if (selectedRole === 'Dosen Pembimbing Lapangan') {
        isDplCheckbox.checked = true;
        isDplCheckbox.disabled = true;
    } else {
        isDplCheckbox.checked = false;
        isDplCheckbox.disabled = false;
    }
    
    if (selectedRole && orderInput) {
        const defaultOrder = getDefaultOrderByRole(selectedRole);
        orderInput.value = defaultOrder;
        orderInput.placeholder = `Default: ${defaultOrder} (${selectedRole})`;
    }
}

function getDefaultOrderByRole(role) {
    const roleOrder = {
        'Dosen Pembimbing Lapangan': 0,
        'Ketua': 1,
        'Wakil Ketua': 2,
        'Sekretaris': 3,
        'Bendahara': 4,
        'Publikasi, Desain, dan Dokumentasi': 5,
        'Hubungan Masyarakat': 6,
        'Logistik': 7
    };
    
    return roleOrder[role] || 99;
}