<style>
  /* Mengatur agar konten pas di layar */
  .content { padding: 0 20px 30px 20px !important; width: 100%; max-width: 100%; }
  .custom-card { background: #fff; border-radius: 15px; padding: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); margin-bottom: 20px; }
  
  /* Header Banner */
  .header-banner {
    background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>');
    background-size: cover; background-position: center;
    border-radius: 15px; padding: 20px 25px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center;
    width: 100%; box-sizing: border-box; flex-wrap: wrap; gap: 15px;
  }
  .header-icon { background: #e6f0fa; color: #1e5fa6; width: 60px; height: 60px; border-radius: 15px; display: flex; justify-content: center; align-items: center; font-size: 26px; margin-right: 15px; flex-shrink: 0; }
  .header-title { font-size: 22px; font-weight: 800; margin: 0; color: #1a1a1a; }

  /* Desain Tabel yang lebih ringkas */
  .table-responsive-custom { width: 100%; overflow-x: hidden; }
  .table-custom { width: 100%; border-collapse: separate; border-spacing: 0 8px; table-layout: fixed; }
  
  /* Header Tabel Dipertebal */
  .table-custom thead th { 
    background: #f8fafc; padding: 12px 10px; font-size: 13px; font-weight: 800; /* Dipertebal */
    color: #475569; text-align: left; border: none; white-space: nowrap; 
    text-transform: uppercase; letter-spacing: 0.5px;
  }
  
  /* Isi Tabel Dipertebal */
  .table-custom tbody tr { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s; }
  .table-custom tbody td { 
    padding: 12px 10px; vertical-align: middle; border-top: 1px solid #f1f5f9; 
    border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 600; /* Dipertebal */
    color: #1e293b; word-wrap: break-word; 
  }
  .table-custom tbody tr td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
  .table-custom tbody tr td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

  /* Pengaturan Lebar & Rata Tengah Kolom */
  .col-no { width: 5%; text-align: center; }
  .col-nama { width: 30%; color: #1e5fa6; font-weight: 800; /* Nama barang paling tebal */ }
  .col-stok { width: 10%; text-align: center; } /* Rata Tengah */
  .col-foto { width: 10%; text-align: center; } /* Rata Tengah */
  .col-ket { width: 45%; color: #475569; }

  /* Badge Stok Dipertebal */
  .badge-stok { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; display: inline-block; }
  .stok-aman { background: #e6fcf5; color: #0ca678; border: 1px solid #b2f2bb; }
  .stok-menipis { background: #fff4e6; color: #f08c00; border: 1px solid #ffe8cc; }
  
  /* Styling Foto */
  .img-thumbnail-custom {
    width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;
    transition: transform 0.2s;
  }
  .img-thumbnail-custom:hover { transform: scale(1.1); }
</style>

<!-- HEADER BANNER -->
<div class="header-banner">
  <div class="d-flex align-items-center" style="flex: 1; min-width: 250px;">
    <div class="header-icon"><i class="fa fa-database"></i></div>
    <div>
      <p style="color: #1e5fa6; font-weight: 700; margin: 0; font-size: 13px;">Instalasi Pusat Data Elektronik (PDE)</p>
      <h1 class="header-title">Stok Sparepart</h1>
      <p style="color: #555; margin: 0; font-size: 12px; font-weight: 600;">Daftar sparepart yang tersedia di gudang. Data diambil langsung dari Pengadaan Barang PDE</p>
    </div>
  </div>
</div>

<!-- KONTEN TABEL -->
<div class="custom-card">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 15px;">
    <div>
      <h4 style="font-weight: 800; margin: 0; font-size: 16px;"><i class="fa fa-box text-primary"></i> Data Stok Aktual</h4>
    </div>
    <div>
      <input type="text" id="searchSparepart" class="form-control" placeholder="Cari sparepart..." style="border-radius: 10px; width: 220px; font-size: 13px; font-weight: 600;">
    </div>
  </div>

  <div class="table-responsive-custom">
    <table class="table-custom" id="tabelSparepart">
      <thead>
        <tr>
          <th class="col-no">No</th>
          <th class="col-nama">Nama Sparepart</th>
          <th class="col-stok" style="text-align: center;">Stok</th>
          <th class="col-foto" style="text-align: center;">Foto</th>
          <th class="col-ket">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($sparepart)): ?>
          <tr>
            <td colspan="5" style="text-align: center; padding: 30px; color: #888; font-weight: 600;">Belum ada data sparepart dengan stok tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $no = 1; foreach($sparepart as $s): ?>
          <tr>
            <td class="col-no"><?= $no++ ?></td>
            <td class="col-nama"><?= esc($s['part_number']) ?></td>
            
            <!-- Rata Tengah untuk Stok -->
            <td class="col-stok">
              <?php $badgeClass = ($s['stock_actual'] <= 5) ? 'stok-menipis' : 'stok-aman'; ?>
              <span class="badge-stok <?= $badgeClass ?>">
                <?= $s['stock_actual'] ?>
              </span>
            </td>
            
            <!-- Rata Tengah untuk Foto -->
            <td class="col-foto">
              <?php if (!empty($s['dokumentasi'])): ?>
                <a href="<?= base_url('uploads/' . $s['dokumentasi']) ?>" target="_blank">
                  <img src="<?= base_url('uploads/' . $s['dokumentasi']) ?>" class="img-thumbnail-custom" alt="Foto">
                </a>
              <?php else: ?>
                <span style="color: #94a3b8; font-size: 11px; font-weight: 700;">No Image</span>
              <?php endif; ?>
            </td>

            <td class="col-ket"><?= $s['description'] ?? '-' ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $("#searchSparepart").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tabelSparepart tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>