$(document).ready(function () {
    $(document).on("change", 'input[type="file"]', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            const previewContainer = $(this)
                .closest(".popup-content, form")
                .find("#image_preview");
            const previewImg = previewContainer.find("img");

            reader.onload = function (e) {
                previewContainer
                    .removeClass("hidden")
                    .addClass("animate-fade-in");
                previewImg.attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $("#select-all").on("change", function () {
        const isChecked = $(this).is(":checked");
        $(".item-checkbox").prop("checked", isChecked);
        updateBulkDeleteButton();
    });

    $(document).on("change", ".item-checkbox", function () {
        updateBulkDeleteButton();
        updateSelectAllState();
    });

    $("#search-input").on("input", function () {
        filterArtikelItems();
    });

    $(document).on("submit", "form", function (e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');

        if (!validateArtikelForm(this)) {
            e.preventDefault();
            return false;
        }

        if (typeof tinymce !== "undefined") {
            tinymce.triggerSave();
        }

        $submitBtn.prop("disabled", true);
        const originalText = $submitBtn.html();
        $submitBtn.html(
            '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...'
        );

        setTimeout(() => {
            $submitBtn.prop("disabled", false);
            $submitBtn.html(originalText);
        }, 5000);
    });

    $(document).on("click", "#popup-overlay", function (e) {
        if (e.target === this) {
            closePopup();
        }
    });

    $(document).on("keydown", function (e) {
        if (e.which === 27) {
            closePopup();
        }
    });
});

function initializeTinyMCE(selector) {
    if (typeof tinymce === "undefined") {
        console.warn("TinyMCE not loaded");
        return;
    }

    tinymce.init({
        selector: selector,
        height: 400,
        menubar: false,
        plugins: [
            "advlist",
            "autolink",
            "lists",
            "link",
            "image",
            "charmap",
            "preview",
            "anchor",
            "searchreplace",
            "visualblocks",
            "code",
            "fullscreen",
            "insertdatetime",
            "media",
            "table",
            "help",
            "wordcount",
        ],
        toolbar:
            "undo redo | blocks | " +
            "bold italic backcolor | alignleft aligncenter " +
            "alignright alignjustify | bullist numlist | " +
            "removeformat | code | help",
        content_style: `
            body { 
                font-family: Georgia, serif; 
                font-size: 16px; 
                line-height: 1.6;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            p { 
                margin: 0 0 1em 0; 
                text-align: justify;
                text-indent: 3em; /* SEMUA paragraf memiliki indent */
            }
            p.no-indent {
                text-indent: 0 !important;
            }
            blockquote p, li p {
                text-indent: 0 !important;
            }
            h1, h2, h3, h4, h5, h6 {
                text-indent: 0 !important;
                font-weight: bold;
                margin-top: 1.5em;
                margin-bottom: 0.5em;
            }
        `,
        formats: {
            "no-indent": {
                selector: "p",
                classes: "no-indent",
            },
        },
        style_formats: [
            {
                title: "Paragraf",
                items: [
                    { title: "Normal (dengan indent)", format: "p" },
                    { title: "Tanpa indent", format: "no-indent" },
                ],
            },
        ],
        setup: function (editor) {
            editor.on("change", function () {
                editor.save();
            });

            editor.ui.registry.addToggleButton("toggleindent", {
                text: "Toggle Indent",
                tooltip: "Aktifkan/Nonaktifkan indent baris pertama",
                onAction: function () {
                    var node = editor.selection.getNode();
                    if (node.tagName === "P") {
                        if (editor.dom.hasClass(node, "no-indent")) {
                            editor.dom.removeClass(node, "no-indent");
                        } else {
                            editor.dom.addClass(node, "no-indent");
                        }
                    }
                },
                onSetup: function (api) {
                    var nodeChangeHandler = function () {
                        var node = editor.selection.getNode();
                        api.setActive(
                            node.tagName === "P" &&
                                editor.dom.hasClass(node, "no-indent")
                        );
                    };
                    editor.on("NodeChange", nodeChangeHandler);
                    return function () {
                        editor.off("NodeChange", nodeChangeHandler);
                    };
                },
            });
        },
        toolbar:
            "undo redo | blocks | " +
            "bold italic backcolor | alignleft aligncenter " +
            "alignright alignjustify | bullist numlist | " +
            "toggleindent | removeformat | code | help",
        language: "id",

        keep_styles: false,

        paste_preprocess: function (plugin, args) {
            args.content = args.content.replace(/text-indent:[^;]*;?/g, "");
        },
    });
}

function destroyTinyMCE() {
    if (typeof tinymce !== "undefined") {
        tinymce.remove();
    }
}

function showAddArtikelPopup() {
    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[95vh] overflow-y-auto popup-content">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Tambah Artikel Baru</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${
                window.adminArtikelRoutes.store
            }" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$(
                    'meta[name="csrf-token"]'
                ).attr("content")}">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-green-500 mr-2"></i>Judul Artikel
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" 
                                   id="judul" name="judul" required placeholder="Masukkan judul artikel...">
                        </div>
                        
                        <div>
                            <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-amber-500 mr-2"></i>Upload Gambar
                            </label>
                            <input type="file" 
                                   class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-green-500 transition-colors duration-200" 
                                   id="gambar" name="gambar" accept="image/*" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-500 mr-2"></i>Tanggal Publikasi
                            </label>
                            <input type="datetime-local" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" 
                                   id="published_at" name="published_at" value="${new Date()
                                       .toISOString()
                                       .slice(0, 16)}">
                        </div>
                        
                        <div class="flex items-end">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           class="w-5 h-5 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                    <label for="is_featured" class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-star text-yellow-500 mr-2"></i>Jadikan Artikel Utama
                                    </label>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="is_visible" name="is_visible" value="1" checked
                                           class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                    <label for="is_visible" class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-eye text-green-500 mr-2"></i>Tampilkan di Halaman Artikel
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="isi_konten" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-purple-500 mr-2"></i>Isi Konten
                        </label>
                        <textarea class="w-full" 
                                  id="isi_konten" name="isi_konten" required
                                  placeholder="Masukkan isi artikel..."></textarea>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Gunakan toolbar editor untuk formatting text
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mt-2">Format gambar yang didukung: JPG, PNG, WEBP (Max: 5MB)</p>
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
                        <i class="fas fa-save mr-2"></i>Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    `;

    showPopup(popupContent);

    setTimeout(() => {
        initializeTinyMCE("#isi_konten");
    }, 100);
}

function showEditArtikelPopup(item) {
    const updateUrl = window.adminArtikelRoutes.update.replace(":id", item.id);

    const publishedDate = new Date(item.published_at)
        .toISOString()
        .slice(0, 16);

    const popupContent = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[95vh] overflow-y-auto popup-content">
            <div class="bg-primary-green px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Edit Artikel</h3>
                    </div>
                    <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors duration-200" onclick="closePopup()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="${updateUrl}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${$(
                    'meta[name="csrf-token"]'
                ).attr("content")}">
                <input type="hidden" name="_method" value="PUT">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit_judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-amber-500 mr-2"></i>Judul Artikel
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" 
                                   id="edit_judul" name="judul" required value="${
                                       item.judul || ""
                                   }">
                        </div>
                        
                        <div>
                            <label for="edit_gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-amber-500 mr-2"></i>Gambar Baru (opsional)
                            </label>
                            <input type="file" 
                                   class="w-full px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl focus:border-amber-500 transition-colors duration-200" 
                                   id="edit_gambar" name="gambar" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit_published_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-500 mr-2"></i>Tanggal Publikasi
                            </label>
                            <input type="datetime-local" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200" 
                                   id="edit_published_at" name="published_at" value="${publishedDate}">
                        </div>
                        
                        <div class="flex items-end">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="edit_is_featured" name="is_featured" value="1" 
                                           ${item.is_featured ? "checked" : ""}
                                           class="w-5 h-5 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                    <label for="edit_is_featured" class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-star text-yellow-500 mr-2"></i>Jadikan Artikel Utama
                                    </label>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="edit_is_visible" name="is_visible" value="1" 
                                           ${item.is_visible ? "checked" : ""}
                                           class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                    <label for="edit_is_visible" class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-eye text-green-500 mr-2"></i>Tampilkan di Halaman Artikel
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_isi_konten" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-purple-500 mr-2"></i>Isi Konten
                        </label>
                        <textarea class="w-full" 
                                  id="edit_isi_konten" name="isi_konten" required>${
                                      item.isi_konten || ""
                                  }</textarea>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Gunakan toolbar editor untuk formatting text
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah gambar</p>
                        <div id="current_image_preview" class="mt-3">
                            <div class="relative inline-block">
                                <img src="${
                                    item.gambar_url || ""
                                }" class="max-w-64 h-40 object-cover rounded-lg shadow-sm">
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200">
                                    <p class="text-white text-xs text-center px-2">Gambar saat ini<br>Kosongkan jika tidak ingin mengubah</p>
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
                        <i class="fas fa-save mr-2"></i>Update Artikel
                    </button>
                </div>
            </form>
        </div>
    `;

    showPopup(popupContent);

    setTimeout(() => {
        initializeTinyMCE("#edit_isi_konten");
    }, 100);
}

function showPopup(content) {
    const overlay = $("#popup-overlay");
    const contentContainer = $("#popup-content");

    contentContainer.html(content);
    overlay.removeClass("hidden");

    setTimeout(() => {
        overlay.addClass("opacity-100");
        contentContainer
            .find(".popup-content")
            .removeClass("scale-95")
            .addClass("scale-100");
    }, 10);

    $("body").addClass("overflow-hidden");
}

function closePopup() {
    const overlay = $("#popup-overlay");
    const contentContainer = $("#popup-content");

    destroyTinyMCE();

    overlay.removeClass("opacity-100");
    contentContainer
        .find(".popup-content")
        .removeClass("scale-100")
        .addClass("scale-95");

    setTimeout(() => {
        overlay.addClass("hidden");
        contentContainer.html("");
        $("body").removeClass("overflow-hidden");
    }, 300);
}

function deleteArtikel(id) {
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
                            <p class="text-gray-600">Yakin ingin menghapus artikel ini?</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button onclick="document.getElementById('deleteConfirmModal').remove()" 
                                class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            Batal
                        </button>
                        <button onclick="confirmDeleteArtikel(${id})" 
                                class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors duration-200">
                            Ya, Hapus Artikel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    $("body").append(confirmModal);
}

function confirmDeleteArtikel(id) {
    const deleteUrl = window.adminArtikelRoutes.delete.replace(":id", id);
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    const form = $("<form>", {
        method: "POST",
        action: deleteUrl,
        style: "display:none;",
    });

    form.append(
        $("<input>", { type: "hidden", name: "_token", value: csrfToken })
    );
    form.append(
        $("<input>", { type: "hidden", name: "_method", value: "DELETE" })
    );

    $("body").append(form);

    showNotification("Menghapus artikel...", "info");
    form.submit();

    $("#deleteConfirmModal").remove();
}

function bulkDelete() {
    const selectedIds = [];
    $(".item-checkbox:checked").each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        showNotification("Pilih minimal satu artikel untuk dihapus", "warning");
        return;
    }

    const count = selectedIds.length;

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
                            <p class="text-gray-600">Yakin ingin menghapus ${count} artikel?</p>
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
                            Ya, Hapus ${count} Artikel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    $("body").append(confirmModal);
}

function confirmBulkDelete() {
    const selectedIds = [];
    $(".item-checkbox:checked").each(function () {
        selectedIds.push($(this).val());
    });

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    const form = $("<form>", {
        method: "POST",
        action: window.adminArtikelRoutes.bulkDelete,
        style: "display:none;",
    });

    form.append(
        $("<input>", { type: "hidden", name: "_token", value: csrfToken })
    );

    selectedIds.forEach((id) => {
        form.append($("<input>", { type: "hidden", name: "ids[]", value: id }));
    });

    $("body").append(form);

    showNotification(`Menghapus ${selectedIds.length} artikel...`, "info");
    form.submit();

    $("#bulkDeleteConfirmModal").remove();
}

function toggleFeatured(id, setFeatured) {
    const toggleUrl = window.adminArtikelRoutes.toggleFeatured.replace(
        ":id",
        id
    );
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    const form = $("<form>", {
        method: "POST",
        action: toggleUrl,
        style: "display:none;",
    });

    form.append(
        $("<input>", { type: "hidden", name: "_token", value: csrfToken })
    );
    form.append(
        $("<input>", { type: "hidden", name: "_method", value: "PUT" })
    );

    $("body").append(form);

    const message =
        setFeatured === "true"
            ? "Mengubah status featured..."
            : "Menghapus status featured...";
    showNotification(message, "info");
    form.submit();
}

function toggleVisibility(id) {
    const url = window.adminArtikelRoutes.toggleVisibility.replace(':id', id);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken,
        },
        success: function(data) {
            if (data.success) {
                showNotification(data.message, 'success');

                const $itemElement = $(`.artikel-item[data-id='${id}']`);
                const $statusBadge = $itemElement.find('.status-badge');
                const $toggleButton = $itemElement.find(`button[onclick="toggleVisibility(${id})"]`);

                if (data.is_visible) {
                    $itemElement.removeClass('opacity-60');
                    
                    $statusBadge.text('Ditampilkan').removeClass('bg-gray-500').addClass('bg-green-500');
                    
                    $toggleButton.removeClass('bg-green-100 hover:bg-green-200 text-green-800')
                                 .addClass('bg-yellow-100 hover:bg-yellow-200 text-yellow-800');
                    $toggleButton.html('<i class="fas fa-eye-slash mr-1"></i>Sembunyikan');

                } else {
                    $itemElement.addClass('opacity-60');

                    $statusBadge.text('Disembunyikan').removeClass('bg-green-500').addClass('bg-gray-500');
                    
                    $toggleButton.removeClass('bg-yellow-100 hover:bg-yellow-200 text-yellow-800')
                                 .addClass('bg-green-100 hover:bg-green-200 text-green-800');
                    $toggleButton.html('<i class="fas fa-eye mr-1"></i>Tampilkan');
                }

                if (data.totalVisible !== undefined && data.totalHidden !== undefined) {
                    $('#total-visible-count').text(data.totalVisible);
                    $('#total-hidden-count').text(data.totalHidden);
                }
                
            } else {
                showNotification(data.message || 'Gagal mengubah status visibilitas.', 'error');
            }
        },
        error: function() {
            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    });
}

function updateBulkDeleteButton() {
    const selectedCount = $(".item-checkbox:checked").length;
    const $bulkBtn = $("#bulk-delete-btn");

    if (selectedCount > 0) {
        $bulkBtn.removeClass("hidden").addClass("animate-fade-in");
        $bulkBtn.find("span").text(`Hapus ${selectedCount} Terpilih`);
    } else {
        $bulkBtn.addClass("hidden").removeClass("animate-fade-in");
    }
}

function updateSelectAllState() {
    const totalCheckboxes = $(".item-checkbox").length;
    const checkedCheckboxes = $(".item-checkbox:checked").length;
    const $selectAll = $("#select-all");

    if (checkedCheckboxes === 0) {
        $selectAll.prop("indeterminate", false);
        $selectAll.prop("checked", false);
    } else if (checkedCheckboxes === totalCheckboxes) {
        $selectAll.prop("indeterminate", false);
        $selectAll.prop("checked", true);
    } else {
        $selectAll.prop("indeterminate", true);
    }
}

function filterArtikelItems() {
    const searchQuery = $("#search-input").val().toLowerCase();

    $(".artikel-item").each(function () {
        const $item = $(this);
        const itemSearch = $item.data("search");

        let showItem = true;

        if (searchQuery && !itemSearch.includes(searchQuery)) {
            showItem = false;
        }

        if (showItem) {
            $item.removeClass("hidden").addClass("animate-fade-in");
        } else {
            $item.addClass("hidden").removeClass("animate-fade-in");
        }
    });

    const visibleItems = $(".artikel-item:not(.hidden)").length;
    if (visibleItems === 0) {
        if ($("#no-results").length === 0) {
            $("#artikel-container").append(`
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
        $("#no-results").remove();
    }
}

function validateArtikelForm(form) {
    let isValid = true;
    const errors = [];

    const requiredFields = form.querySelectorAll("[required]");
    requiredFields.forEach((field) => {
        if (!field.value.trim()) {
            const fieldName =
                field.getAttribute("name") || field.getAttribute("id");
            errors.push(`${ucfirst(fieldName.replace("_", " "))} wajib diisi`);
            isValid = false;
            field.classList.add("border-red-400", "ring-red-400");
        } else {
            field.classList.remove("border-red-400", "ring-red-400");
        }
    });

    const editorId = form
        .querySelector('textarea[name="isi_konten"]')
        ?.getAttribute("id");
    if (editorId && typeof tinymce !== "undefined") {
        const editor = tinymce.get(editorId);
        if (editor && editor.getContent().trim().length < 50) {
            errors.push("Isi konten minimal 50 karakter");
            isValid = false;
        }
    }

    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput && fileInput.files.length > 0) {
        const file = fileInput.files[0];

        if (file.size > maxSize) {
            errors.push("Ukuran file tidak boleh lebih dari 5MB");
            isValid = false;
        }

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/jpg",
            "image/webp",
        ];
        if (!allowedTypes.includes(file.type)) {
            errors.push("Format file harus JPG, PNG, atau WEBP");
            isValid = false;
        }
    }

    if (!isValid) {
        showNotification(errors.join(". "), "error");
        const firstInvalid = form.querySelector(".border-red-400");
        if (firstInvalid) {
            firstInvalid.focus();
        }
    }

    return isValid;
}

function showNotification(message, type = "info") {
    const icons = {
        success: "fas fa-check-circle",
        error: "fas fa-exclamation-circle",
        info: "fas fa-info-circle",
        warning: "fas fa-exclamation-triangle",
    };

    const colors = {
        success: "bg-green-50 border-green-200 text-green-800",
        error: "bg-red-50 border-red-200 text-red-800",
        info: "bg-blue-50 border-blue-200 text-blue-800",
        warning: "bg-yellow-50 border-yellow-200 text-yellow-800",
    };

    const iconColors = {
        success: "text-green-600",
        error: "text-red-600",
        info: "text-blue-600",
        warning: "text-yellow-600",
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

    $("body").append(notification);

    const timeout = type === "warning" ? 8000 : 5000;
    setTimeout(() => {
        removeNotification(notificationId);
    }, timeout);
}

function removeNotification(notificationId) {
    $(`#${notificationId}`).fadeOut(300, function () {
        $(this).remove();
    });
}

function ucfirst(str) {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1);
}

$(document).keydown(function (e) {
    if ((e.ctrlKey || e.metaKey) && e.which === 83) {
        e.preventDefault();
        const $form = $(".popup-content form:visible").first();
        if ($form.length) {
            if (typeof tinymce !== "undefined") {
                tinymce.triggerSave();
            }

            if (validateArtikelForm($form[0])) {
                $form.submit();
                showNotification(
                    "Form disimpan dengan shortcut keyboard!",
                    "success"
                );
            }
        }
    }

    if (e.which === 27) {
        closePopup();
        $("#deleteConfirmModal, #bulkDeleteConfirmModal").remove();
    }

    if (
        (e.ctrlKey || e.metaKey) &&
        e.which === 65 &&
        !$(e.target).is("input, textarea")
    ) {
        e.preventDefault();
        $("#select-all").prop("checked", true).trigger("change");
        showNotification("Semua artikel dipilih", "info");
    }
});

function initializeDragDrop() {
    $(document).on("dragover dragenter", 'input[type="file"]', function (e) {
        e.preventDefault();
        $(this)
            .closest(".form-group, div")
            .addClass("border-green-400 bg-green-50");
    });

    $(document).on(
        "dragleave dragend drop",
        'input[type="file"]',
        function (e) {
            e.preventDefault();
            $(this)
                .closest(".form-group, div")
                .removeClass("border-green-400 bg-green-50");
        }
    );

    $(document).on("drop", 'input[type="file"]', function (e) {
        e.preventDefault();
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            this.files = files;
            $(this).trigger("change");
            showNotification("File berhasil dipilih!", "success");
        }
    });
}

$(document).ready(function () {
    initializeDragDrop();

    if (!document.querySelector('script[src*="tinymce"]')) {
        const script = document.createElement("script");
        script.src =
            "https://cdn.tiny.cloud/1/ayawtiwyb62qnm0he2qcgukk953gqbugwhqbubxutll7agpp/tinymce/6/tinymce.min.js";
        script.referrerPolicy = "origin";
        document.head.appendChild(script);
    }

    if (!document.getElementById("artikelAnimations")) {
        const style = document.createElement("style");
        style.id = "artikelAnimations";
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
            
            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
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
            
            /* TinyMCE Custom Styles */
            .tox-tinymce {
                border-radius: 12px !important;
                border: 1px solid #e5e7eb !important;
            }
            
            .tox-toolbar {
                border-bottom: 1px solid #e5e7eb !important;
            }
            
            .tox .tox-edit-area {
                border: none !important;
            }
            
            .tox .tox-statusbar {
                border-top: 1px solid #e5e7eb !important;
                border-radius: 0 0 12px 12px !important;
            }
        `;
        document.head.appendChild(style);
    }
});

window.artikelAdminUtils = {
    showAddArtikelPopup,
    showEditArtikelPopup,
    deleteArtikel,
    bulkDelete,
    toggleFeatured,
    toggleVisibility,
    filterArtikelItems,
    showNotification,
    validateArtikelForm,
    showPopup,
    closePopup,
    initializeTinyMCE,
    destroyTinyMCE,
};
