<style>
  .content { padding: 0 30px 30px 30px !important; width: 100% !important; max-width: 100% !important; }
  .custom-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); margin-bottom: 20px; width: 100%; box-sizing: border-box; }
  
  .header-banner {
    background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>');
    background-size: cover; background-position: center;
    border-radius: 20px; padding: 25px 30px; margin-bottom: 25px;
    display: flex; justify-content: space-between; align-items: center;
    width: 100%; box-sizing: border-box; flex-wrap: wrap; gap: 20px;
  }
  .header-icon { background: #e6f0fa; color: #1e5fa6; width: 70px; height: 70px; border-radius: 18px; display: flex; justify-content: center; align-items: center; font-size: 32px; margin-right: 20px; flex-shrink: 0; }
  .header-title { font-size: 26px; font-weight: 800; margin: 0; color: #1a1a1a; }
  
  .stat-row { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 20px; margin-bottom: 25px; width: 100% !important; }
  .stat-box { display: flex !important; flex-direction: row !important; align-items: center !important; gap: 15px; background: #fff; padding: 20px; border-radius: 15px; border: 1px solid #f0f0f0; box-sizing: border-box; width: 100% !important; }
  .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 22px; flex-shrink: 0; }
  
  @media (max-width: 768px) { .stat-row { grid-template-columns: repeat(2, 1fr) !important; } }
  @media (max-width: 480px) { .stat-row { grid-template-columns: 1fr !important; } }

  .masonry-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; align-items: stretch; width: 100%; }
  .masonry-item { display: flex; width: 100%; }
  .poli-card { border: 2px solid #eee; border-radius: 15px; padding: 20px 15px; background: #fff; transition: all 0.3s ease; display: flex; flex-direction: column; align-items: center; text-align: center; height: 100%; width: 100%; box-sizing: border-box; }
  .poli-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.08); transform: translateY(-3px); }
  .poli-title { font-weight: 800; font-size: 14px; color: #1a1a1a; margin-bottom: 5px; line-height: 1.3; text-transform: uppercase; }
  .poli-subtitle { font-size: 12px; color: #888; margin-bottom: 12px; }
  .poli-icon-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 22px; margin-bottom: 12px; flex-shrink: 0; }

  .toolbar-container { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px; width: 100%; }
  .search-group { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  .search-group input { width: 250px; border-radius: 10px; }

  .btn-filter { border-radius: 20px !important; padding: 6px 16px !important; font-weight: 600 !important; font-size: 13px !important; border: 1px solid #ddd !important; background-color: #fff !important; color: #64748b !important; transition: all 0.2s !important; white-space: nowrap; }
  .btn-filter.active { background-color: #1e5fa6 !important; color: #fff !important; border-color: #1e5fa6 !important; }

  body.sidebar-minimize .main-sidebar { width: 80px !important; }
  body.sidebar-minimize .content-wrapper { margin-left: 80px !important; }
  body.sidebar-minimize .sidebar-menu span, body.sidebar-minimize .sidebar-menu .fa-angle-right { display: none !important; }
  body.sidebar-minimize .logo-sidebar, body.sidebar-minimize .menu-cepat-title { display: none !important; }
  body.sidebar-minimize .sidebar-menu a { justify-content: center !important; padding: 12px 0 !important; }
  body.sidebar-minimize .sidebar-menu i { font-size: 20px !important; margin: 0; }
  body.sidebar-minimize .main-sidebar > div:first-child { justify-content: center !important; }
</style>

<div class="header-banner">
  <div class="d-flex align-items-center" style="flex: 1; min-width: 250px;">
    <div class="header-icon"><i class="fa fa-cube"></i></div>
    <div>
      <p style="color: #1e5fa6; font-weight: 600; margin: 0; font-size: 14px;">Instalasi Pusat Data Elektronik (PDE)</p>
      <h1 class="header-title">Pencatatan Ruangan</h1>
      <p style="color: #555; margin: 0; font-size: 13px;">Kelola data inventaris ruangan di RSUD Sultan Suriansyah Banjarmasin.</p>
    </div>
  </div>
  <div style="background: #fff; padding: 12px 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; flex-shrink: 0;">
    <i class="fa fa-calendar" style="color: #1e5fa6; font-size: 20px; margin-bottom: 5px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333; font-size: 14px;"><?= date('l, d F Y') ?></p>
    <p style="margin: 0; font-size: 12px; color: #888;"><?= date('H:i') ?> WITA</p>
  </div>
</div>

<div class="stat-row">
  <div class="stat-box">
    <div class="stat-icon" style="background: #e6f0fa; color: #1e5fa6;"><i class="fa fa-print"></i></div>
    <div><p style="margin:0; font-size:12px; color:#888; font-weight: 600;">Total Printer</p><h3 style="margin:0; font-weight:800; font-size: 24px;"><?= $total_printer ?></h3></div>
  </div>
  <div class="stat-box">
    <div class="stat-icon" style="background: #e6fcf5; color: #20c997;"><i class="fa fa-desktop"></i></div>
    <div><p style="margin:0; font-size:12px; color:#888; font-weight: 600;">Total Komputer</p><h3 style="margin:0; font-weight:800; font-size: 24px;"><?= $total_komputer ?></h3></div>
  </div>
  <div class="stat-box" style="cursor: pointer;" onclick="window.location.href='<?= base_url('riwayatmutasi') ?>'">
    <div class="stat-icon" style="background: #fff4e6; color: #ff922b;"><i class="fa fa-exchange"></i></div>
    <div><p style="margin:0; font-size:12px; color:#888; font-weight: 600;">Riwayat Perpindahan</p><h3 style="margin:0; font-weight:800; font-size: 24px;"><?= $total_riwayat ?> <small style="font-size:12px; font-weight:normal; color:#1e5fa6;">(Lihat)</small></h3></div>
  </div>
  <div class="stat-box">
    <div class="stat-icon" style="background: #ffe3e3; color: #fa5252;"><i class="fa fa-wrench"></i></div>
    <div><p style="margin:0; font-size:12px; color:#888; font-weight: 600;">Sedang di Service</p><h3 style="margin:0; font-weight:800; font-size: 24px;"><?= $sedang_service ?></h3></div>
  </div>
</div>

<div class="custom-card">
  <div class="toolbar-container">
    <h4 style="font-weight: 800; margin: 0; font-size: 18px;"><i class="fa fa-cube text-primary"></i> Data Ruangan</h4>
    <div class="search-group">
      <input type="text" id="searchBarang" class="form-control" placeholder="Cari ruangan...">
      <button class="btn btn-primary" onclick="bukaModalTambah()" style="background: #1e5fa6; border-radius: 10px; white-space: nowrap;"><i class="fa fa-plus"></i> Tambah Barang</button>
    </div>
  </div>

  <div class="d-flex mb-4" style="gap: 10px; overflow-x: auto; padding-bottom: 10px; flex-wrap: wrap;">
    <button class="btn btn-filter active" data-filter="all">Semua</button>
    <button class="btn btn-filter" data-filter="poliklinik">Poliklinik</button>
    <button class="btn btn-filter" data-filter="rawat-inap">Rawat Inap</button>
    <button class="btn btn-filter" data-filter="instalasi">Instalasi</button>
    <button class="btn btn-filter" data-filter="umum">Umum / Lainnya</button>
  </div>

  <div class="masonry-grid" id="gridBarang">
    <?php 
      $grouped = [];
      foreach($barang as $p) { $grouped[$p['kategori']][] = $p; }
      
      foreach($grouped as $kategori => $items): 
        $count = count($items);
        $semuaFix = true;
        foreach($items as $item) {
            if (($item['status_fix'] ?? 'belum_fix') !== 'fix') { $semuaFix = false; break; }
        }
        
        $borderColor = $semuaFix ? '#1e5fa6' : '#ffc107';
        $bgIcon = $semuaFix ? '#e6f0fa' : '#fff4e6';
        $iconColor = $semuaFix ? '#1e5fa6' : '#ff922b';
        
        $grup = 'umum';
        $kat_lower = strtolower($kategori);
        if (strpos($kat_lower, 'poli') !== false) { $grup = 'poliklinik'; } 
        elseif (strpos($kat_lower, 'rawat inap') !== false || strpos($kat_lower, 'ranap') !== false) { $grup = 'rawat-inap'; } 
        elseif (strpos($kat_lower, 'igd') !== false || strpos($kat_lower, 'instalasi') !== false || strpos($kat_lower, 'laboratorium') !== false) { $grup = 'instalasi'; }
    ?>
    
    <div class="masonry-item poli-wrapper" data-kategori="<?= strtolower($kategori) ?>" data-grup="<?= $grup ?>">
      <div class="poli-card" style="border: 2px solid <?= $borderColor ?>; box-shadow: 0 0 10px <?= $semuaFix ? 'rgba(30,95,166,0.2)' : 'rgba(255,193,7,0.3)' ?>;">
        <div class="poli-icon-circle" style="background: <?= $bgIcon ?>; color: <?= $iconColor ?>;"><i class="fa fa-cube"></i></div>
        <div class="poli-title"><?= strtoupper($kategori) ?></div>
        <div class="poli-subtitle"><?= $count ?> Unit Barang</div>
        <div style="font-size: 11px; margin-bottom: 10px; font-weight: 700; color: <?= $borderColor ?>;">
          <i class="fa <?= $semuaFix ? 'fa-check-circle' : 'fa-clock-o' ?>"></i> 
          <?= $semuaFix ? 'SUDAH FIX' : 'BELUM FIX' ?>
        </div>
        
        <button class="btn btn-primary btn-sm btn-lihat-ruangan" 
                data-kategori="<?= $kategori ?>"
                data-barang='<?= htmlspecialchars(json_encode($items), ENT_QUOTES, "UTF-8") ?>'
                style="background: <?= $borderColor ?>; border-radius: 20px; padding: 6px 15px; font-weight: 600; margin-top: auto; font-size: 12px; border: none; width: 100%;">
          Lihat Detail
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->include('pencatatan/modals') ?>

<!-- PASTIKAN JQUERY DIMUAT SEBELUM FILE JS ANDA -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>

<!-- PANGGIL FILE JS -->
<script src="<?= base_url('assets/js/pencatatan.js') ?>"></script>