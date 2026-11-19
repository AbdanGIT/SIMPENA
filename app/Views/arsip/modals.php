<!-- MODAL TAMBAH DOKUMEN -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;"><i class="fa fa-plus-circle text-primary"></i> Tambah Arsip Dokumen</h4>
      </div>
      <div class="modal-body">
        <form id="formTambah">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"><label>Nama Dokumen</label><input type="text" class="form-control" id="addNama" required></div>
              <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" id="addKategori" required>
                  <option value="" disabled selected>Pilih Kategori...</option>
                  <option value="SOP">SOP</option>
                  <option value="Laporan">Laporan</option>
                  <option value="Akreditasi">Akreditasi</option>
                  <option value="Tanda Terima">Tanda Terima</option>
                  <option value="Service Report">Service Report</option>
                  <option value="Telaah Staff">Telaah Staff</option>
                  <option value="Jadwal Piket">Jadwal Piket</option>
                </select>
              </div>
              <div class="form-group"><label>Tanggal Upload</label><input type="date" class="form-control" id="addTanggal" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group"><label>Deskripsi (Opsional)</label><textarea class="form-control" id="addDeskripsi" rows="3"></textarea></div>
              <div class="form-group"><label>Upload File (PDF, Word, Excel, Gambar)</label><input type="file" class="form-control" id="addFile" required></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveTambah" style="background: #1e5fa6;">Simpan Dokumen</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDIT DOKUMEN -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;"><i class="fa fa-pencil text-primary"></i> Edit Arsip Dokumen</h4>
      </div>
      <div class="modal-body">
        <form id="formEdit">
          <input type="hidden" id="editId">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"><label>Nama Dokumen</label><input type="text" class="form-control" id="editNama" required></div>
              <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" id="editKategori" required>
                  <option value="SOP">SOP</option>
                  <option value="Laporan">Laporan</option>
                  <option value="Akreditasi">Akreditasi</option>
                  <option value="Tanda Terima">Tanda Terima</option>
                  <option value="Service Report">Service Report</option>
                  <option value="Telaah Staff">Telaah Staff</option>
                  <option value="Jadwal Piket">Jadwal Piket</option>
                </select>
              </div>
              <div class="form-group"><label>Tanggal Upload</label><input type="date" class="form-control" id="editTanggal" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group"><label>Deskripsi</label><textarea class="form-control" id="editDeskripsi" rows="3"></textarea></div>
              <div class="form-group"><label>Ganti File (Kosongkan jika tidak diganti)</label><input type="file" class="form-control" id="editFile"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveEdit" style="background: #1e5fa6;">Update Dokumen</button>
      </div>
    </div>
  </div>
</div>

<script>
    window.urlArsipSimpan = "<?= base_url('arsip/simpan') ?>";
    window.urlArsipUpdate = "<?= base_url('arsip/update') ?>";
    window.urlArsipHapus  = "<?= base_url('arsip/hapus/') ?>";
    window.csrfName = "<?= csrf_token() ?>";
    window.csrfHash = "<?= csrf_hash() ?>";
</script>
<script src="<?= base_url('assets/js/arsip.js') ?>"></script>