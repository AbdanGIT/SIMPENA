document.addEventListener("DOMContentLoaded", function () {

    // 1. BUKA MODAL EDIT
    document.querySelectorAll(".edit-btn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("modalTitle").innerHTML = '<i class="fa fa-pencil text-primary"></i> Edit Service Report';
            
            document.getElementById("editId").value = this.getAttribute("data-id");
            document.getElementById("no_report").value = this.getAttribute("data-no");
            document.getElementById("tanggal").value = this.getAttribute("data-tgl") || '';
            document.getElementById("nama_prasarana").value = this.getAttribute("data-prasarana") || '';
            document.getElementById("merek").value = this.getAttribute("data-merek") || '';
            document.getElementById("tipe_model").value = this.getAttribute("data-tipe") || '';
            document.getElementById("no_seri").value = this.getAttribute("data-seri") || '';
            document.getElementById("nama_ruangan").value = this.getAttribute("data-ruangan") || '';
            document.getElementById("tahun").value = this.getAttribute("data-tahun") || '';

            document.getElementById("permasalahan").value = this.getAttribute("data-permasalahan") || '';
            document.getElementById("keterangan").value = this.getAttribute("data-keterangan") || '';

            document.getElementById("ttd_kiri").value = this.getAttribute("data-kiri") || '';
            document.getElementById("ttd_kanan").value = this.getAttribute("data-kanan") || '';
            document.getElementById("ttd_tengah").value = this.getAttribute("data-tengah") || 'Staff PDE';

            document.querySelectorAll("#formReport input[type='checkbox']").forEach(cb => cb.checked = false);

            let serviceVal = this.getAttribute("data-service") || '';
            document.querySelectorAll("input[name='service_type[]']").forEach(cb => {
                if (serviceVal.includes(cb.value)) cb.checked = true;
            });

            let periksaVal = this.getAttribute("data-periksa") || '';
            document.querySelectorAll("input[name='pemeriksaan[]']").forEach(cb => {
                if (periksaVal.includes(cb.value)) cb.checked = true;
            });

            let hasilVal = this.getAttribute("data-hasil") || '';
            document.querySelectorAll("input[name='hasil_pemeriksaan[]']").forEach(cb => {
                if (hasilVal.includes(cb.value)) cb.checked = true;
            });

            var modal = document.getElementById("modalTambah");
            if (modal) {
                modal.classList.add("in");
                modal.style.display = "block";
            }
        });
    });

    // 2. TUTUP MODAL
    document.querySelectorAll("[data-dismiss='modal']").forEach(function(btn) {
        btn.addEventListener("click", function() {
            var modal = this.closest(".modal");
            if (modal) { modal.classList.remove("in"); modal.style.display = "none"; }
        });
    });

    // 3. SIMPAN DATA
    var saveReport = document.getElementById("saveReport");
    if (saveReport) {
        saveReport.addEventListener("click", function(e) {
            e.preventDefault();
            var form = document.getElementById("formReport");
            var formData = new FormData(form);
            formData.append(window.csrfName, window.csrfHash);
            
            var isEdit = document.getElementById("editId").value !== "";
            var url = isEdit ? window.urlServiceUpdate : window.urlServiceSimpan;

            fetch(url, { method: 'POST', body: formData })
            .then(response => {
                if (!response.ok) { return response.text().then(text => { throw new Error(text) }); }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(err => {
                alert("Gagal menyimpan.\n\nPesan Error:\n" + err.message);
            });
        });
    }

    // 4. HAPUS DATA
    document.querySelectorAll(".delete-btn").forEach(function(btn) {
        btn.addEventListener("click", function() {
            if(confirm("Hapus report ini?")) {
                fetch(window.urlServiceHapus + this.getAttribute("data-id"), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': window.csrfHash, 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.status !== 'success') { alert(res.message); return; }
                        location.reload();
                    })
                    .catch(() => alert('Gagal menghapus report.'));
            }
        });
    });
});

// FUNGSI BUKA MODAL TAMBAH
function bukaModalTambah() {
    document.getElementById("modalTitle").innerHTML = '<i class="fa fa-file-text text-primary"></i> Buat Service Report';
    document.getElementById("formReport").reset();
    document.getElementById("editId").value = "";
    
    // Ambil nilai no_report otomatis dari view
    var noDefault = document.getElementById("no_report").defaultValue;
    document.getElementById("no_report").value = noDefault;
    
    var modal = document.getElementById("modalTambah");
    modal.classList.add("in");
    modal.style.display = "block";
    document.body.classList.add("modal-open");
    
    var backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop fade in";
    backdrop.id = "backdropTambah";
    document.body.appendChild(backdrop);
}