// public/assets/js/pencatatan.js

// ==========================================
// FUNGSI GLOBAL
// ==========================================
function bukaModalTambah() {
  var form = document.getElementById("formTambahBarang");
  if (form) form.reset();
  var modal = document.getElementById("modalTambahBarang");
  if (modal) {
    modal.classList.add("in");
    modal.style.display = "block";
    document.body.classList.add("modal-open");
    var backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop fade in";
    backdrop.id = "backdropTambah";
    document.body.appendChild(backdrop);
  }
}

function tutupModalTambah() {
  var modal = document.getElementById("modalTambahBarang");
  if (modal) {
    modal.classList.remove("in");
    modal.style.display = "none";
    document.body.classList.remove("modal-open");
    var backdrop = document.getElementById("backdropTambah");
    if (backdrop) backdrop.remove();
  }
}

function toggleSidebar() {
  document.body.classList.toggle("sidebar-minimize");
}

$(document).ready(function () {
  // ==========================================
  // DATA MASTER DROPDOWN BERTINGKAT
  // ==========================================
  const dataMerek = {
    Printer: ["Epson", "Canon", "Brother", "Fargo", "Zebra", "Blueprint"],
    Komputer: ["Axioo", "Acer", "Lenovo", "Asus"],
    Monitor: ["Axioo", "Acer", "Lenovo", "Asus", "SPC"],
  };

  const dataSeri = {
    // Printer
    Epson: ["L121", "L3110", "L3210", "L3251"],
    Canon: ["G3010", "G3730", "G4770"],
    Brother: ["DCP-T310"],
    Fargo: ["DTC1250-ID"],
    Zebra: ["Thermal Zebra"],
    Blueprint: ["R80WD"],

    // Komputer
    Axioo: ["H5", "K5", "K7"],
    Acer: ["Aspire C27", "Veriton Z4"],
    Lenovo: ["Lenovo Silver", "Lenovo Putih", "Lenovo Hitam"],
    Asus: ["Asus VivoBook", "Asus ExpertBook"],

    // Monitor
    SPC: ["Monitor SPC"],
  };

  function updateMerek(prefix) {
    let namaBarang = $(`#${prefix}NamaBarang`).val();
    let merekSelect = $(`#${prefix}Merek`);
    let seriSelect = $(`#${prefix}SeriTipe`);

    merekSelect
      .empty()
      .append('<option value="" disabled selected>-- Pilih Merek --</option>');
    seriSelect
      .empty()
      .append('<option value="" disabled selected>-- Pilih Seri --</option>');

    if (namaBarang && dataMerek[namaBarang]) {
      dataMerek[namaBarang].forEach(function (merek) {
        merekSelect.append(`<option value="${merek}">${merek}</option>`);
      });
    }
  }

  function updateSeri(prefix) {
    let merek = $(`#${prefix}Merek`).val();
    let seriSelect = $(`#${prefix}SeriTipe`);

    seriSelect
      .empty()
      .append('<option value="" disabled selected>-- Pilih Seri --</option>');

    if (merek && dataSeri[merek]) {
      dataSeri[merek].forEach(function (seri) {
        seriSelect.append(`<option value="${seri}">${seri}</option>`);
      });
    }
  }

  // Event Listener Tambah
  $("#addNamaBarang").change(function () {
    updateMerek("add");
  });
  $("#addMerek").change(function () {
    updateSeri("add");
  });

  // Event Listener Edit
  $("#editNamaBarang").change(function () {
    updateMerek("edit");
  });
  $("#editMerek").change(function () {
    updateSeri("edit");
  });

  // ==========================================
  // FUNGSI HALAMAN
  // ==========================================
  // 1. PENCARIAN RUANGAN
  $("#searchBarang").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $(".poli-wrapper").filter(function () {
      var kategoriText = $(this).find(".poli-title").text().toLowerCase();
      $(this).toggle(kategoriText.indexOf(value) > -1);
    });
  });

  // 2. FILTER TAB
  $(".btn-filter").click(function () {
    $(".btn-filter").removeClass("active");
    $(this).addClass("active");
    var filter = $(this).data("filter");
    if (filter === "all") {
      $(".poli-wrapper").show();
    } else {
      $(".poli-wrapper").hide();
      $(".poli-wrapper[data-grup='" + filter + "']").show();
    }
  });

  // 3. TOMBOL LIHAT RUANGAN
  $(".btn-lihat-ruangan").click(function () {
    var kategori = $(this).data("kategori");
    var barang = $(this).data("barang");

    $("#titleRuangan").text(kategori.toUpperCase());
    $("#tabelDetailRuangan tbody").empty();

    barang.forEach(function (item) {
      var badgeColor =
        item.status === "Berfungsi"
          ? "background: #e6fcf5; color: #20c997;"
          : item.status === "Rusak"
            ? "background: #ffe3e3; color: #fa5252;"
            : "background: #fff4e6; color: #ff922b;";
      var fotoHtml = "-";
      if (item.foto) {
        fotoHtml = `<a href="${window.baseUrl}uploads/inventaris/${item.foto}" target="_blank"><img src="${window.baseUrl}uploads/inventaris/${item.foto}" style="height: 30px; border-radius: 4px;"></a>`;
      }
      var isFix = (item.status_fix || "belum_fix") === "fix";
      var fixText = isFix ? "Sudah Fix" : "Belum Fix";
      var fixColor = isFix ? "#1e5fa6" : "#ffc107";
      var fixBg = isFix ? "#e6f0fa" : "#fff9e6";
      var rowBg = isFix ? "transparent" : "#fffdf5";

      var row = `
        <tr style="background-color: ${rowBg};">
          <td style="vertical-align: middle; font-weight: 600;">${item.nama_barang}</td>
          <td style="vertical-align: middle;">${item.merek || "-"}</td>
          <td style="vertical-align: middle;">${item.seri_tipe || "-"}</td>
          <td style="vertical-align: middle;">${item.keterangan || "-"}</td>
          <td style="vertical-align: middle;"><span style="padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; ${badgeColor}">${item.status}</span></td>
          <td style="vertical-align: middle;"><span style="padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; background: ${fixBg}; color: ${fixColor};">${fixText}</span></td>
          <td style="vertical-align: middle;">${fotoHtml}</td>
          <td style="text-align: center; white-space: nowrap;">
            <button class="btn btn-sm btn-warning pindah-btn" data-id="${item.id_komputer}" data-nama="${item.nama_barang}" data-kategori="${item.kategori}" title="Pindah Ruangan" style="border-radius: 8px; color: white; margin-bottom: 2px;"><i class="fa fa-exchange"></i></button>
            <button class="btn btn-sm btn-primary edit-btn" data-id="${item.id_komputer}" data-nama="${item.nama_barang}" data-kategori="${item.kategori}" data-status="${item.status}" data-merek="${item.merek || ""}" data-seritipe="${item.seri_tipe || ""}" data-keterangan="${item.keterangan || ""}" data-statusfix="${item.status_fix || "belum_fix"}" style="border-radius: 8px; margin-bottom: 2px;"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id_komputer}" style="border-radius: 8px; margin-bottom: 2px;"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      `;
      $("#tabelDetailRuangan tbody").append(row);
    });

    $("#modalLihatRuangan").modal("show");
  });

  // 4. TOMBOL EDIT
  $(document).on("click", ".edit-btn", function () {
    $("#editId").val($(this).data("id"));
    $("#editNamaBarang").val($(this).data("nama")).trigger("change");

    // Delay sedikit agar dropdown merek dan seri selesai di-render
    setTimeout(() => {
      $("#editMerek").val($(this).data("merek")).trigger("change");
      setTimeout(() => {
        $("#editSeriTipe").val($(this).data("seritipe"));
      }, 100);
    }, 100);

    $("#editKategori").val($(this).data("kategori"));
    $("#editStatus").val($(this).data("status"));
    $("#editKeterangan").val($(this).data("keterangan"));
    $("#editStatusFix").val($(this).data("statusfix"));

    $("#modalLihatRuangan").modal("hide");
    $("#modalEditBarang").modal("show");
  });

  // 5. SIMPAN TAMBAH BARANG
  $("#formTambahBarang").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(window.csrfName, window.csrfHash);

    var btn = $("#saveBarang");
    btn
      .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...')
      .prop("disabled", true);

    $.ajax({
      url: window.baseUrl + "pencatatan/simpan",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.status === "success") {
          tutupModalTambah();
          location.reload();
        } else {
          alert("Gagal: " + response.message);
          btn.html("Simpan").prop("disabled", false);
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        alert("Gagal simpan. Cek Console.");
        btn.html("Simpan").prop("disabled", false);
      },
    });
  });

  // 6. SIMPAN EDIT BARANG
  $("#formEditBarang").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(window.csrfName, window.csrfHash);

    var btn = $("#updateBarang");
    btn
      .html('<i class="fa fa-spinner fa-spin"></i> Update...')
      .prop("disabled", true);

    $.ajax({
      url: window.baseUrl + "pencatatan/update",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function () {
        location.reload();
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        alert("Gagal update. Cek Console.");
      },
    });
  });

  // 7. HAPUS BARANG
  $(document).on("click", ".delete-btn", function () {
    if (confirm("Hapus barang ini?")) {
      var id = $(this).data("id");
      $.ajax({
        url: window.baseUrl + "pencatatan/hapus/" + id,
        type: "POST",
        headers: { "X-CSRF-TOKEN": window.csrfHash },
        success: function () {
          location.reload();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alert("Gagal hapus. Cek Console.");
        },
      });
    }
  });

  // 8. TOMBOL PINDAH RUANGAN
  $(document).on("click", ".pindah-btn", function () {
    $("#pindahId").val($(this).data("id"));
    $("#pindahNama").val($(this).data("nama"));
    $("#pindahAsal").val($(this).data("kategori"));
    $("#pindahTujuan").val("");
    $("#pindahKeterangan").val("");
    $("#pindahFoto").val("");
    $("#modalLihatRuangan").modal("hide");
    $("#modalPindah").modal("show");
  });

  // 9. PROSES SIMPAN PINDAH RUANGAN
  $("#formPindah").on("submit", function (e) {
    e.preventDefault();
    var tujuan = $("#pindahTujuan").val();
    if (tujuan === "" || tujuan === null) {
      alert("Pilih ruangan tujuan terlebih dahulu!");
      return;
    }
    var formData = new FormData(this);
    formData.append(window.csrfName, window.csrfHash);

    var btn = $("#savePindah");
    btn
      .html('<i class="fa fa-spinner fa-spin"></i> Memproses...')
      .prop("disabled", true);

    $.ajax({
      url: window.baseUrl + "pencatatan/pindah_ruangan",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.status === "success") {
          alert("Barang berhasil dipindahkan!");
          location.reload();
        } else {
          alert(response.message);
          btn.html("Proses Pindah").prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        alert("Gagal memindahkan barang. Cek Console (F12).");
        btn.html("Proses Pindah").prop("disabled", false);
      },
    });
  });
});
