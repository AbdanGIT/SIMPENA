<!-- MODAL LIHAT DETAIL -->
<?php foreach ($kerusakan as $row): ?>
<div class="modal fade" id="modalLihat<?= $row['id_kerusakan'] ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;">
          <i class="fa fa-eye text-info"></i> Detail Kerusakan: <?= esc($row['nama_barang']) ?>
        </h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr><th style="width: 35%; background: #f8fafc;">Nomor Barang</th><td><?= esc($row['no_barang']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Nama Barang</th><td><?= esc($row['nama_barang']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Merek / Tipe</th><td><?= esc($row['merek']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Ruangan</th><td><?= esc($row['ruangan']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Tempat Service</th><td><?= esc($row['tempat_service']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Tanggal Ambil</th><td><?= esc($row['tgl_ambil']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Tanggal Service</th><td><?= esc($row['tgl_service']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Kelengkapan</th><td><?= esc($row['kelengkapan']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Masalah</th><td><?= esc($row['masalah']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Keterangan</th><td><?= esc($row['keterangan']) ?></td></tr>
          <tr><th style="background: #f8fafc;">Bukti Foto</th>
            <td>
              <?php if (! empty($row['bukti_foto'])): ?>
                <a href="<?= base_url('uploads/' . $row['bukti_foto']) ?>" target="_blank">
                    <img src="<?= base_url('uploads/' . $row['bukti_foto']) ?>" alt="Bukti Foto" style="max-width: 100%; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                </a>
              <?php else: ?>
                <span class="text-muted">Tidak ada foto</span>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th style="background: #f8fafc;">Status</th>
            <td>
                <?php 
                  $statusModal = $row['status'];
                  if ($statusModal == 'Sudah Penghapusan Aset') {
                      $bgModal = '#fee2e2'; $textModal = '#dc2626';
                  } elseif ($statusModal == 'Selesai Perbaikan') {
                      $bgModal = '#d1fae5'; $textModal = '#065f46';
                  } elseif ($statusModal == 'Dalam Perbaikan') {
                      $bgModal = '#fef3c7'; $textModal = '#92400e';
                  } else {
                      $bgModal = '#e6f0fa'; $textModal = '#1e5fa6';
                  }
                ?>
                <span class="status-text" style="background: <?= $bgModal ?>; color: <?= $textModal ?>; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;"><?= esc($row['status']) ?></span>
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;"><i class="fa fa-plus-circle text-primary"></i> Tambah Data Kerusakan</h4>
      </div>
      <div class="modal-body">
        <form id="formTambah">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Barang</label>
                <select class="form-control" id="addNama" required>
                  <option value="" disabled selected>Pilih Kategori Barang...</option>
                  <option value="Komputer">Komputer</option>
                  <option value="Printer">Printer</option>
                  <option value="PC Deskop">PC Deskop</option>
                </select>
              </div>
              <div class="form-group">
                <label>Merek / Tipe</label>
                <select class="form-control" id="addMerek" required>
                  <option value="" disabled selected>Pilih Merek...</option>
                  <option value="Acer Aspire C27">Acer Aspire C27</option>
                  <option value="Acer Veriton Z4">Acer Veriton Z4</option>
                  <option value="Axioo">Axioo</option>
                  <option value="Axioo H5">Axioo H5</option>
                  <option value="Axioo K5">Axioo K5</option>
                  <option value="Axioo K7">Axioo K7</option>
                  <option value="Lenovo Silver">Lenovo Silver</option>
                  <option value="Lenovo Putih">Lenovo Putih</option>
                  <option value="Lenovo Hitam">Lenovo Hitam</option>
                  <option value="Printer Epson L121">Printer Epson L120</option>
                  <option value="Printer Epson L121">Printer Epson L121</option>
                  <option value="Printer Epson L3110">Printer Epson L3110</option>
                  <option value="Printer Epson L3210">Printer Epson L3210</option>
                  <option value="Printer Canon G3010">Printer Canon G3010</option>
                  <option value="Printer Canon G3730">Printer Canon G3730</option>
                  <option value="Printer Canon G4770">Printer Canon G4770</option>
                  <option value="Printer Brother DCP-T310">Printer Brother DCP-T310</option>
                  <option value="PC Deskop HP">PC Deskop HP</option>
                </select>
              </div>
              <div class="form-group"><label>Ruangan</label><input type="text" class="form-control" id="addRuangan" required></div>
              <div class="form-group"><label>Tempat Service</label><input type="text" class="form-control" id="addTempat" required></div>
              <div class="form-group"><label>Tanggal Ambil dari Ruangan</label><input type="date" class="form-control" id="addTglAmbil" required></div>
              <div class="form-group"><label>Tanggal Service</label><input type="date" class="form-control" id="addTglService" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Kelengkapan Barang</label>
                <select class="form-control" id="addKelengkapan" required>
                  <option value="" disabled selected>Pilih Kelengkapan...</option>
                  <option value="Kabel (Ada)">Kabel (Ada)</option>
                  <option value="Kabel USB (Ada)">Kabel USB (Ada)</option>
                  <option value="Kabel Power (Ada)">Kabel Power (Ada)</option>
                  <option value="Kabel (Tidak Ada)">Kabel (Tidak Ada)</option>
                  <option value="Kabel USB (Tidak Ada)">Kabel USB (Tidak Ada)</option>
                  <option value="Kabel Power (Tidak Ada)">Kabel Power (Tidak Ada)</option>
                </select>
              </div>
              <div class="form-group"><label>Masalah</label><input type="text" class="form-control" id="addMasalah" required></div>
              <div class="form-group"><label>Keterangan</label><input type="text" class="form-control" id="addKeterangan" required></div>
              <div class="form-group"><label>Nomor Barang</label><input type="text" class="form-control" id="addNoBarang" required></div>
              <div class="form-group"><label>Bukti Photo</label><input type="file" class="form-control" id="addFoto"></div>
              <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="addStatus">
                  <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                  <option value="Selesai Perbaikan">Selesai Perbaikan</option>
                  <option value="Di Gudang">Di Gudang</option>
                  <option value="Di Ruangan">Di Ruangan</option>
                  <option value="Sudah Penghapusan Aset">Sudah Penghapusan Aset</option>
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
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;"><i class="fa fa-pencil text-primary"></i> Edit Data Kerusakan</h4>
      </div>
      <div class="modal-body">
        <form id="formEdit">
          <input type="hidden" id="editId">
          <input type="hidden" id="editFotoLama">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Barang</label>
                <select class="form-control" id="editNama" required>
                  <option value="Komputer">Komputer</option>
                  <option value="Printer">Printer</option>
                  <option value="PC Deskop">PC Deskop</option>
                </select>
              </div>
              <div class="form-group">
                <label>Merek / Tipe</label>
                <select class="form-control" id="editMerek" required>
                  <option value="Acer Aspire C27">Acer Aspire C27</option>
                  <option value="Acer Veriton Z4">Acer Veriton Z4</option>
                  <option value="Axioo">Axioo</option>
                  <option value="Axioo H5">Axioo H5</option>
                  <option value="Axioo K5">Axioo K5</option>
                  <option value="Axioo K7">Axioo K7</option>
                  <option value="Lenovo Silver">Lenovo Silver</option>
                  <option value="Lenovo Putih">Lenovo Putih</option>
                  <option value="Lenovo Hitam">Lenovo Hitam</option>
                  <option value="Printer Epson L121">Printer Epson L120</option>
                  <option value="Printer Epson L121">Printer Epson L121</option>
                  <option value="Printer Epson L3110">Printer Epson L3110</option>
                  <option value="Printer Epson L3210">Printer Epson L3210</option>
                  <option value="Printer Canon G3010">Printer Canon G3010</option>
                  <option value="Printer Canon G3730">Printer Canon G3730</option>
                  <option value="Printer Canon G4770">Printer Canon G4770</option>
                  <option value="Printer Brother DCP-T310">Printer Brother DCP-T310</option>
                  <option value="PC Deskop HP">PC Deskop HP</option>
                </select>
              </div>
              <div class="form-group"><label>Ruangan</label><input type="text" class="form-control" id="editRuangan" required></div>
              <div class="form-group"><label>Tempat Service</label><input type="text" class="form-control" id="editTempat" required></div>
              <div class="form-group"><label>Tanggal Ambil</label><input type="date" class="form-control" id="editTglAmbil" required></div>
              <div class="form-group"><label>Tanggal Service</label><input type="date" class="form-control" id="editTglService" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Kelengkapan Barang</label>
                <select class="form-control" id="editKelengkapan" required>
                  <option value="Kabel (Ada)">Kabel (Ada)</option>
                  <option value="Kabel USB (Ada)">Kabel USB (Ada)</option>
                  <option value="Kabel Power (Ada)">Kabel Power (Ada)</option>
                  <option value="Kabel (Tidak Ada)">Kabel (Tidak Ada)</option>
                  <option value="Kabel USB (Tidak Ada)">Kabel USB (Tidak Ada)</option>
                  <option value="Kabel Power (Tidak Ada)">Kabel Power (Tidak Ada)</option>
                </select>
              </div>
              <div class="form-group"><label>Masalah</label><input type="text" class="form-control" id="editMasalah" required></div>
              <div class="form-group"><label>Keterangan</label><input type="text" class="form-control" id="editKeterangan" required></div>
              <div class="form-group"><label>Nomor Barang</label><input type="text" class="form-control" id="editNoBarang" required></div>
              <div class="form-group"><label>Bukti Photo (Kosongkan jika tidak diganti)</label><input type="file" class="form-control" id="editFoto"></div>
              <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="editStatus">
                  <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                  <option value="Selesai Perbaikan">Selesai Perbaikan</option>
                  <option value="Di Gudang">Di Gudang</option>
                  <option value="Di Ruangan">Di Ruangan</option>
                  <option value="Sudah Penghapusan Aset">Sudah Penghapusan Aset</option>
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

<!-- Script Variabel dari PHP ke JS -->
<script>
    window.urlSimpan = "<?= base_url('kerusakan/simpan') ?>";
    window.urlUpdate = "<?= base_url('kerusakan/update') ?>";
    window.urlHapus  = "<?= base_url('kerusakan/hapus/') ?>";
    
    // Ambil nama token dan hash CSRF dari CI4
    window.csrfName = "<?= csrf_token() ?>";
    window.csrfHash = "<?= csrf_hash() ?>";
</script>

<!-- Panggil file JS eksternal -->
<script src="<?= base_url('assets/js/kerusakan.js') ?>"></script>