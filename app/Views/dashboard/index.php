<style>
  /* Memperbaiki Layout Utama agar sejajar */
  .content { padding: 0 30px 30px 30px !important; width: 100%; box-sizing: border-box; }
  .custom-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); margin-bottom: 25px; width: 100%; box-sizing: border-box; }
  
  /* Header Banner */
  .hero-banner {
    background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>');
    background-size: cover; background-position: center;
    border-radius: 20px; padding: 30px; margin-bottom: 25px;
    display: flex; justify-content: space-between; align-items: center;
    width: 100%; box-sizing: border-box;
  }
  .hero-text h2 { font-size: 32px; font-weight: 800; margin: 0; color: #1a1a1a; }
  .hero-text p { font-size: 16px; color: #555; margin-top: 5px; }
  
  /* Kartu Statistik */
  .stat-row { display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
  .stat-card { flex: 1; min-width: 200px; display: flex; align-items: center; gap: 15px; background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; }
  .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 24px; flex-shrink: 0; }
  .stat-info { display: flex; flex-direction: column; }
  .stat-title { margin: 0; color: #888; font-size: 14px; }
  .stat-value { margin: 5px 0; font-weight: 800; color: #1a1a1a; font-size: 24px; }
  .stat-trend { color: #20c997; font-size: 12px; font-weight: 700; }
  .stat-sub { color: #888; font-size: 12px; }

  /* Area Grafik & Banner Kanan */
  .content-row { display: flex; gap: 25px; margin-bottom: 25px; flex-wrap: wrap; }
  .chart-area { flex: 2; min-width: 300px; background: #fff; padding: 25px; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); }
  .banner-area { flex: 1; min-width: 300px; background: #fff; padding: 0; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); overflow: hidden; position: relative; }
  .banner-area img { width: 100%; height: 200px; object-fit: cover; }
  .banner-text { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(30,95,166,0.9), transparent); padding: 20px; color: white; }
</style>

<!-- HERO BANNER -->
<div class="hero-banner">
  <div class="hero-text">
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Selamat Datang,</p>
    <h2><?= session()->get('nama') ? session()->get('nama') : 'Nadifa Nabila' ?></h2>
    <p>Semoga hari ini penuh dengan hal baik</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; flex-shrink: 0; margin-left: 20px;">
    <i class="fa fa-calendar" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;"><?= date('l, d F Y') ?></p>
    <p style="margin: 0; font-size: 13px; color: #888;"><?= date('H:i') ?> WIB</p>
  </div>
</div>

<!-- KARTU STATISTIK (data real dari database) -->
<?php
$kartu = [
    ['Sedang Di Service', (int) ($stats['sedang_service'] ?? 0), 'fa-wrench', '#e6f0fa', '#1e5fa6', 'Barang berstatus "Dalam Perbaikan"'],
    ['Selesai Service', (int) ($stats['selesai_service'] ?? 0), 'fa-check-circle', '#e6fcf5', '#20c997', 'Barang berstatus "Selesai Perbaikan"'],
    ['Rusak', (int) ($stats['rusak'] ?? 0), 'fa-exclamation-triangle', '#f3f1ff', '#7b6cf6', 'Barang rusak yang belum selesai'],
    ['Penghapusan Aset', (int) ($stats['penghapusan_aset'] ?? 0), 'fa-trash', '#fff4e6', '#ff922b', 'Barang yang sudah dihapus asetnya'],
];
?>
<div class="stat-row">
  <?php foreach ($kartu as [$judul, $nilai, $ikon, $bg, $warna, $ket]): ?>
    <div class="stat-card">
      <div class="stat-icon" style="background: <?= $bg ?>; color: <?= $warna ?>;"><i class="fa <?= $ikon ?>"></i></div>
      <div class="stat-info">
        <p class="stat-title"><?= esc($judul) ?></p>
        <h3 class="stat-value"><?= number_format($nilai, 0, ',', '.') ?></h3>
        <span class="stat-sub"><?= esc($ket) ?></span>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- RINGKASAN DATA -->
<div class="stat-row">
  <div class="stat-card">
    <div class="stat-icon" style="background: #eaf3ff; color: #2563eb;"><i class="fa fa-desktop"></i></div>
    <div class="stat-info">
      <p class="stat-title">Total Inventaris</p>
      <h3 class="stat-value"><?= number_format((int) ($stats['total_inventaris'] ?? 0), 0, ',', '.') ?></h3>
      <span class="stat-sub">Barang tercatat</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #f1f5f9; color: #475569;"><i class="fa fa-file-text-o"></i></div>
    <div class="stat-info">
      <p class="stat-title">Service Report</p>
      <h3 class="stat-value"><?= number_format((int) ($stats['total_service_report'] ?? 0), 0, ',', '.') ?></h3>
      <span class="stat-sub">Dokumen service</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #fdf2f8; color: #db2777;"><i class="fa fa-archive"></i></div>
    <div class="stat-info">
      <p class="stat-title">Arsip Dokumen</p>
      <h3 class="stat-value"><?= number_format((int) ($stats['total_arsip'] ?? 0), 0, ',', '.') ?></h3>
      <span class="stat-sub">Dokumen tersimpan</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #fefce8; color: #ca8a04;"><i class="fa fa-cogs"></i></div>
    <div class="stat-info">
      <p class="stat-title">Antrian Sparepart</p>
      <h3 class="stat-value"><?= number_format((int) ($stats['antrian_sparepart'] ?? 0), 0, ',', '.') ?></h3>
      <span class="stat-sub">Menunggu / diproses</span>
    </div>
  </div>
</div>


<!-- AREA GRAFIK & BANNER KANAN -->
<div class="content-row">
  <!-- Grafik Kiri -->
  <div class="chart-area">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h4 style="margin: 0; font-weight: 800; color: #1a1a1a;">Tren Service Komputer dan Printer</h4>
      <select class="form-control" style="width: 150px; border-radius: 10px; border: 1px solid #ddd;">
        <option>7 Hari Terakhir</option>
      </select>
    </div>
    <p style="color: #888; font-size: 13px; margin-bottom: 20px;">Data service perangkat dalam 8 hari terakhir</p>
    
    <!-- Grafik dari data 8 hari terakhir -->
    <?php
    $labelGrafik  = $grafik['labels'] ?? [];
    $jumlahTitik  = max(1, count($labelGrafik));
    $nilaiService = $grafik['service'] ?? [];
    $nilaiRusak   = $grafik['kerusakan'] ?? [];
    $nilaiMaks    = max(1, max(array_merge([0], $nilaiService, $nilaiRusak)));

    $buatTitik = static function (array $deret) use ($jumlahTitik, $nilaiMaks): string {
        $titik = [];
        foreach ($deret as $i => $nilai) {
            $x = $jumlahTitik > 1 ? ($i * 100 / ($jumlahTitik - 1)) : 0;
            $y = 95 - (((int) $nilai / $nilaiMaks) * 85);
            $titik[] = round($x, 1) . ',' . round($y, 1);
        }

        return implode(' ', $titik);
    };
    ?>
    <div style="height: 250px; border-bottom: 2px solid #f0f0f0; border-left: 2px solid #f0f0f0; position: relative;">
       <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
         <polyline fill="none" stroke="#1e5fa6" stroke-width="2" points="<?= $buatTitik($nilaiService) ?>" />
         <polyline fill="none" stroke="#ff922b" stroke-width="2" points="<?= $buatTitik($nilaiRusak) ?>" />
       </svg>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: 11px; color: #94a3b8;">
      <?php foreach ($labelGrafik as $label): ?>
        <span><?= esc($label) ?></span>
      <?php endforeach; ?>
    </div>
    <div style="display: flex; gap: 20px; margin-top: 12px; font-size: 12px; color: #64748b;">
      <span><i class="fa fa-circle" style="color: #1e5fa6;"></i> Service Report</span>
      <span><i class="fa fa-circle" style="color: #ff922b;"></i> Kerusakan</span>
      <span style="margin-left: auto;">Total 8 hari: <?= array_sum($nilaiService) ?> service / <?= array_sum($nilaiRusak) ?> kerusakan</span>
    </div>
  </div>

  <!-- Banner Kanan -->
  <div class="banner-area">
    <img src="<?= base_url('assets/images/Background_rsss.png') ?>" alt="Service">
    <div class="banner-text">
      <h4 style="margin: 0; font-weight: 800; color: #fff;">Hannas Computer & Printer</h4>
      <p style="margin: 5px 0 15px 0; font-size: 13px;">Melayani Service dengan Cepat, Tepat, dan Bergaransi</p>
      <button class="btn btn-default btn-sm" style="border-radius: 20px; font-weight: 700; padding: 8px 20px;">Tentang Kami <i class="fa fa-arrow-right"></i></button>
    </div>
  </div>
</div>