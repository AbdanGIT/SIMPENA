document.addEventListener("DOMContentLoaded", function () {
  // ==========================================
  // 1. FILTER DROPDOWN
  // ==========================================
  window.toggleFilter = function () {
    var menu = document.getElementById("filterMenu");
    if (menu) {
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    }
  };

  window.onclick = function (event) {
    if (
      !event.target.matches("#filterDropdown") &&
      !event.target.closest("#filterDropdown")
    ) {
      var menu = document.getElementById("filterMenu");
      if (menu) menu.style.display = "none";
    }
  };

  // ==========================================
  // 2. FUNGSI FILTER
  // ==========================================
  document.querySelectorAll(".filter-btn").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      var filterValue = this.getAttribute("data-filter");
      document
        .querySelectorAll("#tabelKerusakan tbody tr")
        .forEach(function (row) {
          var statusText = row.querySelector(".status-text")
            ? row.querySelector(".status-text").textContent.trim()
            : "";
          row.style.display =
            filterValue === "all" || statusText === filterValue ? "" : "none";
        });
      var menu = document.getElementById("filterMenu");
      if (menu) menu.style.display = "none";
    });
  });

  // ==========================================
  // 3. FUNGSI PENCARIAN
  // ==========================================
  var searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keyup", function () {
      var value = this.value.toLowerCase();
      document
        .querySelectorAll("#tabelKerusakan tbody tr")
        .forEach(function (row) {
          row.style.display =
            row.textContent.toLowerCase().indexOf(value) > -1 ? "" : "none";
        });
    });
  }

  // ==========================================
  // 4. FUNGSI EDIT (Membuka Modal)
  // ==========================================
  document.querySelectorAll(".edit-btn").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      document.getElementById("editId").value = this.getAttribute("data-id");
      document.getElementById("editNama").value =
        this.getAttribute("data-nama");
      document.getElementById("editMerek").value =
        this.getAttribute("data-merek");
      document.getElementById("editRuangan").value =
        this.getAttribute("data-ruangan");
      document.getElementById("editTempat").value =
        this.getAttribute("data-tempat");
      document.getElementById("editTglAmbil").value =
        this.getAttribute("data-tglambil");
      document.getElementById("editTglService").value =
        this.getAttribute("data-tglservice");
      document.getElementById("editKelengkapan").value =
        this.getAttribute("data-kelengkapan");
      document.getElementById("editMasalah").value =
        this.getAttribute("data-masalah");
      document.getElementById("editKeterangan").value =
        this.getAttribute("data-keterangan");
      document.getElementById("editNoBarang").value =
        this.getAttribute("data-nobarang");
      document.getElementById("editStatus").value =
        this.getAttribute("data-status");

      var modal = document.getElementById("modalEdit");
      if (modal) {
        modal.classList.add("in");
        modal.style.display = "block";
      }
    });
  });

  // ==========================================
  // 5. FUNGSI TUTUP MODAL
  // ==========================================
  document.querySelectorAll("[data-dismiss='modal']").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var modal = this.closest(".modal");
      if (modal) {
        modal.classList.remove("in");
        modal.style.display = "none";
      }
    });
  });

  // ==========================================
  // 6. FUNGSI SIMPAN TAMBAH (PERBAIKAN CSRF & ANTI DOBEL)
  // ==========================================
  var saveTambahBtn = document.getElementById("saveTambah");

  if (saveTambahBtn) {
    var newSaveTambahBtn = saveTambahBtn.cloneNode(true);
    saveTambahBtn.parentNode.replaceChild(newSaveTambahBtn, saveTambahBtn);

    newSaveTambahBtn.addEventListener("click", function (e) {
      e.preventDefault();

      if (this.disabled) return;

      this.disabled = true;
      this.innerText = "Menyimpan...";

      var formData = new FormData();
      // Pastikan token CSRF terkirim
      formData.append(window.csrfName, window.csrfHash);

      formData.append(
        "addNoBarang",
        document.getElementById("addNoBarang").value,
      );
      formData.append("addNama", document.getElementById("addNama").value);
      formData.append("addMerek", document.getElementById("addMerek").value);
      formData.append(
        "addRuangan",
        document.getElementById("addRuangan").value,
      );
      formData.append("addTempat", document.getElementById("addTempat").value);
      formData.append(
        "addTglAmbil",
        document.getElementById("addTglAmbil").value,
      );
      formData.append(
        "addTglService",
        document.getElementById("addTglService").value,
      );
      formData.append(
        "addKelengkapan",
        document.getElementById("addKelengkapan").value,
      );
      formData.append(
        "addMasalah",
        document.getElementById("addMasalah").value,
      );
      formData.append(
        "addKeterangan",
        document.getElementById("addKeterangan").value,
      );
      formData.append("addStatus", document.getElementById("addStatus").value);

      var fileFoto = document.getElementById("addFoto").files[0];
      if (fileFoto) formData.append("addFoto", fileFoto);

      fetch(window.urlSimpan, {
        method: "POST",
        body: formData,
        // TAMBAHKAN HEADER INI AGAR DIKENALI SEBAGAI AJAX
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => {
          // Cek jika response berupa HTML (berarti ada error PHP/CSRF)
          const contentType = response.headers.get("content-type");
          if (!contentType || !contentType.includes("application/json")) {
            throw new Error(
              "Respon server bukan JSON (Kemungkinan Error CSRF 403 atau PHP Error 500).",
            );
          }
          return response.json();
        })
        .then((data) => {
          if (data.status === "success") {
            var modal = document.getElementById("modalTambah");
            if (modal) {
              modal.classList.remove("in");
              modal.style.display = "none";
            }
            alert("Data Berhasil Disimpan!");
            location.reload();
          } else {
            alert("Server Error: " + data.message);
            this.disabled = false;
            this.innerText = "Simpan Data";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Gagal menyimpan data: " + error.message);
          this.disabled = false;
          this.innerText = "Simpan Data";
        });
    });
  }

  // ==========================================
  // 7. FUNGSI SIMPAN EDIT (PERBAIKAN CSRF)
  // ==========================================
  var saveEditBtn = document.getElementById("saveEdit");
  if (saveEditBtn) {
    saveEditBtn.addEventListener("click", function (e) {
      e.preventDefault();

      var formData = new FormData();
      formData.append(window.csrfName, window.csrfHash);

      formData.append("id_kerusakan", document.getElementById("editId").value);
      formData.append(
        "addNoBarang",
        document.getElementById("editNoBarang").value,
      );
      formData.append("addNama", document.getElementById("editNama").value);
      formData.append("addMerek", document.getElementById("editMerek").value);
      formData.append(
        "addRuangan",
        document.getElementById("editRuangan").value,
      );
      formData.append("addTempat", document.getElementById("editTempat").value);
      formData.append(
        "addTglAmbil",
        document.getElementById("editTglAmbil").value,
      );
      formData.append(
        "addTglService",
        document.getElementById("editTglService").value,
      );
      formData.append(
        "addKelengkapan",
        document.getElementById("editKelengkapan").value,
      );
      formData.append(
        "addMasalah",
        document.getElementById("editMasalah").value,
      );
      formData.append(
        "addKeterangan",
        document.getElementById("editKeterangan").value,
      );
      formData.append("addStatus", document.getElementById("editStatus").value);

      var fileFoto = document.getElementById("editFoto").files[0];
      if (fileFoto) formData.append("editFoto", fileFoto);

      saveEditBtn.innerText = "Mengupdate...";
      saveEditBtn.disabled = true;

      fetch(window.urlUpdate, {
        method: "POST",
        body: formData,
        // TAMBAHKAN HEADER INI
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => {
          const contentType = response.headers.get("content-type");
          if (!contentType || !contentType.includes("application/json")) {
            throw new Error(
              "Respon server bukan JSON (Kemungkinan Error CSRF 403 atau PHP Error 500).",
            );
          }
          return response.json();
        })
        .then((data) => {
          if (data.status === "success") {
            var modal = document.getElementById("modalEdit");
            if (modal) {
              modal.classList.remove("in");
              modal.style.display = "none";
            }
            alert("Data Berhasil Diupdate!");
            location.reload();
          } else {
            alert("Server Error: " + data.message);
            saveEditBtn.innerText = "Update Data";
            saveEditBtn.disabled = false;
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Gagal update data: " + error.message);
          saveEditBtn.innerText = "Update Data";
          saveEditBtn.disabled = false;
        });
    });
  }

  // ==========================================
  // 8. FUNGSI HAPUS DATA
  // ==========================================
  document.querySelectorAll(".delete-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var id = this.getAttribute("data-id");

      if (!id || id === "0") {
        alert("Gagal menghapus: ID data tidak ditemukan.");
        return;
      }

      if (confirm("Hapus data ini?")) {
        fetch(window.urlHapus + id, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": window.csrfHash,
            "X-Requested-With": "XMLHttpRequest",
          },
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              alert(data.message);
              location.reload();
            } else {
              alert("Gagal menghapus: " + data.message);
            }
          })
          .catch((err) => {
            console.error("Error detail:", err);
            alert(
              "Terjadi kesalahan sistem saat menghapus. Cek Console (F12).",
            );
          });
      }
    });
  });
});
