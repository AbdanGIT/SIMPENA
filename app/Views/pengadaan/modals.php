<!-- MODAL LIHAT DETAIL -->
<?php if(!empty($pengadaan)): foreach($pengadaan as $row): ?>
<div class="modal fade" id="modalLihat<?= $row['id_pengadaan'] ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;">
          <i class="fa fa-eye text-info"></i> Detail Pengadaan
        </h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr><th style="width: 35%; background: #f8fafc;">Part Number</th><td><?= esc($row['part_number']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Description</th><td><?= esc($row['description']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Stock Awal</th><td><?= $row['stock_awal'] ?></td></tr>
          <tr><th style="background: #f8fafc;">Incoming</th><td class="text-success">+<?= $row['incoming'] ?></td></tr>
          <tr><th style="background: #f8fafc;">Outgoing</th><td class="text-danger">-<?= $row['outgoing'] ?></td></tr>
          <tr><th style="background: #f8fafc;">Stock Actual</th><td><strong><?= $row['stock_actual'] ?></strong></td></tr>
          <tr><th style="background: #f8fafc;">Dokumentasi</th>
            <td>
              <?php if(!empty($row['dokumentasi'])): ?>
                <a href="<?= base_url('uploads/' . $row['dokumentasi']) ?>" target="_blank">Lihat Dokumen</a>
              <?php else: ?>
                <span class="text-muted">Tidak ada file</span>
              <?php endif; ?>
            </td>
          </tr>
          <tr><th style="background: #f8fafc;">Status</th><td><span style="background: <?= ($row['status'] == 'DONE') ? '#d1fae5' : '#fef3c7' ?>; color: <?= ($row['status'] == 'DONE') ? '#065f46' : '#92400e' ?>; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;"><?= $row['status'] ?></span></td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; endif; ?>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;"><i class="fa fa-plus-circle text-primary"></i> Tambah Data Pengadaan</h4>
      </div>
      <div class="modal-body">
        <form id="formTambah">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"><label>Part Number</label><input type="text" class="form-control" id="addPart" required></div>
              <div class="form-group"><label>Description</label><input type="text" class="form-control" id="addDesc" required></div>
              <div class="form-group"><label>Stock Awal</label><input type="number" class="form-control" id="addAwal" value="0" required></div>
              <div class="form-group"><label>Incoming (Masuk)</label><input type="number" class="form-control" id="addIncoming" value="0" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group"><label>Outgoing (Keluar)</label><input type="number" class="form-control" id="addOutgoing" value="0" required></div>
              <div class="form-group"><label>Stock Aktual (Otomatis)</label><input type="number" class="form-control" id="addAktual" readonly style="background: #e9ecef;"></div>
              <div class="form-group"><label>Dokumentasi (Foto/File)</label><input type="file" class="form-control" id="addDokumentasi"></div>
              <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="addStatus">
                  <option value="PENDING">PENDING</option>
                  <option value="DONE">DONE</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveTambah" style="background: #1e5fa6;">Simpan Data</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDIT DATA -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;"><i class="fa fa-pencil text-primary"></i> Edit Data Pengadaan</h4>
      </div>
      <div class="modal-body">
        <form id="formEdit">
          <input type="hidden" id="editId">
          <input type="hidden" id="editDokumentasiLama"> 
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"><label>Part Number</label><input type="text" class="form-control" id="editPart" required></div>
              <div class="form-group"><label>Description</label><input type="text" class="form-control" id="editDesc" required></div>
              <div class="form-group"><label>Stock Awal</label><input type="number" class="form-control" id="editAwal" required></div>
              <div class="form-group"><label>Incoming (Masuk)</label><input type="number" class="form-control" id="editIncoming" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group"><label>Outgoing (Keluar)</label><input type="number" class="form-control" id="editOutgoing" required></div>
              <div class="form-group"><label>Stock Aktual</label><input type="number" class="form-control" id="editAktual" readonly style="background: #e9ecef;"></div>
              <div class="form-group"><label>Dokumentasi (Kosongkan jika tidak diganti)</label><input type="file" class="form-control" id="editDokumentasi"></div>
              <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="editStatus">
                  <option value="PENDING">PENDING</option>
                  <option value="DONE">DONE</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveEdit" style="background: #1e5fa6;">Update Data</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH INCOMING -->
<div class="modal fade" id="modalTambahInc" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success"><h4 class="modal-title text-white">Tambah Incoming</h4></div>
      <div class="modal-body">
        <div class="form-group">
            <label>Part Number</label>
            <select class="form-control" id="addIncPart" onchange="isiDeskripsi('addIncPart', 'addIncDesc')" required>
                <option value="">-- Pilih Part Number --</option>
                <?php foreach($list_part as $lp): ?>
                <option value="<?= $lp['part_number'] ?>" data-desc="<?= esc($lp['description']) ?>"><?= esc($lp['part_number']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>
        <div class="form-group"><label>Description</label><input type="text" class="form-control" id="addIncDesc" required></div>
        <div class="form-group"><label>Qty Masuk</label><input type="number" class="form-control" id="addIncQty" required></div>
        <div class="form-group"><label>Tanggal</label><input type="date" class="form-control" id="addIncTgl" required></div>
        <div class="form-group"><label>Status</label><select class="form-control" id="addIncStatus"><option value="PENDING">PENDING</option><option value="DONE">DONE</option></select></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="button" class="btn btn-success" id="saveInc">Simpan</button></div>
    </div>
  </div>
</div>

<!-- MODAL EDIT INCOMING -->
<div class="modal fade" id="modalEditInc" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary"><h4 class="modal-title text-white">Edit Incoming</h4></div>
      <div class="modal-body">
        <input type="hidden" id="editIncId">
        <div class="form-group">
            <label>Part Number</label>
            <select class="form-control" id="editIncPart" onchange="isiDeskripsi('editIncPart', 'editIncDesc')" required>
                <option value="">-- Pilih Part Number --</option>
                <?php foreach($list_part as $lp): ?>
                <option value="<?= $lp['part_number'] ?>" data-desc="<?= esc($lp['description']) ?>"><?= esc($lp['part_number']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>
        <div class="form-group"><label>Description</label><input type="text" class="form-control" id="editIncDesc" required></div>
        <div class="form-group"><label>Qty Masuk</label><input type="number" class="form-control" id="editIncQty" required></div>
        <div class="form-group"><label>Tanggal</label><input type="date" class="form-control" id="editIncTgl" required></div>
        <div class="form-group"><label>Status</label><select class="form-control" id="editIncStatus"><option value="PENDING">PENDING</option><option value="DONE">DONE</option></select></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="button" class="btn btn-primary" id="saveEditInc">Update</button></div>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH OUTGOING -->
<div class="modal fade" id="modalTambahOut" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger"><h4 class="modal-title text-white">Tambah Outgoing</h4></div>
      <div class="modal-body">
        <div class="form-group">
            <label>Part Number</label>
            <select class="form-control" id="addOutPart" onchange="isiDeskripsi('addOutPart', 'addOutDesc')" required>
                <option value="">-- Pilih Part Number --</option>
                <?php foreach($list_part as $lp): ?>
                <option value="<?= $lp['part_number'] ?>" data-desc="<?= esc($lp['description']) ?>"><?= esc($lp['part_number']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>
        <div class="form-group"><label>Description</label><input type="text" class="form-control" id="addOutDesc" required></div>
        <div class="form-group"><label>Qty Keluar</label><input type="number" class="form-control" id="addOutQty" required></div>
        <div class="form-group"><label>Tanggal</label><input type="date" class="form-control" id="addOutTgl" required></div>
        <div class="form-group"><label>Distribusi</label><input type="text" class="form-control" id="addOutDist" required></div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" id="addOutStatus">
                <option value="PENDING">PENDING</option>
                <option value="DONE">DONE</option>
            </select>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="button" class="btn btn-danger" id="saveOut">Simpan</button></div>
    </div>
  </div>
</div>

<!-- MODAL EDIT OUTGOING -->
<div class="modal fade" id="modalEditOut" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary"><h4 class="modal-title text-white">Edit Outgoing</h4></div>
      <div class="modal-body">
        <input type="hidden" id="editOutId">
        <div class="form-group">
            <label>Part Number</label>
            <select class="form-control" id="editOutPart" onchange="isiDeskripsi('editOutPart', 'editOutDesc')" required>
                <option value="">-- Pilih Part Number --</option>
                <?php foreach($list_part as $lp): ?>
                <option value="<?= $lp['part_number'] ?>" data-desc="<?= esc($lp['description']) ?>"><?= esc($lp['part_number']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>
        <div class="form-group"><label>Description</label><input type="text" class="form-control" id="editOutDesc" required></div>
        <div class="form-group"><label>Qty Keluar</label><input type="number" class="form-control" id="editOutQty" required></div>
        <div class="form-group"><label>Tanggal</label><input type="date" class="form-control" id="editOutTgl" required></div>
        <div class="form-group"><label>Distribusi</label><input type="text" class="form-control" id="editOutDist" required></div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" id="editOutStatus">
                <option value="PENDING">PENDING</option>
                <option value="DONE">DONE</option>
            </select>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="button" class="btn btn-primary" id="saveEditOut">Update</button></div>
    </div>
  </div>
</div>

<script>
    window.urlSimpanPengadaan = "<?= base_url('pengadaan/simpan') ?>";
    window.urlUpdatePengadaan = "<?= base_url('pengadaan/update') ?>";
    window.urlHapusPengadaan  = "<?= base_url('pengadaan/hapus/') ?>";
    
    window.urlSimpanInc = "<?= base_url('pengadaan/simpan_incoming') ?>";
    window.urlUpdateInc = "<?= base_url('pengadaan/update_incoming') ?>";
    window.urlHapusInc  = "<?= base_url('pengadaan/hapus_incoming/') ?>";
    
    window.urlSimpanOut = "<?= base_url('pengadaan/simpan_outgoing') ?>";
    window.urlUpdateOut = "<?= base_url('pengadaan/update_outgoing') ?>";
    window.urlHapusOut  = "<?= base_url('pengadaan/hapus_outgoing/') ?>";
    
    window.csrfName = "<?= csrf_token() ?>";
    window.csrfHash = "<?= csrf_hash() ?>";
</script>
<script src="<?= base_url('assets/js/pengadaan.js') ?>"></script>