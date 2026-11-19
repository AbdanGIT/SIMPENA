document.addEventListener("DOMContentLoaded", function () {

    // 1. FILTER DROPDOWN
    window.toggleFilter = function() {
        var menu = document.getElementById("filterMenu");
        if (menu) {
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }
    };

    window.onclick = function(event) {
        if (!event.target.matches('#filterDropdown') && !event.target.closest('#filterDropdown')) {
            var menu = document.getElementById("filterMenu");
            if (menu) menu.style.display = "none";
        }
    };

    // 2. FILTER KATEGORI
    document.querySelectorAll(".filter-btn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            var filterValue = this.getAttribute("data-filter");
            document.querySelectorAll("#tabelArsip tbody tr.data-row").forEach(function(row) {
                var kategoriText = row.querySelector(".kategori-text") ? row.querySelector(".kategori-text").textContent.trim() : "";
                row.style.display = (filterValue === "all" || kategoriText === filterValue) ? "" : "none";
            });
            document.getElementById("filterMenu").style.display = "none";
        });
    });

    // 3. PENCARIAN
    var searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function() {
            var value = this.value.toLowerCase();
            document.querySelectorAll("#tabelArsip tbody tr.data-row").forEach(function(row) {
                row.style.display = (row.textContent.toLowerCase().indexOf(value) > -1) ? "" : "none";
            });
        });
    }

    // 4. BUKA MODAL EDIT
    document.querySelectorAll(".edit-btn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("editId").value = this.getAttribute("data-id");
            document.getElementById("editNama").value = this.getAttribute("data-nama");
            document.getElementById("editKategori").value = this.getAttribute("data-kategori");
            document.getElementById("editDeskripsi").value = this.getAttribute("data-deskripsi");
            document.getElementById("editTanggal").value = this.getAttribute("data-tanggal");
            
            document.getElementById("modalEdit").classList.add("in");
            document.getElementById("modalEdit").style.display = "block";
        });
    });

    // 5. TUTUP MODAL
    document.querySelectorAll("[data-dismiss='modal']").forEach(function(btn) {
        btn.addEventListener("click", function() {
            var modal = this.closest(".modal");
            if (modal) { modal.classList.remove("in"); modal.style.display = "none"; }
        });
    });

    // 6. SIMPAN TAMBAH (AJAX)
    var saveTambah = document.getElementById("saveTambah");
    if (saveTambah) {
        saveTambah.addEventListener("click", function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append(window.csrfName, window.csrfHash);
            formData.append('addNama', document.getElementById("addNama").value);
            formData.append('addKategori', document.getElementById("addKategori").value);
            formData.append('addDeskripsi', document.getElementById("addDeskripsi").value);
            formData.append('addTanggal', document.getElementById("addTanggal").value);
            
            var fileDok = document.getElementById("addFile").files[0];
            if (fileDok) formData.append('addFile', fileDok);

            fetch(window.urlArsipSimpan, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(() => { 
                document.getElementById("modalTambah").classList.remove("in");
                document.getElementById("modalTambah").style.display = "none";
                alert("Dokumen Berhasil Diarsipkan!"); 
                location.reload(); 
            })
            .catch(() => alert("Gagal menyimpan dokumen."));
        });
    }

    // 7. SIMPAN EDIT (AJAX)
    var saveEdit = document.getElementById("saveEdit");
    if (saveEdit) {
        saveEdit.addEventListener("click", function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append(window.csrfName, window.csrfHash);
            formData.append('id_arsip', document.getElementById("editId").value);
            formData.append('addNama', document.getElementById("editNama").value);
            formData.append('addKategori', document.getElementById("editKategori").value);
            formData.append('addDeskripsi', document.getElementById("editDeskripsi").value);
            formData.append('addTanggal', document.getElementById("editTanggal").value);
            
            var fileDok = document.getElementById("editFile").files[0];
            if (fileDok) formData.append('editFile', fileDok);

            fetch(window.urlArsipUpdate, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(() => { 
                document.getElementById("modalEdit").classList.remove("in");
                document.getElementById("modalEdit").style.display = "none";
                alert("Dokumen Berhasil Diupdate!"); 
                location.reload(); 
            })
            .catch(() => alert("Gagal update dokumen."));
        });
    }

    // 8. HAPUS DOKUMEN
    document.querySelectorAll(".delete-btn").forEach(function(btn) {
        btn.addEventListener("click", function() {
            if (confirm("Hapus dokumen ini?")) {
                var id = this.getAttribute("data-id");
                fetch(window.urlArsipHapus + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': window.csrfHash, 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.status !== 'success') { alert(res.message); return; }
                        location.reload();
                    })
                    .catch(() => alert('Gagal menghapus dokumen.'));
            }
        });
    });
});

// FUNGSI BUKA MODAL TAMBAH
function bukaModalTambah() {
    document.getElementById("formTambah").reset();
    var modal = document.getElementById("modalTambah");
    modal.classList.add("in");
    modal.style.display = "block";
    document.body.classList.add("modal-open");
    var backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop fade in";
    backdrop.id = "backdropTambah";
    document.body.appendChild(backdrop);
}