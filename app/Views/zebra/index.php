<!-- HEADER BANNER -->
<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Desain & Cetak,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;">Label Zebra Printer</h2>
    <p style="margin-top: 5px; color: #555;">Buat dan kelola desain label aset dengan mudah</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
    <i class="fa fa-print" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;">Zebra ZPL</p>
    <p style="margin: 0; font-size: 13px; color: #888;">Ready to Print</p>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success" style="border-radius: 10px; font-weight: 600;"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600;"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- CARD UTAMA -->
<div class="custom-card" style="padding: 25px; border-radius: 15px; margin-bottom: 25px;">
    <div class="row">
        <!-- Kolom Kiri: Form Input -->
        <div class="col-md-5">
            <h4 style="margin: 0 0 20px 0; font-weight: 800; color: #1a1a1a; font-size: 18px;">Buat Desain Baru</h4>
            
            <form action="<?= base_url('zebra_label/save') ?>" method="post" id="formLabel">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label style="font-weight: 600; color: #64748b; font-size: 14px;">Nama Desain</label>
                    <input type="text" name="nama_desain" class="form-control" required placeholder="Cth: Label Aset IT" style="border-radius: 10px; padding: 10px; border: 1px solid #ddd;">
                </div>
                
                <div class="form-group">
                    <label style="font-weight: 600; color: #64748b; font-size: 14px;">Ukuran Kertas</label>
                    <select name="ukuran_kertas" id="ukuran_kertas" class="form-control" onchange="generateZPL()" style="border-radius: 10px; padding: 10px; border: 1px solid #ddd;">
                        <option value="50x25">50mm x 25mm</option>
                        <option value="100x50">100mm x 50mm</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label style="font-weight: 600; color: #64748b; font-size: 14px;">Teks Baris 1 (ID/Barcode)</label>
                    <input type="text" id="baris1" class="form-control" onkeyup="generateZPL()" placeholder="Cth: IT-2026-001" style="border-radius: 10px; padding: 10px; border: 1px solid #ddd;">
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; color: #64748b; font-size: 14px;">Teks Baris 2 (Nama Barang)</label>
                    <input type="text" id="baris2" class="form-control" onkeyup="generateZPL()" placeholder="Cth: Laptop Asus" style="border-radius: 10px; padding: 10px; border: 1px solid #ddd;">
                </div>
                
                <input type="hidden" name="zpl_code" id="zpl_code">
                
                <button type="submit" class="btn btn-primary" style="background: #1e5fa6; border-radius: 10px; font-weight: 600; padding: 10px 20px; width: 100%;">
                    <i class="fa fa-save"></i> Simpan Desain
                </button>
            </form>
        </div>

        <!-- Kolom Kanan: Preview & ZPL -->
        <div class="col-md-7">
            <h4 style="margin: 0 0 20px 0; font-weight: 800; color: #1a1a1a; font-size: 18px;">Preview & Kode ZPL</h4>
            
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 20px; margin-bottom: 20px; font-family: monospace;">
                <div id="prev_baris1" style="font-weight:bold; font-size:24px; color: #1e293b;">-</div>
                <div id="prev_baris2" style="font-size:18px; color: #64748b;">-</div>
            </div>
            
            <div class="form-group">
                <label style="font-weight: 600; color: #64748b; font-size: 14px;">Kode ZPL (Otomatis)</label>
                <textarea id="output_zpl" class="form-control bg-dark text-success" rows="6" readonly style="font-family: monospace; border-radius: 10px; padding: 10px; border: 1px solid #ddd;"></textarea>
            </div>
        </div>
    </div>
</div>

<!-- TABEL DATA DESAIN -->
<div class="custom-card" style="padding: 25px; border-radius: 15px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 15px;">
      <div style="background: #e6f0fa; color: #1e5fa6; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 20px;"><i class="fa fa-print"></i></div>
      <div>
        <h4 style="margin: 0; font-weight: 800; color: #1a1a1a;">Daftar Desain Label</h4>
        <p style="margin: 0; color: #888; font-size: 13px;">Desain yang siap dicetak</p>
      </div>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table class="table table-hover table-bordered" style="margin-bottom: 0; width: 100%; table-layout: fixed;">
      <thead style="background: #f8fafc;">
        <tr>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 5%; text-align: center;">No</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 30%;">Nama Desain</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 20%; text-align: center;">Ukuran Kertas</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 25%; text-align: center;">Tanggal Dibuat</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 20%; text-align: center;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($labels)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada desain label.</td></tr>
        <?php else: ?>
            <?php $no = 1; foreach($labels as $row): ?>
            <tr>
              <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= $no++ ?></td>
              <td style="padding: 15px; vertical-align: middle; font-weight: 600; color: #1e5fa6; word-wrap: break-word;"><?= esc($row['nama_desain']) ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;">
                  <span style="background: #e6f0fa; color: #1e5fa6; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap;"><?= esc($row['ukuran_kertas']) ?></span>
              </td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center; white-space: nowrap;">
                <button class="btn btn-sm btn-primary detail-btn" 
                        data-zpl="<?= htmlspecialchars($row['zpl_code'], ENT_QUOTES) ?>" 
                        style="border-radius: 8px; background: #1e5fa6;">
                    <i class="fa fa-eye"></i>
                </button>
                <form action="<?= base_url('zebra_label/delete/'.$row['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus desain ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; background: #fa5252;"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
    function generateZPL() {
        let baris1 = document.getElementById('baris1').value;
        let baris2 = document.getElementById('baris2').value;

        document.getElementById('prev_baris1').innerText = baris1 || '-';
        document.getElementById('prev_baris2').innerText = baris2 || '-';

        let zpl = `^XA\n^PW400\n^LL200\n`;
        zpl += `^FO20,20^A0N,30,30^FD${baris1}^FS\n`;
        zpl += `^FO20,60^A0N,20,20^FD${baris2}^FS\n`;
        zpl += `^XZ`;

        document.getElementById('zpl_code').value = zpl;
        document.getElementById('output_zpl').value = zpl;
    }

    // Fungsi untuk tombol detail
    document.querySelectorAll(".detail-btn").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            var zplCode = this.getAttribute("data-zpl");
            
            // Tampilkan di alert (bisa diganti modal nanti)
            alert("Kode ZPL Tersimpan:\n\n" + zplCode);
        });
    });
</script>