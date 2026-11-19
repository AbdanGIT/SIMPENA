<style>
  .content { padding: 0 30px 30px 30px !important; width: 100% !important; }
  .custom-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); margin-bottom: 20px; width: 100%; box-sizing: border-box; }
  
  /* Header Banner */
  .header-banner {
    background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>');
    background-size: cover; background-position: center;
    border-radius: 20px; padding: 25px 30px; margin-bottom: 25px;
    display: flex; justify-content: space-between; align-items: center;
    width: 100%; box-sizing: border-box; flex-wrap: wrap; gap: 20px;
  }
  .header-icon { background: #fff4e6; color: #ff922b; width: 70px; height: 70px; border-radius: 18px; display: flex; justify-content: center; align-items: center; font-size: 32px; margin-right: 20px; flex-shrink: 0; }
  .header-title { font-size: 26px; font-weight: 800; margin: 0; color: #1a1a1a; }

  /* Tabel */
  .table-custom { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
  .table-custom thead th { background: #f8fafc; padding: 15px; font-size: 13px; font-weight: 700; color: #64748b; text-align: left; border: none; white-space: nowrap; }
  .table-custom tbody tr { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s; }
  .table-custom tbody tr:hover { box-shadow: 0 4px 10px rgba(0,0,0,0.08); transform: scale(1.002); }
  .table-custom tbody td { padding: 15px; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #334155; }
  .table-custom tbody tr td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
  .table-custom tbody tr td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
  
  .badge-custom { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; white-space: nowrap; }

  /* Lampiran yang berupa dokumen (PDF/dll) tidak bisa dipakai di <img>,
     jadi ditampilkan sebagai kartu tautan. */
  .lampiran-dokumen {
    display: inline-flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 2px; width: 45px; height: 45px; border-radius: 6px; border: 1px solid #ddd;
    background: #fef2f2; color: #dc2626; text-decoration: none; font-size: 9px; font-weight: 700;
    letter-spacing: .5px; line-height: 1;
  }
  .lampiran-dokumen:hover, .lampiran-dokumen:focus { background: #fee2e2; color: #b91c1c; text-decoration: none; }
  .lampiran-dokumen i { font-size: 16px; }

  /* ==========================================
     PERBAIKAN: KOLOM KETERANGAN AGAR WRAP
     ========================================== */
  .table-custom tbody td {
      white-space: normal !important; /* Mengizinkan teks turun baris */
      word-wrap: break-word !important; /* Memotong kata yang terlalu panjang */
      vertical-align: top !important; /* Teks rata atas agar rapi */
  }

  /* Khusus untuk kolom Keterangan (kolom terakhir) */
  .table-custom tbody td:last-child {
      min-width: 250px; /* Lebar minimum */
      max-width: 400px; /* Lebar maksimum agar tidak terlalu panjang */
      line-height: 1.6;
  }
</style>

<?php
/**
 * Lampiran riwayat mutasi diambil dari kolom `foto` milik barang.
 * Data lama hasil impor bisa berisi dokumen (mis. PDF), bukan gambar, sehingga
 * tidak boleh dirender memakai <img> (penyebab gambar rusak di tabel).
 *
 * @return array{nama:string,ext:string,jenis:string,url:string,ada:bool}|null
 */
$lampiranMutasi = static function (?string $nama): ?array {
    $nama = trim((string) $nama);
    if ($nama === '') {
        return null;
    }

    // basename() mencegah nama berisi "../" ikut terbaca sebagai path.
    $nama  = basename($nama);
    $ext   = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
    $jenis = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'avif'], true)
        ? 'gambar'
        : ($ext === 'pdf' ? 'pdf' : 'dokumen');

    return [
        'nama'  => $nama,
        'ext'   => $ext,
        'jenis' => $jenis,
        'url'   => base_url('uploads/inventaris/' . rawurlencode($nama)),
        'ada'   => is_file(FCPATH . 'uploads/inventaris/' . $nama),
    ];
};
?>

<!-- HEADER BANNER -->
<div class="header-banner">
  <div class="d-flex align-items-center" style="flex: 1; min-width: 250px;">
    <div class="header-icon"><i class="fa fa-exchange"></i></div>
    <div>
      <p style="color: #ff922b; font-weight: 600; margin: 0; font-size: 14px;">Instalasi Pusat Data Elektronik (PDE)</p>
      <h1 class="header-title">Riwayat Perpindahan Komputer</h1>
      <p style="color: #555; margin: 0; font-size: 13px;">Catatan mutasi inventaris antar ruangan di RSUD Sultan Suriansyah.</p>
    </div>
  </div>
</div>

<!-- KONTEN TABEL -->
<div class="custom-card">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4" style="gap: 15px;">
    <div>
      <h4 style="font-weight: 800; margin: 0; font-size: 18px;"><i class="fa fa-history text-warning"></i> Log Perpindahan</h4>
      <p style="margin: 0; font-size: 13px; color: #888;">Semua catatan barang yang telah dipindahkan</p>
    </div>
    <div>
      <input type="text" id="searchRiwayat" class="form-control" placeholder="Cari barang / ruangan..." style="border-radius: 10px; width: 250px;">
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table class="table-custom" id="tabelRiwayat">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal Pindah</th>
            <th>Nama Barang</th>
            <th>Ruangan Asal</th>
            <th>Ruangan Tujuan</th>
            <th>Foto</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      <tbody>
        <?php if(empty($riwayat)): ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 30px; color: #888;">Belum ada riwayat perpindahan.</td>
          </tr>
        <?php else: ?>
          <?php $no = 1; foreach($riwayat as $r): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td>
              <span class="badge-custom" style="background: #e0f2fe; color: #0284c7;">
                <i class="fa fa-calendar"></i> <?= date('d M Y - H:i', strtotime($r['tanggal_mutasi'])) ?> WITA
              </span>
            </td>
            <td style="font-weight: 600; color: #1e5fa6;"><?= esc($r['nama_barang']) ?></td>
            <td><span class="badge-custom" style="background:#ffe3e3; color:#fa5252;"><?= esc($r['ruangan_asal']) ?></span></td>
            <td><span class="badge-custom" style="background:#e6fcf5; color:#20c997;"><?= esc($r['ruangan_tujuan']) ?></span></td>
            
            <!-- TAMPILKAN LAMPIRAN (gambar = thumbnail, PDF/dokumen = kartu tautan) -->
            <td>
              <?php $lampiran = $lampiranMutasi($r['foto'] ?? null); ?>
              <?php if ($lampiran === null): ?>
                <span style="color: #aaa; font-size: 12px;">-</span>
              <?php elseif (! $lampiran['ada']): ?>
                <span style="color: #aaa; font-size: 12px;" title="Berkas lampiran tidak ditemukan di server">-</span>
              <?php elseif ($lampiran['jenis'] === 'gambar'): ?>
                <a href="<?= $lampiran['url'] ?>" target="_blank" rel="noopener">
                  <img src="<?= $lampiran['url'] ?>" alt="Foto <?= esc($r['nama_barang']) ?>" loading="lazy"
                       style="height: 45px; width: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                </a>
              <?php else: ?>
                <a class="lampiran-dokumen" href="<?= $lampiran['url'] ?>" target="_blank" rel="noopener"
                   title="Buka lampiran <?= esc(strtoupper($lampiran['ext'] ?: 'berkas')) ?> (<?= esc($lampiran['nama']) ?>)">
                  <i class="fa <?= $lampiran['jenis'] === 'pdf' ? 'fa-file-pdf-o' : 'fa-file-o' ?>"></i>
                  <span><?= esc(strtoupper($lampiran['ext'] ?: 'FILE')) ?></span>
                </a>
              <?php endif; ?>
            </td>

            <td><?= esc($r['keterangan']) ? esc($r['keterangan']) : '-' ?></td>
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
  // Fitur Pencarian di Tabel
  $("#searchRiwayat").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tabelRiwayat tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>