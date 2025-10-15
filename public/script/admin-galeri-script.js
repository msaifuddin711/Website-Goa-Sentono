// public/script/admin-galeri-script.js

$(document).ready(function() {
    // File preview functionality
    $(document).on('change', 'input[type="file"]', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            const previewContainer = $(this).closest('.popup-content, form').find('#image_preview');
            const previewImg = previewContainer.find('img');
            
            reader.onload = function(e) {
                previewContainer.removeClass('hidden').addClass('animate-fade-in');
                previewImg.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Select all functionality
    $('#select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.item-checkbox').prop('checked', isChecked);
        updateBulkDeleteButton();
    });

    // Individual checkbox change
    $(document).on('change', '.item-checkbox', function() {
        updateBulkDeleteButton();
        updateSelectAllState();
    });

    // Search functionality
    $('#search-input').on('input', function() {
        filterGaleriItems();
    });

    // Initialize character counters
    $(document).on('input', 'textarea[maxlength]', function() {
        const counterId = $(this).data('counter');
        if (counterId) {
            updateCharCount(this, counterId);
        }
    });

    // Enhanced form validation
    $(document).on('submit', 'form', function(e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        
        if (!validateGaleriForm(this)) {
            e.preventDefault();
            return false;
        }
        
        // Add loading state
        $submitBtn.prop('disabled', true);
        const originalText = $submitBtn.html();
        $submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
        
        // Re-enable after timeout as fallback
        setTimeout(() => {
            $submitBtn.prop('disabled', false);
            $submitBtn.html(originalText);
        }, 5000);
    });

    // Close popup on overlay click
    $(document).on('click', '#popup-overlay', function(e) {
        if (e.target === this) {
            closePopup();
        }
    });

    // Escape key to close popup
    $(document).on('keydown', function(e) {
        if (e.which === 27) { // ESC key
            closePopup();
        }
    });
});

function toggleVisibility(id) {
    const url = window.adminGaleriRoutes.toggleVisibility.replace(':id', id);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken
        },
        success: function(data) {
            if (data.success) {
                showNotification(data.message, 'success');

                const $itemElement = $(`.galeri-item[data-id='${id}']`);
                const $statusBadge = $itemElement.find('.status-badge');
                const $toggleButton = $itemElement.find('button[onclick^="toggleVisibility"]');
                const $toggleText = $toggleButton.find('.toggle-text');
                const $toggleIcon = $toggleButton.find('i');

                if (data.is_visible) {
                    $itemElement.removeClass('opacity-60');
                    $statusBadge.text('Ditampilkan').removeClass('bg-gray-500').addClass('bg-green-500');
                    
                    $toggleButton.removeClass('bg-green-100 hover:bg-green-200 text-green-800').addClass('bg-yellow-100 hover:bg-yellow-200 text-yellow-800');
                    $toggleText.text('Sembunyikan');
                    $toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $itemElement.addClass('opacity-60');
                    $statusBadge.text('Disembunyikan').removeClass('bg-green-500').addClass('bg-gray-500');

                    $toggleButton.removeClass('bg-yellow-100 hover:bg-yellow-200 text-yellow-800').addClass('bg-green-100 hover:bg-green-200 text-green-800');
                    $toggleText.text('Tampilkan');
                    $toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }

                if (data.totalVisible !== undefined && data.totalHidden !== undefined) {
                    $('#total-visible-count').text(data.totalVisible);
                    $('#total-hidden-count').text(data.totalHidden);
                }
            }
        },
        error: function() {
            showNotification('Terjadi kesalahan saat mengubah status.', 'error');
        }
    });
}

// Show Add Galeri Popup
function showAddGaleriPopup() {
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Tambah Foto Galeri</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${window.adminGaleriRoutes.store}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-500 mr-2"></i>Judul Foto
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="judul" name="judul" required placeholder="Masukkan judul foto...">
                        </div>
                        
                        <div>
                            <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-amber-500 mr-2"></i>Upload Foto
                            </label>
                            <input type="file" 
                                   class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-blue-500 transition-colors duration-200" 
                                   id="gambar" name="gambar" accept="image/*" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-green-500 mr-2"></i>Deskripsi (Opsional)
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  id="deskripsi" name="deskripsi" rows="4" maxlength="500"
                                  placeholder="Masukkan deskripsi foto... (opsional)"
                                  data-counter="deskripsi_count"></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Deskripsi maksimal 500 karakter
                            </p>
                            <p class="text-xs" id="deskripsi_count">0/500</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <input id="is_visible" name="is_visible" type="checkbox" value="1" class="h-5 w-5 rounded border-gray-300 text-primary-green focus:ring-primary-green" checked>
                        <div>
                            <label for="is_visible" class="font-medium text-gray-800">Tampilkan di Galeri</label>
                            <p class="text-sm text-gray-500">Hilangkan centang untuk menyembunyikan foto ini dari galeri publik.</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mt-2">Format yang didukung: JPG, PNG, WEBP</p>
                        <div id="image_preview" class="mt-3 hidden">
                            <img src="" class="max-w-64 h-40 object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" class="px-6 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200" onclick="closePopup()">
                        Batal
                    </button>
                    <button type="submit" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    `;
    
    showPopup(popupContent);
}

// Show Edit Galeri Popup
function showEditGaleriPopup(item) {
    const updateUrl = window.adminGaleriRoutes.update.replace(':id', item.id);
    const isVisibleChecked = item.is_visible ? 'checked' : '';
    
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
            <div class="bg-primary-green px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Edit Foto Galeri</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${updateUrl}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="PUT">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="edit_judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-amber-500 mr-2"></i>Judul Foto
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" 
                                   id="edit_judul" name="judul" required value="${item.judul || ''}">
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-green-500 mr-2"></i>Deskripsi (Opsional)
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  id="edit_deskripsi" name="deskripsi" rows="4" maxlength="500"
                                  data-counter="edit_deskripsi_count"
                                  placeholder="Masukkan deskripsi foto... (opsional)">${item.deskripsi || ''}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Deskripsi maksimal 500 karakter
                            </p>
                            <p class="text-xs" id="edit_deskripsi_count">0/500</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <input id="edit_is_visible" name="is_visible" type="checkbox" value="1" class="h-5 w-5 rounded border-gray-300 text-primary-green focus:ring-primary-green" ${isVisibleChecked}>
                        <div>
                            <label for="edit_is_visible" class="font-medium text-gray-800">Tampilkan di Galeri</label>
                            <p class="text-sm text-gray-500">Hilangkan centang untuk menyembunyikan foto ini dari galeri publik.</p>
                        </div>
                    </div>

                    <div>
                        <label for="edit_gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-amber-500 mr-2"></i>Foto Baru (opsional)
                        </label>
                        <input type="file" 
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-amber-500 transition-colors duration-200" 
                               id="edit_gambar" name="gambar" accept="image/*">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah foto</p>
                        <div id="current_image_preview" class="mt-3">
                            <div class="relative inline-block">
                                <img src="${item.gambar_url || ''}" class="max-w-64 h-40 object-cover rounded-lg shadow-sm">
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200">
                                    <p class="text-white text-xs text-center px-2">Foto saat ini<br>Kosongkan jika tidak ingin mengubah</p>
                                </div>
                            </div>
                        </div>
                        <div id="image_preview" class="mt-3 hidden">
                            <img src="" class="max-w-64 h-40 object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" class="px-6 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200" onclick="closePopup()">
                        Batal
                    </button>
                    <button type="submit" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    `;
    
    showPopup(popupContent);
    
    // Initialize character count for edit form
    setTimeout(() => {
        updateCharCount(document.getElementById('edit_deskripsi'), 'edit_deskripsi_count');
    }, 100);
}

// Show popup function
function showPopup(content) {
    const overlay = $('#popup-overlay');
    const contentContainer = $('#popup-content');
    
    contentContainer.html(content);
    overlay.removeClass('hidden');
    
    // Animate in
    setTimeout(() => {
        overlay.addClass('opacity-100');
        contentContainer.find('.popup-content').removeClass('scale-95').addClass('scale-100');
    }, 10);
    
    // Prevent body scroll
    $('body').addClass('overflow-hidden');
}

// Close popup function
function closePopup() {
    const overlay = $('#popup-overlay');
    const contentContainer = $('#popup-content');
    
    // Animate out
    overlay.removeClass('opacity-100');
    contentContainer.find('.popup-content').removeClass('scale-100').addClass('scale-95');
    
    setTimeout(() => {
        overlay.addClass('hidden');
        contentContainer.html('');
        $('body').removeClass('overflow-hidden');
    }, 300);
}

// Legacy function to maintain compatibility
function editGaleri(item) {
    showEditGaleriPopup(item);
}

// Delete galeri function
function deleteGaleri(id) {
    const confirmModal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" id="deleteConfirmModal">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-trash text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Hapus</h3>
                            <p class="text-gray-600">Yakin ingin menghapus foto ini?</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button onclick="document.getElementById('deleteConfirmModal').remove()" 
                                class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            Batal
                        </button>
                        <button onclick="confirmDeleteGaleri(${id})" 
                                class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors duration-200">
                            Ya, Hapus Foto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(confirmModal);
}

// Confirm single delete - NEW FUNCTION
function confirmDeleteGaleri(id) {
    const deleteUrl = window.adminGaleriRoutes.delete.replace(':id', id);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    const form = $('<form>', {
        method: 'POST',
        action: deleteUrl,
        style: 'display:none;'
    });
    
    form.append($('<input>', {type: 'hidden', name: '_token', value: csrfToken}));
    form.append($('<input>', {type: 'hidden', name: '_method', value: 'DELETE'}));
    
    $('body').append(form);
    
    showNotification('Menghapus foto...', 'info');
    form.submit();
    
    $('#deleteConfirmModal').remove();
}

// Bulk delete function - CORRECTED VERSION
function confirmBulkDelete() {
    const selectedIds = [];
    $('.item-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });
    
    if (selectedIds.length === 0) {
        showNotification('Tidak ada foto yang dipilih', 'warning');
        $('#bulkDeleteConfirmModal').remove();
        return;
    }
    
    // Create form for bulk delete
    const form = $('<form>', {
        method: 'POST',
        action: window.adminGaleriRoutes.bulkDelete,
        style: 'display:none;'
    });
    
    // Add CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    form.append($('<input>', {type: 'hidden', name: '_token', value: csrfToken}));
    
    // Add selected IDs
    selectedIds.forEach(id => {
        form.append($('<input>', {type: 'hidden', name: 'ids[]', value: id}));
    });
    
    // Append form to body and submit
    $('body').append(form);
    
    showNotification(`Menghapus ${selectedIds.length} foto...`, 'info');
    form.submit();
    
    // Remove confirmation modal
    $('#bulkDeleteConfirmModal').remove();
}

// CORRECTED - Update bulk delete button state
function updateBulkDeleteButton() {
    const selectedCount = $('.item-checkbox:checked').length;
    const $bulkDeleteBtn = $('#bulk-delete-btn');
    
    if (selectedCount > 0) {
        $bulkDeleteBtn.removeClass('opacity-50 cursor-not-allowed');
        $bulkDeleteBtn.prop('disabled', false);
        $bulkDeleteBtn.find('span').text(`Hapus ${selectedCount} Terpilih`);
    } else {
        $bulkDeleteBtn.addClass('opacity-50 cursor-not-allowed');
        $bulkDeleteBtn.prop('disabled', true);
        $bulkDeleteBtn.find('span').text('Hapus Terpilih');
    }
}

// ALSO UPDATE the existing bulkDelete function to make sure the count variable is properly defined
function bulkDelete() {
    const selectedIds = [];
    $('.item-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });
    
    if (selectedIds.length === 0) {
        showNotification('Pilih minimal satu foto untuk dihapus', 'warning');
        return;
    }
    
    const count = selectedIds.length; // Make sure this is defined
    
    const confirmModal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" id="bulkDeleteConfirmModal">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-trash text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Hapus</h3>
                            <p class="text-gray-600">Yakin ingin menghapus ${count} foto?</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button onclick="document.getElementById('bulkDeleteConfirmModal').remove()" 
                                class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            Batal
                        </button>
                        <button onclick="confirmBulkDelete()" 
                                class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors duration-200">
                            Ya, Hapus ${count} Foto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(confirmModal);
}

// Update select all state
function updateSelectAllState() {
    const totalCheckboxes = $('.item-checkbox').length;
    const checkedCheckboxes = $('.item-checkbox:checked').length;
    const $selectAll = $('#select-all');
    
    if (checkedCheckboxes === 0) {
        $selectAll.prop('indeterminate', false);
        $selectAll.prop('checked', false);
    } else if (checkedCheckboxes === totalCheckboxes) {
        $selectAll.prop('indeterminate', false);
        $selectAll.prop('checked', true);
    } else {
        $selectAll.prop('indeterminate', true);
    }
}

// Filter galeri items (simplified without kategori)
function filterGaleriItems() {
    const searchQuery = $('#search-input').val().toLowerCase();
    
    $('.galeri-item').each(function() {
        const $item = $(this);
        const itemSearch = $item.data('search');
        
        let showItem = true;
        
        // Filter by search only
        if (searchQuery && !itemSearch.includes(searchQuery)) {
            showItem = false;
        }
        
        if (showItem) {
            $item.removeClass('hidden').addClass('animate-fade-in');
        } else {
            $item.addClass('hidden').removeClass('animate-fade-in');
        }
    });
    
    // Update empty state
    const visibleItems = $('.galeri-item:not(.hidden)').length;
    if (visibleItems === 0) {
        if ($('#no-results').length === 0) {
            $('#galeri-container').append(`
                <div id="no-results" class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Hasil</h3>
                    <p class="text-gray-500">Coba ubah kata kunci pencarian</p>
                </div>
            `);
        }
    } else {
        $('#no-results').remove();
    }
}

// Enhanced form validation (simplified)
function validateGaleriForm(form) {
    let isValid = true;
    const errors = [];
    
    // Check required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            const fieldName = field.getAttribute('name') || field.getAttribute('id');
            errors.push(`${ucfirst(fieldName.replace('_', ' '))} wajib diisi`);
            isValid = false;
            field.classList.add('border-red-400', 'ring-red-400');
        } else {
            field.classList.remove('border-red-400', 'ring-red-400');
        }
    });
    
    // Check file size
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            errors.push('Format file harus JPG, PNG, atau WEBP');
            isValid = false;
        }
    }
    
    // Check textarea character limit
    const textareas = form.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const maxLength = parseInt(textarea.getAttribute('maxlength'));
        const currentLength = textarea.value.length;
        
        if (currentLength > maxLength) {
            const fieldName = textarea.getAttribute('name') || 'field';
            errors.push(`${ucfirst(fieldName.replace('_', ' '))} tidak boleh lebih dari ${maxLength} karakter`);
            isValid = false;
            textarea.classList.add('border-red-400', 'ring-red-400');
        }
    });
    
    // Show errors if any
    if (!isValid) {
        showNotification(errors.join('. '), 'error');
        // Focus on first invalid field
        const firstInvalid = form.querySelector('.border-red-400');
        if (firstInvalid) {
            firstInvalid.focus();
        }
    }
    
    return isValid;
}

// Character count function (reused from admin-script.js)
function updateCharCount(element, counterId) {
    const maxLength = parseInt(element.getAttribute('maxlength')) || 500;
    const currentLength = element.value.length;
    const counter = document.getElementById(counterId);
    
    if (counter) {
        const remaining = maxLength - currentLength;
        
        let counterClass = 'text-gray-500';
        if (currentLength > maxLength) {
            counterClass = 'text-red-600 font-bold';
        } else if (remaining <= 50) {
            counterClass = 'text-red-500 font-bold';
        } else if (remaining <= 100) {
            counterClass = 'text-orange-500 font-semibold';
        }
        
        counter.innerHTML = `<span class="${counterClass}">${currentLength}</span><span class="text-gray-400">/${maxLength}</span>`;
        
        // Add visual feedback to textarea border
        element.classList.remove('border-red-400', 'ring-red-400', 'border-orange-400', 'ring-orange-400', 'border-gray-200');
        
        if (currentLength > maxLength) {
            element.classList.add('border-red-400', 'ring-red-400');
        } else if (remaining <= 50) {
            element.classList.add('border-orange-400', 'ring-orange-400');
        } else {
            element.classList.add('border-gray-200');
        }
    }
}

// Notification function (reused from admin-script.js)
function showNotification(message, type = 'info') {
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle',
        warning: 'fas fa-exclamation-triangle'
    };
    
    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800'
    };
    
    const iconColors = {
        success: 'text-green-600',
        error: 'text-red-600',
        info: 'text-blue-600',
        warning: 'text-yellow-600'
    };
    
    const notificationId = `notification-${Date.now()}`;
    const notification = `
        <div class="fixed top-4 right-4 z-50 ${colors[type]} border rounded-xl p-4 shadow-lg transform animate-slide-in-right max-w-sm" id="${notificationId}">
            <div class="flex items-center space-x-3">
                <i class="${icons[type]} ${iconColors[type]}"></i>
                <span class="font-medium">${message}</span>
                <button onclick="removeNotification('${notificationId}')" class="ml-auto hover:bg-black hover:bg-opacity-10 p-1 rounded">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    `;
    
    $('body').append(notification);
    
    // Auto remove after 5 seconds
    const timeout = type === 'warning' ? 8000 : 5000;
    setTimeout(() => {
        removeNotification(notificationId);
    }, timeout);
}

// Remove notification function
function removeNotification(notificationId) {
    $(`#${notificationId}`).fadeOut(300, function() {
        $(this).remove();
    });
}

// Utility function
function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Ctrl/Cmd + S to save form
    if ((e.ctrlKey || e.metaKey) && e.which === 83) {
        e.preventDefault();
        const $form = $('.popup-content form:visible').first();
        if ($form.length) {
            if (validateGaleriForm($form[0])) {
                $form.submit();
                showNotification('Form disimpan dengan shortcut keyboard!', 'success');
            }
        }
    }
    
    // Escape key to close popups and modals
    if (e.which === 27) {
        closePopup();
        $('#deleteConfirmModal, #bulkDeleteConfirmModal').remove();
    }
    
    // Ctrl/Cmd + A to select all
    if ((e.ctrlKey || e.metaKey) && e.which === 65 && !$(e.target).is('input, textarea')) {
        e.preventDefault();
        $('#select-all').prop('checked', true).trigger('change');
        showNotification('Semua foto dipilih', 'info');
    }
});

// Enhanced drag and drop for file uploads
function initializeDragDrop() {
    $(document).on('dragover dragenter', 'input[type="file"]', function(e) {
        e.preventDefault();
        $(this).closest('.form-group, div').addClass('border-blue-400 bg-blue-50');
    });
    
    $(document).on('dragleave dragend drop', 'input[type="file"]', function(e) {
        e.preventDefault();
        $(this).closest('.form-group, div').removeClass('border-blue-400 bg-blue-50');
    });
    
    $(document).on('drop', 'input[type="file"]', function(e) {
        e.preventDefault();
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            this.files = files;
            $(this).trigger('change');
            showNotification('File berhasil dipilih!', 'success');
        }
    });
}

// Initialize enhanced features
$(document).ready(function() {
    initializeDragDrop();
    
    // Add custom CSS animations if not exists
    if (!document.getElementById('galeriAnimations')) {
        const style = document.createElement('style');
        style.id = 'galeriAnimations';
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes scaleIn {
                from { 
                    opacity: 0; 
                    transform: scale(0.9) translateY(-10px); 
                }
                to { 
                    opacity: 1; 
                    transform: scale(1) translateY(0); 
                }
            }
            
            @keyframes slideInRight {
                from { 
                    opacity: 0; 
                    transform: translateX(100%); 
                }
                to { 
                    opacity: 1; 
                    transform: translateX(0); 
                }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }
            
            .animate-scale-in {
                animation: scaleIn 0.3s ease-out;
            }
            
            .animate-slide-in-right {
                animation: slideInRight 0.3s ease-out;
            }
            
            .rotate-3 {
                transform: rotate(3deg);
            }
            
            .line-clamp-1 {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .scale-95 {
                transform: scale(0.95);
            }
            
            .scale-100 {
                transform: scale(1);
            }
            
            .overflow-hidden {
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    }
});

// Export functions for global use
window.galeriAdminUtils = {
    showAddGaleriPopup,
    showEditGaleriPopup,
    editGaleri,
    deleteGaleri,
    bulkDelete,
    filterGaleriItems,
    showNotification,
    updateCharCount,
    validateGaleriForm,
    showPopup,
    closePopup,
    toggleVisibility 
};