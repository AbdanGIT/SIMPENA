document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // FUNGSI OTOMATIS MENGISI DESKRIPSI
    // ==========================================
    window.isiDeskripsi = function(selectId, descId) {
        var selectElement = document.getElementById(selectId);
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var deskripsi = selectedOption.getAttribute('data-desc');
        
        if (deskripsi) {
            document.getElementById(descId).value = deskripsi;
        } else {
            document.getElementById(descId).value = '';
        }
    };

    // ==========================================
    // 1. FILTER DROPDOWN
    // ==========================================
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

    // ==========================================
    // 2. FUNGSI FILTER & PENCARIAN
    // ==========================================
    document.querySelectorAll(".filter-btn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            var filterValue = this.getAttribute("data-filter");
            document.querySelectorAll("#tabelPengadaan tbody tr.data-row").forEach(function(row) {
                var statusText = row.querySelector(".status-text") ? row.querySelector(".status-text").textContent.trim() : "";
                row.style.display = (filterValue === "all" || statusText === filterValue) ? "" : "none";
            });
            document.getElementById("filterMenu").style.display = "none";
        });
    });

    var searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function() {
            var value = this.value.toLowerCase();
            document.querySelectorAll("#tabelPengadaan tbody tr.data-row").forEach(function(row) {
                row.style.display = (row.textContent.toLowerCase().indexOf(value) > -1) ? "" : "none";
            });
        });
    }

    // ==========================================
    // 3. HITUNG STOCK AKTUAL OTOMATIS
    // ==========================================
    function hitungAktual(awalId, inId, outId, targetId) {
        var awal = parseInt(document.getElementById(awalId).value) || 0;
        var masuk = parseInt(document.getElementById(inId).value) || 0;
        var keluar = parseInt(document.getElementById(outId).value) || 0;
        document.getElementById(targetId).value = (awal + masuk) - keluar;
    }

    ['addAwal', 'addIncoming', 'addOutgoing'].forEach(function(id) {
        var el = document.getElementById(id);
        if(el) el.addEventListener('input', function() { hitungAktual('addAwal', 'addIncoming', 'addOutgoing', 'addAktual'); });
    });
    ['editAwal', 'editIncoming', 'editOutgoing'].forEach(function(id) {
        var el = document.getElementById(id);
        if(el) el.addEventListener('input', function() { hitungAktual('editAwal', 'editIncoming', 'editOutgoing', 'editAktual'); });
    });

    // ==========================================
    // 4. FUNGSI EDIT & TUTUP MODAL
    // ==========================================
    document.querySelectorAll(".edit-btn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("editId").value = this.getAttribute("data-id");
            document.getElementById("editPart").value = this.getAttribute("data-part");
            document.getElementById("editDesc").value = this.getAttribute("data-desc");
            document.getElementById("editAwal").value = this.getAttribute("data-awal");
            document.getElementById("editIncoming").value = this.getAttribute("data-incoming");
            document.getElementById("editOutgoing").value = this.getAttribute("data-outgoing");
            document.getElementById("editAktual").value = this.getAttribute("data-aktual");
            document.getElementById("editStatus").value = this.getAttribute("data-status");
            document.getElementById("editDokumentasiLama").value = this.getAttribute("data-dok");
            
            document.getElementById("modalEdit").classList.add("in");
            document.getElementById("modalEdit").style.display = "block";
        });
    });

    document.querySelectorAll("[data-dismiss='modal']").forEach(function(btn) {
        btn.addEventListener("click", function() {
            var modal = this.closest(".modal");
            if (modal) { modal.classList.remove("in"); modal.style.display = "none"; }
        });
    });

    // ==========================================
    // 5. SIMPAN TAMBAH (AJAX)
    // ==========================================
    var saveTambahBtn = document.getElementById("saveTambah");
    if (saveTambahBtn) {
        saveTambahBtn.addEventListener("click", function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append(window.csrfName, window.csrfHash);

            formData.append('addPart', document.getElementById("addPart").value);
            formData.append('addDesc', document.getElementById("addDesc").value);
            formData.append('addAwal', document.getElementById("addAwal").value);
            formData.append('addIncoming', document.getElementById("addIncoming").value);
            formData.append('addOutgoing', document.getElementById("addOutgoing").value);
            formData.append('addAktual', document.getElementById("addAktual").value);
            formData.append('addStatus', document.getElementById("addStatus").value);
            
            var fileDok = document.getElementById("addDokumentasi").files[0];
            if (fileDok) formData.append('addDokumentasi', fileDok);

            fetch(window.urlSimpanPengadaan, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') { alert(data.message); return; }
                document.getElementById("modalTambah").classList.remove("in");
                document.getElementById("modalTambah").style.display = "none";
                alert(data.message);
                location.reload();
            })
            .catch(err => alert("Gagal menyimpan data: " + err.message));
        });
    }

    // ==========================================
    // 6. SIMPAN EDIT (AJAX)
    // ==========================================
    var saveEditBtn = document.getElementById("saveEdit");
    if (saveEditBtn) {
        saveEditBtn.addEventListener("click", function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append(window.csrfName, window.csrfHash);

            formData.append('id_pengadaan', document.getElementById("editId").value);
            formData.append('editPart', document.getElementById("editPart").value);
            formData.append('editDesc', document.getElementById("editDesc").value);
            formData.append('editAwal', document.getElementById("editAwal").value);
            formData.append('editIncoming', document.getElementById("editIncoming").value);
            formData.append('editOutgoing', document.getElementById("editOutgoing").value);
            formData.append('editAktual', document.getElementById("editAktual").value);
            formData.append('editStatus', document.getElementById("editStatus").value);
            formData.append('editDokumentasiLama', document.getElementById("editDokumentasiLama").value);
            
            var fileDok = document.getElementById("editDokumentasi").files[0];
            if (fileDok) formData.append('editDokumentasi', fileDok);

            fetch(window.urlUpdatePengadaan, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') { alert(data.message); return; }
                document.getElementById("modalEdit").classList.remove("in");
                document.getElementById("modalEdit").style.display = "none";
                alert(data.message);
                location.reload();
            })
            .catch(err => alert("Gagal update data: " + err.message));
        });
    }

    // ==========================================
    // 7. HAPUS DATA UTAMA
    // ==========================================
    document.querySelectorAll(".delete-btn").forEach(function(btn) {
        btn.addEventListener("click", function() {
            if (confirm("Hapus data ini?")) {
                var id = this.getAttribute("data-id");
                fetch(window.urlHapusPengadaan + id, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': window.csrfHash, 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') { alert(data.message); return; }
                location.reload();
            })
            .catch(() => { alert("Gagal menghapus data."); });
            }
        });
    });

    // ==========================================
    // 8. CRUD INCOMING
    // ==========================================
    document.querySelectorAll(".edit-inc").forEach(function(btn) {
        btn.addEventListener("click", function() {
            document.getElementById("editIncId").value = this.getAttribute("data-id");
            document.getElementById("editIncPart").value = this.getAttribute("data-part");
            document.getElementById("editIncDesc").value = this.getAttribute("data-desc");
            document.getElementById("editIncQty").value = this.getAttribute("data-qty");
            document.getElementById("editIncTgl").value = this.getAttribute("data-tgl");
            document.getElementById("editIncStatus").value = this.getAttribute("data-status");
            document.getElementById("modalEditInc").classList.add("in");
            document.getElementById("modalEditInc").style.display = "block";
        });
    });

    var saveInc = document.getElementById("saveInc");
    if(saveInc) {
        saveInc.addEventListener("click", function(e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append(window.csrfName, window.csrfHash);
            fd.append('addIncPart', document.getElementById("addIncPart").value);
            fd.append('addIncDesc', document.getElementById("addIncDesc").value);
            fd.append('addIncQty', document.getElementById("addIncQty").value);
            fd.append('addIncTgl', document.getElementById("addIncTgl").value);
            fd.append('addIncStatus', document.getElementById("addIncStatus").value);
            
            fetch(window.urlSimpanInc, { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') { alert(data.message); return; }
                    alert(data.message);
                    location.reload();
                })
                .catch(() => { alert("Gagal Tambah Incoming."); });
        });
    }

    var saveEditInc = document.getElementById("saveEditInc");
    if(saveEditInc) {
        saveEditInc.addEventListener("click", function(e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append(window.csrfName, window.csrfHash);
            fd.append('editIncId', document.getElementById("editIncId").value);
            fd.append('editIncPart', document.getElementById("editIncPart").value);
            fd.append('editIncDesc', document.getElementById("editIncDesc").value);
            fd.append('editIncQty', document.getElementById("editIncQty").value);
            fd.append('editIncTgl', document.getElementById("editIncTgl").value);
            fd.append('editIncStatus', document.getElementById("editIncStatus").value);
            
            fetch(window.urlUpdateInc, { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') { alert(data.message); return; }
                    alert(data.message);
                    location.reload();
                })
                .catch(() => { alert("Gagal Update Incoming."); });
        });
    }

    document.querySelectorAll(".hapus-inc").forEach(function(btn) {
        btn.addEventListener("click", function() {
            if(confirm("Hapus data ini?")) {
                fetch(window.urlHapusInc + this.getAttribute("data-id"), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': window.csrfHash, 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status !== 'success') { alert(data.message); return; }
                        location.reload();
                    });
            }
        });
    });

    // ==========================================
    // 9. CRUD OUTGOING
    // ==========================================
    document.querySelectorAll(".edit-out").forEach(function(btn) {
        btn.addEventListener("click", function() {
            document.getElementById("editOutId").value = this.getAttribute("data-id");
            document.getElementById("editOutPart").value = this.getAttribute("data-part");
            document.getElementById("editOutDesc").value = this.getAttribute("data-desc");
            document.getElementById("editOutQty").value = this.getAttribute("data-qty");
            document.getElementById("editOutTgl").value = this.getAttribute("data-tgl");
            document.getElementById("editOutDist").value = this.getAttribute("data-dist");
            document.getElementById("editOutStatus").value = this.getAttribute("data-status"); 
            document.getElementById("modalEditOut").classList.add("in");
            document.getElementById("modalEditOut").style.display = "block";
        });
    });

    var saveOutBtn = document.getElementById("saveOut");
    if(saveOutBtn) {
        saveOutBtn.addEventListener("click", function(e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append(window.csrfName, window.csrfHash);
            fd.append('addOutPart', document.getElementById("addOutPart").value);
            fd.append('addOutDesc', document.getElementById("addOutDesc").value);
            fd.append('addOutQty', document.getElementById("addOutQty").value);
            fd.append('addOutTgl', document.getElementById("addOutTgl").value);
            fd.append('addOutDist', document.getElementById("addOutDist").value);
            fd.append('addOutStatus', document.getElementById("addOutStatus").value);
            
            fetch(window.urlSimpanOut, { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') { alert(data.message); return; }
                    alert(data.message);
                    location.reload();
                })
                .catch(error => { alert("Gagal Tambah Outgoing."); });
        });
    }

    var saveEditOutBtn = document.getElementById("saveEditOut");
    if(saveEditOutBtn) {
        saveEditOutBtn.addEventListener("click", function(e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append(window.csrfName, window.csrfHash);
            fd.append('editOutId', document.getElementById("editOutId").value);
            fd.append('editOutPart', document.getElementById("editOutPart").value);
            fd.append('editOutDesc', document.getElementById("editOutDesc").value);
            fd.append('editOutQty', document.getElementById("editOutQty").value);
            fd.append('editOutTgl', document.getElementById("editOutTgl").value);
            fd.append('editOutDist', document.getElementById("editOutDist").value);
            fd.append('editOutStatus', document.getElementById("editOutStatus").value);
            
            fetch(window.urlUpdateOut, { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') { alert(data.message); return; }
                    alert(data.message);
                    location.reload();
                })
                .catch(error => { alert("Gagal Update Outgoing."); });
        });
    }

    document.querySelectorAll(".hapus-out").forEach(function(btn) {
        btn.addEventListener("click", function() {
            if(confirm("Hapus data ini?")) {
                fetch(window.urlHapusOut + this.getAttribute("data-id"), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': window.csrfHash, 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status !== 'success') { alert(data.message); return; }
                        location.reload();
                    });
            }
        });
    });
});

// FUNGSI BUKA MODAL TAMBAH PENGADAAN
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

// FUNGSI BUKA MODAL INCOMING/OUTGOING MANUAL (jika tidak pakai Bootstrap)
function bukaModalTambahInc() {
    document.getElementById("modalTambahInc").classList.add("in");
    document.getElementById("modalTambahInc").style.display = "block";
}

function bukaModalTambahOut() {
    document.getElementById("modalTambahOut").classList.add("in");
    document.getElementById("modalTambahOut").style.display = "block";
}