<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800;"><i class="fa fa-plus-circle text-primary"></i> Tambah Barang</h4>
      </div>
      <form id="formTambahBarang" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label>Kategori (Poli/Ruangan)</label>
            <select class="form-control" id="addKategori" name="kategori" required>
              <option value="" disabled selected>-- Pilih Kategori --</option>
              <optgroup label="Poliklinik">
                <option value="Poli Anak">Poli Anak</option>
                <option value="Poli Bedah">Poli Bedah</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli Gizi">Poli Gizi</option>
                <option value="Poli Jantung">Poli Jantung</option>
                <option value="Poli Jiwa">Poli Jiwa</option>
                <option value="Poli Kandungan">Poli Kandungan</option>
                <option value="Poli Kulit & Kelamin">Poli Kulit & Kelamin</option>
                <option value="Poli Mata">Poli Mata</option>
                <option value="Poli MCU">Poli MCU</option>
                <option value="Poli Paru">Poli Paru</option>
                <option value="Poli Penyakit Dalam">Poli Penyakit Dalam</option>
                <option value="Poli Psikologi">Poli Psikologi</option>
                <option value="Instalasi Rehab Medik">Instalasi Rehab Medik</option>
                <option value="Poli Saraf">Poli Saraf</option>
                <option value="Poli THT">Poli THT</option>
                <option value="Poli VCT">Poli VCT</option>
                <option value="Poli Orthopedi">Poli Orthopedi</option>
                <option value="Poli Geriatri">Poli Geriatri</option>
                <option value="Poli Urologi">Poli Urologi</option>
              </optgroup>
              <optgroup label="Rawat Inap">
                <option value="Ruang VK Bersalin">Ruang VK Bersalin</option>
                  <option value="Rawat Inap Binjai (ICU)">Rawat Inap Binjai (ICU)</option>
                  <option value="Rawat Inap Langsat (Anak)">Rawat Inap Langsat (Anak)</option>
                  <option value="Rawat Inap Rambutan (Bedah)">Rawat Inap Rambutan (Bedah)</option>
                  <option value="Rawat Inap Hambawang (Nifas)">Rawat Inap Hambawang (Nifas)</option>
                  <option value="Rawat Inap Pampaken (Isolasi)">Rawat Inap Pampaken (Isolasi)</option>
                   <option value="Rawat Inap Kasturi (VIP/VVIP)">Rawat Inap Kasturi (VIP/VVIP)</option>
                  <option value="Rawat Inap Rambai (Penyakit Dalam)">Rawat Inap Rambai (Penyakit Dalam)</option>
                  <option value="Rawat Inap Karamunting (Bayi dan Isolasi Bayi)">Rawat Inap Karamunting (Bayi)</option>
                  <option value="Rawat Inap Ramania Anak dan Dewasa (VIP)">Rawat Inap Ramania Anak dan Dewasa (VIP)</option>
              </optgroup>
              <optgroup label="Instalasi">
                <option value="IGD PONEK">IGD PONEK</option>
                <option value="Laboratorium">Laboratorium</option>
                <option value="Instalasi Farmasi">Instalasi Farmasi</option>
                <option value="Instalasi Gawat Darurat">Instalasi Gawat Darurat</option>
                <option value="Instalasi Radiologi">Instalasi Radiologi</option>
                <option value="Instalasi Laboratorium">Instalasi Laboratorium</option>
                <option value="Instalasi Bedah Sentral">Instalasi Bedah Sentral</option>
                <option value="Instalasi Rekam Medis">Instalasi Rekam Medis</option>
                <option value="Instalasi Sanitasi">Instalasi Sanitasi</option>
                <option value="Instalasi CSSD dan Laundry">Instalasi CSSD dan Laundry</option>
                <option value="Instalasi Gizi">Instalasi Gizi</option>
                <option value="Instalasi K3">Instalasi K3</option>
                <option value="Instalasi Logistik">Instalasi Logistik</option>
                <option value="Instalasi Ambulan">Instalasi Ambulan</option>
                <option value="Instalasi Pengaduan Masyarakat">Instalasi Pengaduan Masyarakat</option>
                <option value="Instalasi Pemulasaran Jenazah">Instalasi Pemulasaran Jenazah</option>
                <option value="Instalasi Gas Medis">Instalasi Gas Medis</option>
                <option value="Instalasi Pusat Data dan Informasi Elektronik">Instalasi Pusat Data dan Informasi Elektronik</option>
                <option value="Patologi Anatomi">Patologi Anatomi</option>
                <option value="Hemodialisa">Hemodialisa</option>
                <option value="IPSRS">IPSRS</option>
              </optgroup>
              <optgroup label="Komite & Manajemen">
                <option value="JKN">JKN</option>
                <option value="KOMITE PPI">KOMITE PPI</option>
                <option value="KOMITE PENUNJANG">KOMITE PENUNJANG</option>
                <option value="KOMITE MEDIK">KOMITE MEDIK</option>
                <option value="KOMITE KEPERAWATAN">KOMITE KEPERAWATAN</option>
                <option value="RESEPSIONIS">RESEPSIONIS</option>
                <option value="AKREDITASI">AKREDITASI</option>
                <option value="CASE MANAGER">CASE MANAGER</option>
                <option value="SATUAN PENGAWAS INTERNAL">SATUAN PENGAWAS INTERNAL</option>
                <option value="BAGIAN ADMINISTRASI UMUM DAN KEUANGAN">BAGIAN ADMINISTRASI UMUM DAN KEUANGAN</option>
                <option value="BIDANG PELAYANAN MEDIK DAN PENUNJANG MEDIK">BIDANG PELAYANAN MEDIK DAN PENUNJANG MEDIK</option>
                <option value="BIDANG KEPERAWATAN DAN KEBIDANAN">BIDANG KEPERAWATAN DAN KEBIDANAN</option>
                <option value="BIDANG PELAYANAN KEFARMASIAN DAN PENUNJANG">BIDANG PELAYANAN KEFARMASIAN DAN PENUNJANG</option>
              </optgroup>
            </select>
          </div>

          <!-- DROPDOWN BERTINGKAT -->
          <div class="form-group">
            <label>Nama Barang (Kategori)</label>
            <select class="form-control" id="addNamaBarang" name="nama_barang" required>
              <option value="" disabled selected>-- Pilih Jenis Barang --</option>
              <option value="Printer">Printer</option>
              <option value="Komputer">Komputer</option>
              <option value="Monitor">Monitor</option>
            </select>
          </div>

          <div class="form-group">
            <label>Merek</label>
            <select class="form-control" id="addMerek" name="merek" required>
              <option value="" disabled selected>-- Pilih Merek --</option>
            </select>
          </div>

          <div class="form-group">
            <label>Seri / Tipe</label>
            <select class="form-control" id="addSeriTipe" name="seri_tipe" required>
              <option value="" disabled selected>-- Pilih Seri --</option>
            </select>
          </div>

          <div class="form-group"><label>Keterangan</label><textarea class="form-control" id="addKeterangan" name="keterangan" rows="2"></textarea></div>
          <div class="form-group">
            <label>Status Data</label>
            <select class="form-control" id="addStatusFix" name="status_fix">
              <option value="belum_fix">Belum Fix (Kuning)</option>
              <option value="fix">Sudah Fix (Biru)</option>
            </select>
          </div>
          <div class="form-group"><label>Status</label>
            <select class="form-control" id="addStatus" name="status"><option>Berfungsi</option><option>Dalam Perbaikan</option><option>Rusak</option></select>
          </div>
          <div class="form-group"><label>Foto Barang (Opsional)</label><input type="file" class="form-control" id="addFoto" name="foto"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="tutupModalTambah()">Batal</button>
          <button type="submit" class="btn btn-primary" id="saveBarang" style="background: #1e5fa6;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL LIHAT RUANGAN -->
<div class="modal fade" id="modalLihatRuangan" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800;"><i class="fa fa-list text-primary"></i> Daftar Inventaris: <span id="titleRuangan"></span></h4>
      </div>
      <div class="modal-body" style="overflow-x: auto;">
        <table class="table table-bordered table-hover" id="tabelDetailRuangan">
        <thead>
            <tr>
              <th>Nama Barang</th>
              <th>Merek</th>
              <th>Seri / Tipe</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>Fix</th>
              <th>Foto</th>
              <th style="text-align: center; width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEditBarang" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800;"><i class="fa fa-pencil text-primary"></i> Edit Barang</h4>
      </div>
      <form id="formEditBarang" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" id="editId" name="id_komputer">
          <div class="form-group"><label>Kategori</label><input type="text" class="form-control" id="editKategori" name="kategori" required></div>
          
          <div class="form-group">
            <label>Nama Barang (Kategori)</label>
            <select class="form-control" id="editNamaBarang" name="nama_barang" required>
              <option value="" disabled>-- Pilih Jenis Barang --</option>
              <option value="Printer">Printer</option>
              <option value="Komputer">Komputer</option>
              <option value="Monitor">Monitor</option>
            </select>
          </div>

          <div class="form-group">
            <label>Merek</label>
            <select class="form-control" id="editMerek" name="merek" required>
              <option value="" disabled>-- Pilih Merek --</option>
            </select>
          </div>

          <div class="form-group">
            <label>Seri / Tipe</label>
            <select class="form-control" id="editSeriTipe" name="seri_tipe" required>
              <option value="" disabled>-- Pilih Seri --</option>
            </select>
          </div>

          <div class="form-group"><label>Keterangan</label><textarea class="form-control" id="editKeterangan" name="keterangan" rows="2"></textarea></div>
          <div class="form-group">
            <label>Status Data</label>
            <select class="form-control" id="editStatusFix" name="status_fix">
              <option value="belum_fix">Belum Fix (Kuning)</option>
              <option value="fix">Sudah Fix (Biru)</option>
            </select>
          </div>
          <div class="form-group"><label>Status</label>
            <select class="form-control" id="editStatus" name="status"><option>Berfungsi</option><option>Dalam Perbaikan</option><option>Rusak</option></select>
          </div>
          <div class="form-group"><label>Ganti Foto (Opsional)</label><input type="file" class="form-control" id="editFoto" name="foto"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="updateBarang" style="background: #1e5fa6;">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL PINDAH RUANGAN -->
<div class="modal fade" id="modalPindah" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background: #fff4e6; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="modal-title" style="font-weight: 800; color: #ff922b;"><i class="fa fa-exchange"></i> Pindah Ruangan</h4>
      </div>
      <form id="formPindah" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" id="pindahId" name="id_komputer">
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" class="form-control" id="pindahNama" readonly style="background:#eee;">
          </div>
          <div class="form-group">
            <label>Ruangan Saat Ini (Asal)</label>
            <input type="text" class="form-control" id="pindahAsal" readonly style="background:#eee;">
          </div>
          <div class="form-group">
            <label>Pindah Ke Ruangan (Tujuan)</label>
            <select class="form-control" id="pindahTujuan" name="ruangan_tujuan" required>
                <option value="" disabled selected>-- Pilih Ruangan Tujuan --</option>
                <optgroup label="Poliklinik">
                  <option value="Poli Anak">Poli Anak</option>
                  <option value="Poli Bedah">Poli Bedah</option>
                  <option value="Poli Gigi">Poli Gigi</option>
                  <option value="Poli Gizi">Poli Gizi</option>
                  <option value="Poli Jantung">Poli Jantung</option>
                  <option value="Poli Jiwa">Poli Jiwa</option>
                  <option value="Poli Kandungan">Poli Kandungan</option>
                  <option value="Poli Kulit & Kelamin">Poli Kulit & Kelamin</option>
                  <option value="Poli Mata">Poli Mata</option>
                  <option value="Poli MCU">Poli MCU</option>
                  <option value="Poli Paru">Poli Paru</option>
                  <option value="Poli Penyakit Dalam">Poli Penyakit Dalam</option>
                  <option value="Poli Psikologi">Poli Psikologi</option>
                  <option value="Instalasi Rehab Medik">Instalasi Rehab Medik</option>
                  <option value="Poli Saraf">Poli Saraf</option>
                  <option value="Poli THT">Poli THT</option>
                  <option value="Poli VCT">Poli VCT</option>
                  <option value="Poli Orthopedi">Poli Orthopedi</option>
                  <option value="Poli Geriatri">Poli Geriatri</option>
                  <option value="Poli Urologi">Poli Urologi</option>
                </optgroup>
                <optgroup label="Rawat Inap">
                  <option value="Ruang VK Bersalin">Ruang VK Bersalin</option>
                  <option value="Rawat Inap Binjai (ICU)">Rawat Inap Binjai (ICU)</option>
                  <option value="Rawat Inap Langsat (Anak)">Rawat Inap Langsat (Anak)</option>
                  <option value="Rawat Inap Rambutan (Bedah)">Rawat Inap Rambutan (Bedah)</option>
                  <option value="Rawat Inap Hambawang (Nifas)">Rawat Inap Hambawang (Nifas)</option>
                  <option value="Rawat Inap Pampaken (Isolasi)">Rawat Inap Pampaken (Isolasi)</option>
                   <option value="Rawat Inap Kasturi (VIP/VVIP)">Rawat Inap Kasturi (VIP/VVIP)</option>
                  <option value="Rawat Inap Rambai (Penyakit Dalam)">Rawat Inap Rambai (Penyakit Dalam)</option>
                  <option value="Rawat Inap Karamunting (Bayi dan Isolasi Bayi)">Rawat Inap Karamunting (Bayi)</option>
                  <option value="Rawat Inap Ramania Anak dan Dewasa (VIP)">Rawat Inap Ramania Anak dan Dewasa (VIP)</option>
                </optgroup>
                <optgroup label="Instalasi">
                  <option value="IGD PONEK">IGD PONEK</option>
                  <option value="Laboratorium">Laboratorium</option>
                  <option value="Instalasi Farmasi">Instalasi Farmasi</option>
                  <option value="Instalasi Gawat Darurat">Instalasi Gawat Darurat</option>
                  <option value="Instalasi Radiologi">Instalasi Radiologi</option>
                  <option value="Instalasi Laboratorium">Instalasi Laboratorium</option>
                  <option value="Instalasi Bedah Sentral">Instalasi Bedah Sentral</option>
                  <option value="Instalasi Rekam Medis">Instalasi Rekam Medis</option>
                  <option value="Instalasi Sanitasi">Instalasi Sanitasi</option>
                  <option value="Instalasi CSSD dan Laundry">Instalasi CSSD dan Laundry</option>
                  <option value="Instalasi Gizi">Instalasi Gizi</option>
                  <option value="Instalasi K3">Instalasi K3</option>
                  <option value="Instalasi Logistik">Instalasi Logistik</option>
                  <option value="Instalasi Ambulan">Instalasi Ambulan</option>
                  <option value="Instalasi Pengaduan Masyarakat">Instalasi Pengaduan Masyarakat</option>
                  <option value="Instalasi Pemulasaran Jenazah">Instalasi Pemulasaran Jenazah</option>
                  <option value="Instalasi Gas Medis">Instalasi Gas Medis</option>
                  <option value="Instalasi Pusat Data dan Informasi Elektronik">Instalasi Pusat Data dan Informasi Elektronik</option>
                  <option value="Patologi Anatomi">Patologi Anatomi</option>
                  <option value="Hemodialisa">Hemodialisa</option>
                  <option value="IPSRS">IPSRS</option>
                </optgroup>
                <optgroup label="Komite & Manajemen">
                  <option value="JKN">JKN</option>
                  <option value="KOMITE PPI">KOMITE PPI</option>
                  <option value="KOMITE PENUNJANG">KOMITE PENUNJANG</option>
                  <option value="KOMITE MEDIK">KOMITE MEDIK</option>
                  <option value="KOMITE KEPERAWATAN">KOMITE KEPERAWATAN</option>
                  <option value="RESEPSIONIS">RESEPSIONIS</option>
                  <option value="AKREDITASI">AKREDITASI</option>
                  <option value="CASE MANAGER">CASE MANAGER</option>
                  <option value="SATUAN PENGAWAS INTERNAL">SATUAN PENGAWAS INTERNAL</option>
                  <option value="BAGIAN ADMINISTRASI UMUM DAN KEUANGAN">BAGIAN ADMINISTRASI UMUM DAN KEUANGAN</option>
                  <option value="BIDANG PELAYANAN MEDIK DAN PENUNJANG MEDIK">BIDANG PELAYANAN MEDIK DAN PENUNJANG MEDIK</option>
                  <option value="BIDANG KEPERAWATAN DAN KEBIDANAN">BIDANG KEPERAWATAN DAN KEBIDANAN</option>
                  <option value="BIDANG PELAYANAN KEFARMASIAN DAN PENUNJANG">BIDANG PELAYANAN KEFARMASIAN DAN PENUNJANG</option>
                </optgroup>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan/Alasan Pindah</label>
            <textarea class="form-control" id="pindahKeterangan" name="keterangan" rows="2" placeholder="Contoh: Rotasi user, perbaikan, dll"></textarea>
          </div>
          <div class="form-group">
            <label>Upload Foto Baru (Opsional)</label>
            <input type="file" class="form-control" id="pindahFoto" name="foto" accept="image/*">
            <small style="color: #888; font-size: 11px;">Biarkan kosong jika tidak ingin mengubah foto lama.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning" id="savePindah" style="color:white;">Proses Pindah</button>
        </div>
      </form>
    </div>
  </div>
</div>