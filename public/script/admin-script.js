$(document).ready(function() {
    if (document.getElementById('sejarah-sortable')) {
        new Sortable(document.getElementById('sejarah-sortable'), {
            animation: 200,
            ghostClass: 'opacity-50',
            dragClass: 'rotate-3 scale-105',
            onStart: function(evt) {
                $(evt.item).addClass('shadow-2xl ring-2 ring-blue-400');
            },
            onEnd: function(evt) {
                $(evt.item).removeClass('shadow-2xl ring-2 ring-blue-400');
                
                let items = [];
                $('#sejarah-sortable [data-id]').each(function() {
                    items.push($(this).data('id'));
                });
                
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                
                showNotification('Menyimpan urutan...', 'info');
                
                $.post(window.adminRoutes.sejarahReorder, {
                    _token: csrfToken,
                    items: items
                }).done(function() {
                    showNotification('Urutan berhasil disimpan!', 'success');
                }).fail(function() {
                    showNotification('Gagal menyimpan urutan!', 'error');
                });
            }
        });
    }

    $('.edit-sejarah-btn').click(function() {
        const id = $(this).data('id');
        const alt = $(this).data('alt');
        const urutan = $(this).data('urutan');
        
        showEditSejarahPopup(id, alt, urutan);
    });

    $(document).on('change', 'input[type="file"]', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            const $preview = $(this).closest('.popup-content, form').find('#image_preview, .file-preview');
            
            reader.onload = function(e) {
                if ($preview.length === 0) {
                    $(this).after(`
                        <div class="file-preview mt-3">
                            <img src="${e.target.result}" class="max-w-48 h-32 object-cover rounded-lg shadow-sm border">
                            <p class="text-xs text-gray-500 mt-2">Preview gambar yang akan diupload</p>
                        </div>
                    `);
                } else {
                    $preview.removeClass('hidden').addClass('animate-fade-in');
                    $preview.find('img').attr('src', e.target.result);
                }
            };
            reader.readAsDataURL(file);
        }
    });

    $(document).on('submit', 'form', function(e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        
        if (!validateForm(this)) {
            e.preventDefault();
            return false;
        }
        
        const $deskripsi = $form.find('textarea[maxlength="90"]');
        if ($deskripsi.length && $deskripsi.val().length > 90) {
            e.preventDefault();
            showNotification('Deskripsi tidak boleh lebih dari 90 karakter!', 'error');
            $deskripsi.focus();
            return false;
        }
        
        $submitBtn.prop('disabled', true);
        $submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>');
        
        setTimeout(() => {
            $submitBtn.prop('disabled', false);
            $submitBtn.html('<i class="fas fa-save mr-2"></i>Simpan');
        }, 3000);
    });

    $('textarea[maxlength]').each(function() {
        const counterId = $(this).attr('oninput')?.match(/updateCharCount\(this,\s*'([^']+)'\)/)?.[1];
        if (counterId) {
            updateCharCount(this, counterId);
        }
    });

    $('textarea[maxlength="90"]').on('input', function() {
        const $textarea = $(this);
        const currentLength = $textarea.val().length;
        const maxLength = 90;
        
        if (currentLength > maxLength) {
            $textarea.addClass('border-red-400 ring-red-400');
            $textarea.removeClass('border-gray-200 border-orange-400 ring-orange-400');
            showNotification(`Deskripsi terlalu panjang! ${currentLength - maxLength} karakter berlebih`, 'warning');
        } else if (currentLength > maxLength * 0.8) {
            $textarea.addClass('border-orange-400 ring-orange-400');
            $textarea.removeClass('border-gray-200 border-red-400 ring-red-400');
        } else {
            $textarea.removeClass('border-red-400 ring-red-400 border-orange-400 ring-orange-400');
            $textarea.addClass('border-gray-200');
        }
    });

    $(document).on('click', '#popup-overlay', function(e) {
        if (e.target === this) {
            closePopup();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.which === 27) {
            closePopup();
        }
    });
});

function showPopup(content) {
    const overlay = $('#popup-overlay');
    const contentContainer = $('#popup-content');
    
    contentContainer.html(content);
    overlay.removeClass('hidden');
    
    setTimeout(() => {
        overlay.addClass('opacity-100');
        contentContainer.find('.popup-content').removeClass('scale-95').addClass('scale-100');
    }, 10);
    
    $('body').addClass('overflow-hidden');
}

function closePopup() {
    const overlay = $('#popup-overlay');
    const contentContainer = $('#popup-content');
    
    overlay.removeClass('opacity-100');
    contentContainer.find('.popup-content').removeClass('scale-100').addClass('scale-95');
    
    setTimeout(() => {
        overlay.addClass('hidden');
        contentContainer.html('');
        $('body').removeClass('overflow-hidden');
    }, 300);
}

function deleteSejarahSlider(id) {
    const confirmModal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" id="deleteConfirmModal">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Hapus</h3>
                            <p class="text-gray-600">Yakin ingin menghapus gambar ini?</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan dan gambar akan dihapus permanen dari slider.</p>
                    <div class="flex space-x-3">
                        <button onclick="document.getElementById('deleteConfirmModal').remove()" 
                                class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            Batal
                        </button>
                        <button onclick="confirmDeleteSejarah(${id})" 
                                class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors duration-200">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(confirmModal);
}

function confirmDeleteSejarah(id) {
    const deleteUrl = window.adminRoutes.sejarahDelete.replace(':id', id);

    const form = $('<form>', {
        method: 'POST',
        action: deleteUrl,
        style: 'display:none;'
    });
    
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    form.append($('<input>', {type: 'hidden', name: '_token', value: csrfToken}));
    form.append($('<input>', {type: 'hidden', name: '_method', value: 'DELETE'}));
    
    $('body').append(form);
    
    showNotification('Menghapus gambar...', 'info');
    
    form.submit();
    
    $('#deleteConfirmModal').remove();
}

function showAddSejarahPopup() {
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Tambah Gambar Slider</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${window.adminRoutes.sejarahStore || '/admin/tentang/sejarah'}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <div class="p-6 space-y-6">
                    <div>
                        <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-green-500 mr-2"></i>Pilih Gambar
                        </label>
                        <input type="file" 
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-green-500 transition-colors duration-200" 
                               id="gambar" name="gambar" accept="image/*" required>
                        <p class="text-xs text-gray-500 mt-2">Format yang didukung: JPG, PNG, GIF (Max: 2MB)</p>
                        <div id="image_preview" class="mt-3 hidden">
                            <img src="" class="max-w-48 h-32 object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label for="alt_text" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-blue-500 mr-2"></i>Deskripsi Gambar
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  id="alt_text" name="alt_text" rows="3" maxlength="90"
                                  placeholder="Deskripsi gambar untuk aksesibilitas..."
                                  data-counter="alt_text_count"></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Deskripsi singkat untuk gambar
                            </p>
                            <p class="text-xs" id="alt_text_count">0/90</p>
                        </div>
                    </div>
                    <div>
                        <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-purple-500 mr-2"></i>Urutan Tampilan
                        </label>
                        <input type="number" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" 
                               id="urutan" name="urutan" value="${$('#sejarah-sortable [data-id]').length + 1}" min="1" required>
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
    
    setTimeout(() => {
        updateCharCount(document.getElementById('alt_text'), 'alt_text_count');
    }, 100);
}

function showEditSejarahPopup(id, alt, urutan) {
    const updateUrl = window.adminRoutes.sejarahUpdate.replace(':id', id);
    
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
            <div class="bg-primary-green px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Edit Gambar Slider</h3>
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
                    <div>
                        <label for="edit_gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-amber-500 mr-2"></i>Gambar Baru (opsional)
                        </label>
                        <input type="file" 
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-amber-500 transition-colors duration-200" 
                               id="edit_gambar" name="gambar" accept="image/*">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah gambar</p>
                        <div id="image_preview" class="mt-3 hidden">
                            <img src="" class="max-w-48 h-32 object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label for="edit_alt_text" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-blue-500 mr-2"></i>Deskripsi Gambar
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  id="edit_alt_text" name="alt_text" rows="3" maxlength="90"
                                  data-counter="edit_alt_text_count"
                                  placeholder="Deskripsi gambar untuk aksesibilitas...">${alt || ''}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Deskripsi singkat untuk gambar
                            </p>
                            <p class="text-xs" id="edit_alt_text_count">0/90</p>
                        </div>
                    </div>
                    <div>
                        <label for="edit_urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-purple-500 mr-2"></i>Urutan Tampilan
                        </label>
                        <input type="number" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" 
                               id="edit_urutan" name="urutan" min="1" value="${urutan || 1}" required>
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
    
    setTimeout(() => {
        updateCharCount(document.getElementById('edit_alt_text'), 'edit_alt_text_count');
    }, 100);
}

function openItemModal(type, item = null) {
    if (item) {
        showEditItemPopup(type, item);
    } else {
        showAddItemPopup(type);
    }
}

function showAddItemPopup(type) {
    const titles = {
        'keunikan': 'Keunikan Situs',
        'fasilitas': 'Fasilitas',
        'wisata_sekitar': 'Wisata Sekitar'
    };
    
    const colors = {
        'keunikan': 'from-purple-500 to-indigo-600',
        'fasilitas': 'from-amber-500 to-orange-600',
        'wisata_sekitar': 'from-green-500 to-teal-600'
    };
    
    const count = $(`.${type}-items .card, .${type}-items [class*="col-"]`).length;
    
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
            <div class="bg-gradient-to-r ${colors[type]} px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Tambah ${titles[type]}</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${window.adminRoutes.itemStore}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="tipe" value="${type}">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="item_judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-500 mr-2"></i>Judul
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="item_judul" name="judul" required placeholder="Masukkan judul...">
                        </div>
                        
                        <div>
                            <label for="item_urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sort-numeric-up text-purple-500 mr-2"></i>Urutan
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="item_urutan" name="urutan" min="1" value="${count + 1}" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="item_deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-green-500 mr-2"></i>Deskripsi
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  id="item_deskripsi" name="deskripsi" rows="4" maxlength="90" required 
                                  placeholder="Masukkan deskripsi..."
                                  data-counter="item_deskripsi_count"></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Deskripsi maksimal 90 karakter
                            </p>
                            <p class="text-xs" id="item_deskripsi_count">0/90</p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="item_gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-amber-500 mr-2"></i>Gambar
                        </label>
                        <input type="file" 
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-blue-500 transition-colors duration-200" 
                               id="item_gambar" name="gambar" accept="image/*" required>
                        <div id="image_preview" class="mt-3 hidden">
                            <img src="" class="max-w-48 h-32 object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                    
                    ${type === 'wisata_sekitar' ? `
                    <div>
                        <label for="item_info_tambahan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle text-teal-500 mr-2"></i>Info Tambahan (opsional)
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               id="item_info_tambahan" name="info_tambahan" placeholder="Contoh: lokasi, jarak, dll">
                    </div>
                    ` : ''}
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
    
    setTimeout(() => {
        updateCharCount(document.getElementById('item_deskripsi'), 'item_deskripsi_count');
    }, 100);
}

function showEditItemPopup(type, item) {
    const titles = {
        'keunikan': 'Keunikan Situs',
        'fasilitas': 'Fasilitas',
        'wisata_sekitar': 'Wisata Sekitar'
    };
    
    const colors = {
        'keunikan': 'from-purple-500 to-indigo-600',
        'fasilitas': 'from-amber-500 to-orange-600',
        'wisata_sekitar': 'from-green-500 to-teal-600'
    };
    
    const updateUrl = window.adminRoutes.itemUpdate.replace(':id', item.id);
    
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto popup-content">
            <div class="bg-gradient-to-r ${colors[type]} px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Edit ${titles[type]}</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${updateUrl}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="tipe" value="${type}">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit_item_judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-500 mr-2"></i>Judul
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="edit_item_judul" name="judul" required value="${item.judul || ''}">
                        </div>
                        
                        <div>
                            <label for="edit_item_urutan" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sort-numeric-up text-purple-500 mr-2"></i>Urutan
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="edit_item_urutan" name="urutan" min="1" value="${item.urutan || 1}" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_item_deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-green-500 mr-2"></i>Deskripsi
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  id="edit_item_deskripsi" name="deskripsi" rows="4" maxlength="90" required 
                                  data-counter="edit_item_deskripsi_count"
                                  placeholder="Masukkan deskripsi...">${item.deskripsi || ''}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Deskripsi maksimal 90 karakter
                            </p>
                            <p class="text-xs" id="edit_item_deskripsi_count">0/90</p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_item_gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-amber-500 mr-2"></i>Gambar Baru (opsional)
                        </label>
                        <input type="file" 
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-blue-500 transition-colors duration-200" 
                               id="edit_item_gambar" name="gambar" accept="image/*">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah gambar</p>
                        <div id="current_image_preview" class="mt-3">
                            <div class="relative inline-block">
                                <img src="${item.gambar_url || ''}" class="max-w-48 h-32 object-cover rounded-lg shadow-sm">
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200">
                                    <p class="text-white text-xs text-center px-2">Gambar saat ini<br>Kosongkan jika tidak ingin mengubah</p>
                                </div>
                            </div>
                        </div>
                        <div id="image_preview" class="mt-3 hidden">
                            <img src="" class="max-w-48 h-32 object-cover rounded-lg shadow-sm">
                        </div>
                    </div>
                    
                    ${type === 'wisata_sekitar' ? `
                    <div>
                        <label for="edit_item_info_tambahan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle text-teal-500 mr-2"></i>Info Tambahan (opsional)
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               id="edit_item_info_tambahan" name="info_tambahan" value="${item.info_tambahan || ''}" placeholder="Contoh: lokasi, jarak, dll">
                    </div>
                    ` : ''}
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
    
    setTimeout(() => {
        updateCharCount(document.getElementById('edit_item_deskripsi'), 'edit_item_deskripsi_count');
    }, 100);
}

function editItem(type, itemJson) {
    showEditItemPopup(type, itemJson);
}

function deleteItem(id) {
    const confirmModal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" id="deleteConfirmModal">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Hapus</h3>
                            <p class="text-gray-600">Yakin ingin menghapus item ini?</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button onclick="document.getElementById('deleteConfirmModal').remove()" 
                                class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            Batal
                        </button>
                        <button onclick="confirmDelete(${id})" 
                                class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors duration-200">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(confirmModal);
}

function confirmDelete(id) {
    const deleteUrl = window.adminRoutes.itemDelete.replace(':id', id);

    const form = $('<form>', {
        method: 'POST',
        action: deleteUrl,
        style: 'display:none;'
    });
    
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    form.append($('<input>', {type: 'hidden', name: '_token', value: csrfToken}));
    form.append($('<input>', {type: 'hidden', name: '_method', value: 'DELETE'}));
    
    $('body').append(form);
    
    showNotification('Menghapus item...', 'info');
    
    form.submit();
    
    $('#deleteConfirmModal').remove();
}

function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

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
    
    const timeout = type === 'warning' ? 8000 : 5000;
    setTimeout(() => {
        removeNotification(notificationId);
    }, timeout);
}

function removeNotification(notificationId) {
    $(`#${notificationId}`).fadeOut(300, function() {
        $(this).remove();
    });
}

function updateCharCount(element, counterId) {
    if (!element || !counterId) return;
    
    const maxLength = parseInt(element.getAttribute('maxlength')) || 90;
    const currentLength = element.value.length;
    const counter = document.getElementById(counterId);
    
    if (counter) {
        const remaining = maxLength - currentLength;
        
        let counterClass = 'text-gray-500';
        if (currentLength > maxLength) {
            counterClass = 'text-red-600 font-bold';
        } else if (remaining <= 10) {
            counterClass = 'text-red-500 font-bold';
        } else if (remaining <= 20) {
            counterClass = 'text-orange-500 font-semibold';
        }
        
        counter.innerHTML = `<span class="${counterClass}">${currentLength}</span><span class="text-gray-400">/${maxLength}</span>`;
        
        element.classList.remove('border-red-400', 'ring-red-400', 'border-orange-400', 'ring-orange-400', 'border-gray-200');
        
        if (currentLength > maxLength) {
            element.classList.add('border-red-400', 'ring-red-400');
        } else if (remaining <= 10) {
            element.classList.add('border-orange-400', 'ring-orange-400');
        } else {
            element.classList.add('border-gray-200');
        }
        
        if (currentLength > maxLength && !element.dataset.notificationShown) {
            showNotification(`Deskripsi melebihi batas! ${currentLength - maxLength} karakter berlebih`, 'warning');
            element.dataset.notificationShown = 'true';
            
            setTimeout(() => {
                delete element.dataset.notificationShown;
            }, 3000);
        }
    }
}

$(document).on('input', 'textarea[data-counter]', function() {
    const counterId = $(this).data('counter');
    updateCharCount(this, counterId);
});

function validateForm(form) {
    let isValid = true;
    const errors = [];
    
    const textareas = form.querySelectorAll('textarea[maxlength="90"]');
    textareas.forEach(textarea => {
        const currentLength = textarea.value.length;
        const fieldName = textarea.getAttribute('name') || 'field';
        
        if (currentLength > 90) {
            errors.push(`${ucfirst(fieldName.replace('_', ' '))} tidak boleh lebih dari 90 karakter (saat ini: ${currentLength})`);
            isValid = false;
            if (!textarea.classList.contains('animate-shake')) {
                textarea.classList.add('animate-shake');
                setTimeout(() => textarea.classList.remove('animate-shake'), 600);
            }
        }
        
        if (currentLength < 10 && textarea.hasAttribute('required')) {
            errors.push(`${ucfirst(fieldName.replace('_', ' '))} minimal 10 karakter`);
            isValid = false;
        }
    });
    
    if (!isValid) {
        showNotification(errors.join('. '), 'error');
        const firstInvalid = form.querySelector('textarea.border-red-400');
        if (firstInvalid) {
            firstInvalid.focus();
        }
    }
    
    return isValid;
}

function initializeSearch() {
    const searchInput = $('#searchInput');
    if (searchInput.length) {
        searchInput.on('input', debounce(function() {
            const query = $(this).val().toLowerCase();
            const cards = $('.card-hover');
            
            cards.each(function() {
                const text = $(this).text().toLowerCase();
                if (text.includes(query)) {
                    $(this).removeClass('hidden').addClass('animate-fade-in');
                } else {
                    $(this).addClass('hidden').removeClass('animate-fade-in');
                }
            });
        }, 300));
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

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

$(document).ready(function() {
    initializeSearch();
    initializeDragDrop();
    
    if (!document.getElementById('customAnimations')) {
        const style = document.createElement('style');
        style.id = 'customAnimations';
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
            
            @keyframes scaleOut {
                from { 
                    opacity: 1; 
                    transform: scale(1) translateY(0); 
                }
                to { 
                    opacity: 0; 
                    transform: scale(0.9) translateY(-10px); 
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
            
            @keyframes slideDown {
                from { 
                    opacity: 0; 
                    transform: translateY(-10px); 
                }
                to { 
                    opacity: 1; 
                    transform: translateY(0); 
                }
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }
            
            .animate-scale-in {
                animation: scaleIn 0.3s ease-out;
            }
            
            .animate-scale-out {
                animation: scaleOut 0.3s ease-out;
            }
            
            .animate-slide-in-right {
                animation: slideInRight 0.3s ease-out;
            }
            
            .animate-slide-down {
                animation: slideDown 0.3s ease-out;
            }
            
            .animate-shake {
                animation: shake 0.6s ease-in-out;
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
            
            .btn-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            
            .btn-gradient:hover {
                background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            }
        `;
        document.head.appendChild(style);
    }
    
    $(document).on('mouseenter', '[title]', function() {
        const title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<div class="tooltip-custom bg-gray-800 text-white px-2 py-1 rounded text-sm absolute z-50"></div>')
            .text(title)
            .appendTo('body')
            .fadeIn('fast');
    }).on('mouseleave', '[title], [data-tip-text]', function() {
        $(this).attr('title', $(this).data('tipText'));
        $('.tooltip-custom').remove();
    }).on('mousemove', function(e) {
        $('.tooltip-custom').css({
            top: e.pageY + 10,
            left: e.pageX + 10
        });
    });
});

$(document).keydown(function(e) {
    if ((e.ctrlKey || e.metaKey) && e.which === 83) {
        e.preventDefault();
        const $form = $('.popup-content form:visible, form:visible').first();
        if ($form.length) {
            if (validateForm($form[0])) {
                $form.submit();
                showNotification('Form disimpan dengan shortcut keyboard!', 'success');
            }
        }
    }
    
    if (e.which === 27) {
        closePopup();
        $('#deleteConfirmModal').remove();
    }
});

$(document).ajaxError(function(event, xhr, settings, thrownError) {
    console.error('AJAX Error:', thrownError);
    showNotification('Terjadi kesalahan pada server. Silakan coba lagi.', 'error');
});

$(window).on('load', function() {
    const loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart;
    console.log(`Page loaded in ${loadTime}ms`);
    
    if (loadTime > 3000) {
        console.warn('Page load time is slow. Consider optimizing assets.');
    }
});

function makeTablesResponsive() {
    $('table').each(function() {
        if (!$(this).parent().hasClass('table-responsive')) {
            $(this).wrap('<div class="overflow-x-auto"></div>');
        }
    });
}

$(document).ready(function() {
    makeTablesResponsive();
});

window.adminUtils = {
    showNotification,
    openItemModal,
    editItem,
    deleteItem,
    deleteSejarahSlider,
    confirmDeleteSejarah,
    ucfirst,
    updateCharCount,
    validateForm,
    removeNotification,
    showPopup,
    closePopup,
    showAddSejarahPopup,
    showEditSejarahPopup,
    showAddItemPopup,
    showEditItemPopup
};