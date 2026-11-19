<style>
  .content { padding: 0 20px 30px 20px !important; width: 100%; }
  .custom-card { background: #fff; border-radius: 15px; padding: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); margin-bottom: 20px; }
  
  .header-banner {
    background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>');
    background-size: cover; background-position: center;
    border-radius: 15px; padding: 20px 25px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center;
    width: 100%; box-sizing: border-box; flex-wrap: wrap; gap: 15px;
  }
  .header-icon { background: #fff4e6; color: #ff922b; width: 60px; height: 60px; border-radius: 15px; display: flex; justify-content: center; align-items: center; font-size: 26px; margin-right: 15px; flex-shrink: 0; }
  .header-title { font-size: 22px; font-weight: 800; margin: 0; color: #1a1a1a; }

  .table-custom { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
  .table-custom thead th { background: #f8fafc; padding: 12px 10px; font-size: 13px; font-weight: 800; color: #475569; text-align: left; border: none; white-space: nowrap; text-transform: uppercase; }
  .table-custom tbody tr { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s; }
  .table-custom tbody td { padding: 12px 10px; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 600; color: #1e293b; }
  .table-custom tbody tr td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
  .table-custom tbody tr td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

  .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; display: inline-block; }
  .status-menunggu { background: #fff4e6; color: #f08c00; border: 1px solid #ffe8cc; }
  .status-selesai { background: #e6fcf5; color: #0ca678; border: 1px solid #b2f2bb; }
</style>

<!-- HEADER BANNER -->
<div class="header-banner">
  <div class="d-flex align-items-center" style="flex: 1;">
    <div class="header-icon"><i class="fa fa-list-ol"></i></div>
    <div>
      <p style="color: #ff922b; font-weight: 700; margin: 0; font-size: 13px;">Instalasi Pusat Data Elektronik (PDE)</p>
      <h1 class="header-title">Antrian Pergantian Sparepart</h1>
      <p style="color: #555; margin: 0; font-size: 12px; font-weight: 600;">Daftar barang rusak yang sedang menunggu pengadaan sparepart.</p>
    </div>
  </div>
  <div>
    <button class="btn btn-warning" onclick="bukaModalTambah()" style="border-radius: 10px; font-weight: 700; color: #fff;"><i class="fa fa-plus"></i> Tambah Antrian</button>
  </div>
</div>

<!-- KONTEN TABEL -->
<div class="custom-card">
  <div class="table-responsive">
    <table class="table-custom" id="tabelAntrian">
      <thead>
        <tr>
          <th style="width: 5%; text-align: center;">No</th>
          <th style="width: 20%;">Nama Barang</th>
          <th style="width: 15%;">Ruangan</th>
          <th style="width: 20%;">Kerusakan</th>
          <th style="width: 20%;">Sparepart Dibutuhkan</th>
          <th style="width: 10%; text-align: center;">Status</th>
          <th style="width: 10%; text-align: center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($antrian)): ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 30px; color: #888; font-weight: 600;">
              Belum ada data barang yang menunggu sparepart.
            </td>
          </tr>
        <?php else: ?>
          <?php $no = 1; foreach($antrian as $t): ?>
          <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="color: #1e5fa6; font-weight: 800;"><?= esc($t['nama_barang']) ?></td>
            <td><?= esc($t['ruangan']) ?></td>
            <td><?= esc($t['kerusakan']) ?></td>
            <td><?= esc($t['sparepart_dibutuhkan']) ?></td>
            <td style="text-align: center;">
              <span class="badge-status <?= ($t['status'] == 'SELESAI') ? 'status-selesai' : 'status-menunggu' ?>">
                <?= esc($t['status']) ?>
              </span>
            </td>
            <td style="text-align: center; white-space: nowrap;">
              <button class="btn btn-sm btn-primary edit-btn" 
                data-id="<?= $t['id_antrian'] ?>" 
                data-barang="<?= esc($t['nama_barang']) ?>" 
                data-ruangan="<?= esc($t['ruangan']) ?>" 
                data-rusak="<?= esc($t['kerusakan']) ?>" 
                data-spare="<?= esc($t['sparepart_dibutuhkan']) ?>"
                data-status="<?= esc($t['status']) ?>"
                style="border-radius: 8px;"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $t['id_antrian'] ?>" style="border-radius: 8px;"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #fff4e6;">
        <h4 class="modal-title" style="font-weight: 800; color: #ff922b;"><i class="fa fa-plus-circle"></i> Tambah Antrian Baru</h4>
      </div>
      <div class="modal-body">
        <form id="formTambah">
          <div class="form-group"><label>Nama Barang</label><input type="text" class="form-control" id="addBarang" required></div>
          <div class="form-group"><label>Ruangan</label><input type="text" class="form-control" id="addRuangan" required></div>
          <div class="form-group"><label>Kerusakan</label><textarea class="form-control" id="addRusak" rows="2" required></textarea></div>
          <div class="form-group"><label>Sparepart Dibutuhkan</label><input type="text" class="form-control" id="addSpare" required></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="saveTambah" style="color: white; font-weight: 700;">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #e6f0fa;">
        <h4 class="modal-title" style="font-weight: 800; color: #1e5fa6;"><i class="fa fa-pencil"></i> Edit Data Antrian</h4>
      </div>
      <div class="modal-body">
        <form id="formEdit">
          <input type="hidden" id="editId">
          <div class="form-group"><label>Nama Barang</label><input type="text" class="form-control" id="editBarang" required></div>
          <div class="form-group"><label>Ruangan</label><input type="text" class="form-control" id="editRuangan" required></div>
          <div class="form-group"><label>Kerusakan</label><textarea class="form-control" id="editRusak" rows="2" required></textarea></div>
          <div class="form-group"><label>Sparepart Dibutuhkan</label><input type="text" class="form-control" id="editSpare" required></div>
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" id="editStatus">
              <option value="MENUNGGU">MENUNGGU (Belum Dibeli)</option>
              <option value="SELESAI">SELESAI (Sudah Diganti)</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveEdit" style="font-weight: 700;">Update</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Buka Modal Tambah
  window.bukaModalTambah = function() {
    $("#modalTambah").modal("show");
    document.getElementById("formTambah").reset();
  }

  // Simpan Tambah
  $("#saveTambah").click(function(e) {
    e.preventDefault();
    $.ajax({
      url: "<?= base_url('antrian_sparepart/simpan') ?>",
      type: "POST",
      data: {
        nama_barang: $("#addBarang").val(),
        ruangan: $("#addRuangan").val(),
        kerusakan: $("#addRusak").val(),
        sparepart_dibutuhkan: $("#addSpare").val(),
        "<?= csrf_token() ?>": "<?= csrf_hash() ?>" // Tambahkan CSRF
      },
      success: function() { location.reload(); },
      error: function() { alert("Gagal menyimpan data."); }
    });
  });

  // Buka Modal Edit
  $(".edit-btn").click(function() {
    $("#editId").val($(this).data("id"));
    $("#editBarang").val($(this).data("barang"));
    $("#editRuangan").val($(this).data("ruangan"));
    $("#editRusak").val($(this).data("rusak"));
    $("#editSpare").val($(this).data("spare"));
    $("#editStatus").val($(this).data("status"));
    $("#modalEdit").modal("show");
  });

  // Simpan Edit
  $("#saveEdit").click(function(e) {
    e.preventDefault();
    $.ajax({
      url: "<?= base_url('antrian_sparepart/update') ?>",
      type: "POST",
      data: {
        id_antrian: $("#editId").val(),
        nama_barang: $("#editBarang").val(),
        ruangan: $("#editRuangan").val(),
        kerusakan: $("#editRusak").val(),
        sparepart_dibutuhkan: $("#editSpare").val(),
        status: $("#editStatus").val(),
        "<?= csrf_token() ?>": "<?= csrf_hash() ?>" // Tambahkan CSRF
      },
      success: function() { location.reload(); },
      error: function() { alert("Gagal mengupdate data."); }
    });
  });

  // Hapus
  $(".delete-btn").click(function() {
    if(confirm("Hapus data antrian ini?")) {
      var id = $(this).data("id");
      $.ajax({
        url: "<?= base_url('antrian_sparepart/hapus/') ?>" + id,
        type: "POST",
        headers: { "X-CSRF-TOKEN": "<?= csrf_hash() ?>" },
        success: function(res) {
          if (res && res.status === 'error') { alert(res.message); return; }
          location.reload();
        },
        error: function() { alert("Gagal menghapus data."); }
      });
    }
  });
});
</script>