<style>
    /* Solusi Modal Scrollable Bootstrap 3 */
.modal {
    overflow-y: auto !important;
}

.modal-open {
    overflow: auto !important;
}

.modal-dialog {
    margin-top: 30px;
    margin-bottom: 30px;
}
</style>

<!-- MODAL TAMBAH / EDIT DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-height: 95vh;">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #1a1a1a;" id="modalTitle"><i class="fa fa-file-text text-primary"></i> Buat Service Report</h4>
      </div>
      <div class="modal-body">
        <form id="formReport">
          <input type="hidden" id="editId" name="id_report">
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group"><label>No. Service Report</label><input type="text" class="form-control" id="no_report" name="no_report" value="<?= $no_report_otomatis ?>" required readonly style="background-color: #e9ecef;"></div>
              <div class="form-group"><label>Tanggal</label><input type="date" class="form-control" id="tanggal" name="tanggal" required></div>
              <div class="form-group"><label>Nama Prasarana</label><input type="text" class="form-control" id="nama_prasarana" name="nama_prasarana" placeholder="Contoh: Printer" required></div>
              <div class="form-group"><label>Merek</label><input type="text" class="form-control" id="merek" name="merek" placeholder="Contoh: Canon" required></div>
            </div>
            <div class="col-md-6">
              <div class="form-group"><label>Tipe / Model</label><input type="text" class="form-control" id="tipe_model" name="tipe_model" placeholder="Contoh: G3730"></div>
              <div class="form-group"><label>S/N (Kode Alat)</label><input type="text" class="form-control" id="no_seri" name="no_seri"></div>
              <div class="form-group"><label>Nama Ruangan</label><input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" required></div>
              <div class="form-group"><label>Tahun</label><input type="text" class="form-control" id="tahun" name="tahun"></div>
            </div>
          </div>
          
          <hr>
          
          <div class="form-group">
            <label style="font-weight: 700; font-size: 14px;">Service Report Type</label>
            <div class="row">
              <div class="col-md-4">
                <div class="checkbox"><label><input type="checkbox" name="service_type[]" value="Instalasi Baru"> Instalasi Baru</label></div>
                <div class="checkbox"><label><input type="checkbox" name="service_type[]" value="Pemeliharaan/Maintenance"> Pemeliharaan/Maintenance</label></div>
              </div>
              <div class="col-md-4">
                <div class="checkbox"><label><input type="checkbox" name="service_type[]" value="Pemantauan Funsi Check"> Pemantauan Funsi Check</label></div>
                <div class="checkbox"><label><input type="checkbox" name="service_type[]" value="Perbaikan/Service"> Perbaikan/Service</label></div>
              </div>
              <div class="col-md-4">
                <div class="checkbox"><label><input type="checkbox" name="service_type[]" value="Kalibrasi"> Kalibrasi</label></div>
                <div class="checkbox"><label><input type="checkbox" name="service_type[]" value="Recall Pemeriksaan"> Recall Pemeriksaan</label></div>
              </div>
            </div>
          </div>
          
          <hr>
          
          <div class="form-group">
            <label style="font-weight: 700; font-size: 14px;">Pemeriksaan</label>
            <div class="row">
              <div class="col-md-6">
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Trouble Shooting"> Trouble Shooting</label></div>
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Uji Energi Power Supply"> Uji Energi Power Supply</label></div>
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Uji Ritik/Roller, bearing, caring"> Uji Ritik/Roller, bearing, caring</label></div>
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Mekanik / sensor roller, paper feed"> Mekanik / sensor roller, paper feed</label></div>
              </div>
              <div class="col-md-6">
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Penempatan Alat (fix error, status device)"> Penempatan Alat (fix error, status device)</label></div>
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Kelengkapan Aksesori (kabel USB, power, dll)"> Kelengkapan Aksesori (kabel USB, power, dll)</label></div>
                <div class="checkbox"><label><input type="checkbox" name="pemeriksaan[]" value="Setting Parameter / Konfigurasi (IP, driver, port)"> Setting Parameter / Konfigurasi (IP, driver, port)</label></div>
              </div>
            </div>
          </div>

          <hr>

          <div class="form-group">
            <label style="font-weight: 700; font-size: 14px;">Permasalahan dan Solusi</label>
            <textarea class="form-control" id="permasalahan" name="permasalahan" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label style="font-weight: 700; font-size: 14px;">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
          </div>

          <hr>

          <div class="form-group">
            <label style="font-weight: 700; font-size: 14px;">Hasil Pemeriksaan</label>
            <div class="row">
              <div class="col-md-4"><div class="checkbox"><label><input type="checkbox" name="hasil_pemeriksaan[]" value="Berfungsi Baik"> Berfungsi Baik</label></div></div>
              <div class="col-md-4"><div class="checkbox"><label><input type="checkbox" name="hasil_pemeriksaan[]" value="Berfungsi Tidak Sempurna"> Berfungsi Tidak Sempurna</label></div></div>
              <div class="col-md-4"><div class="checkbox"><label><input type="checkbox" name="hasil_pemeriksaan[]" value="Tidak Berfungsi"> Tidak Berfungsi</label></div></div>
            </div>
          </div>
          
          <hr>
          
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label>User (Kiri)</label><input type="text" class="form-control" id="ttd_kiri" name="ttd_kiri" placeholder="( ......................... )"></div></div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tengah (Mengetahui)</label>
                <select class="form-control" id="ttd_tengah" name="ttd_tengah">
                  <option value="Staff PDE">Staff PDE</option>
                  <option value="Ka. PDE">Ka. PDE</option>
                </select>
              </div>
            </div>
            <div class="col-md-4"><div class="form-group"><label>Staff PDE (Kanan)</label><input type="text" class="form-control" id="ttd_kanan" name="ttd_kanan" placeholder="( ......................... )"></div></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveReport" style="background: #1e5fa6;">Simpan Data</button>
      </div>
    </div>
  </div>
</div>

<script>
    window.urlServiceSimpan = "<?= base_url('service_report/simpan') ?>";
    window.urlServiceUpdate = "<?= base_url('service_report/update') ?>";
    window.urlServiceHapus  = "<?= base_url('service_report/hapus/') ?>";
    window.csrfName = "<?= csrf_token() ?>";
    window.csrfHash = "<?= csrf_hash() ?>";
</script>
<script src="<?= base_url('assets/js/service_report.js') ?>"></script>