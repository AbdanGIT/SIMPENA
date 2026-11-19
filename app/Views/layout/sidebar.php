<style>
    /* ====================================================
       1. SIDEBAR NORMAL (Tampilan Terbuka)
       ==================================================== */
    .main-sidebar, .sidebar {
        overflow-x: hidden !important;
    }

    .menu-cepat-header {
        padding: 15px 25px 5px 25px !important;
        margin-top: 10px;
        list-style: none;
    }
    .menu-cepat-header p {
        color: #94a3b8; 
        font-size: 11px; 
        font-weight: 800; 
        letter-spacing: 1px; 
        text-transform: uppercase; 
        margin: 0;
        white-space: nowrap !important;
    }

    /* ====================================================
       2. SIDEBAR MINIMIZE (Tampilan Tertutup / 80px)
       ==================================================== */
    
    /* Sembunyikan SEMUA teks (span & p) saat minimize */
    body.sidebar-minimize .main-sidebar .sidebar-menu li a span,
    body.sidebar-minimize .main-sidebar .sidebar-menu li.menu-cepat-header,
    body.sidebar-minimize .main-sidebar .sidebar-menu li.header {
        display: none !important; 
    }

    /* Pastikan ikon tetap di tengah dan tidak terpotong */
    body.sidebar-minimize .main-sidebar .sidebar-menu li a {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        padding: 12px 0 !important;
        height: auto !important;
        width: 100% !important;
    }

    body.sidebar-minimize .main-sidebar .sidebar-menu li a i {
        margin: 0 !important;
        font-size: 20px !important;
        display: block !important;
        text-align: center !important;
    }
    
    /* Rapikan tombol menu yang memiliki div pembungkus */
    body.sidebar-minimize .main-sidebar .sidebar-menu li a > div {
        justify-content: center !important;
        width: 100% !important;
    }
</style>

<!-- Sidebar Kiri -->
<aside class="main-sidebar" id="sidebarKustom" style="background: #ffffff !important; padding-top: 0; transition: width 0.3s;">
  
  <!-- HEADER SIDEBAR: TOMBOL & LOGO SEJAJAR -->
  <div style="padding: 15px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
      <img src="<?= base_url('assets/images/RSUD Sultan Suriansyah Logo.png') ?>" 
           alt="Logo" 
           class="logo-sidebar"
           style="max-height: 40px; max-width: 170px; object-fit: contain;">
      
      <button type="button" onclick="toggleSidebar()" style="background: transparent; border: none; font-size: 20px; color: #1e5fa6; cursor: pointer; position: relative; z-index: 9999; padding: 5px;">
          <i class="fa fa-bars"></i>
      </button>
  </div>

  <!-- Menu Sidebar -->
  <section class="sidebar" style="background: transparent !important; margin-top: 0; padding-top: 0;">
    
    <!-- UL UTAMA: Membungkus semua menu -->
    <ul class="sidebar-menu" style="margin-top: 0;">
      
      <!-- Menu Dashboard -->
      <?php $isDashboard = (strpos(uri_string(), 'dashboard') !== false || uri_string() == '') ? 'active' : ''; ?>
      <li class="<?= $isDashboard ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isDashboard ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('dashboard') ?>" style="color: <?= $isDashboard ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 12px; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isDashboard ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-th-large" style="font-size: 16px; width: 20px; text-align: center; color: <?= $isDashboard ? '#1e5fa6' : '#64748b' ?> !important;"></i> 
          <span style="font-size: 14px;">Dashboard</span>
        </a>
      </li>

      <!-- Menu Data Kerusakan Barang -->
      <?php $isKerusakan = (uri_string() == 'kerusakan') ? 'active' : ''; ?>
      <li class="<?= $isKerusakan ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isKerusakan ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('kerusakan') ?>" style="color: <?= $isKerusakan ? '#1e5fa6' : '#64748b' ?> !important; font-weight: 700; padding: 10px 18px; display: flex; align-items: center; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa fa-exclamation-triangle" style="font-size: 16px; width: 20px; text-align: center;"></i> 
            <span style="font-size: 14px;">Data Kerusakan Barang</span>
          </div>
        </a>
      </li>

      <!-- Menu Pencatatan Komputer -->
      <?php $isPencatatan = (strpos(uri_string(), 'pencatatan') !== false) ? 'active' : ''; ?>
      <li class="<?= $isPencatatan ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isPencatatan ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('pencatatan') ?>" style="color: <?= $isPencatatan ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isPencatatan ? '#e8f2fd' : 'transparent' ?>'">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa fa-desktop" style="font-size: 16px; width: 20px; text-align: center;"></i> 
            <span style="font-size: 14px;">Pencatatan Komputer</span>
          </div>
        </a>
      </li>

      <!-- Menu Riwayat Mutasi Komputer -->
      <?php $isRiwayatMutasi = (strpos(uri_string(), 'riwayatmutasi') !== false) ? 'active' : ''; ?>
      <li class="<?= $isRiwayatMutasi ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isRiwayatMutasi ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('riwayatmutasi') ?>" style="color: <?= $isRiwayatMutasi ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isRiwayatMutasi ? '#e8f2fd' : 'transparent' ?>'">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa fa-exchange" style="font-size: 16px; width: 20px; text-align: center;"></i> 
            <span style="font-size: 14px;">Riwayat Mutasi Komputer</span>
          </div>
        </a>
      </li>

      <!-- Menu Pengadaan Barang PDE -->
      <?php $isPengadaan = (strpos(uri_string(), 'pengadaan') !== false) ? 'active' : ''; ?>
      <li class="<?= $isPengadaan ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isPengadaan ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('pengadaan') ?>" style="color: <?= $isPengadaan ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isPengadaan ? '#e8f2fd' : 'transparent' ?>'">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa fa-shopping-cart" style="font-size: 16px; width: 20px; text-align: center;"></i> 
            <span style="font-size: 14px;">Pengadaan Barang PDE</span>
          </div>
        </a>
      </li>

      <!-- Menu Service Report -->
      <?php $isService = (strpos(uri_string(), 'service_report') !== false) ? 'active' : ''; ?>
      <li class="<?= $isService ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isService ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('service_report') ?>" style="color: <?= $isService ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 12px; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isService ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-file-text" style="font-size: 16px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 14px;">Service Report</span>
        </a>
      </li>

      <!-- Menu Arsip Dokumen -->
      <?php $isArsip = (strpos(uri_string(), 'arsip') !== false) ? 'active' : ''; ?>
      <li class="<?= $isArsip ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isArsip ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('arsip') ?>" style="color: <?= $isArsip ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 12px; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isArsip ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-folder-open" style="font-size: 16px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 14px;">Arsip Dokumen</span>
        </a>
      </li>

      <!-- Menu Desain Label Zebra -->
      <?php $isZebra = (strpos(uri_string(), 'zebra_label') !== false) ? 'active' : ''; ?>
      <li class="<?= $isZebra ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isZebra ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('zebra_label') ?>" style="color: <?= $isZebra ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 12px; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isZebra ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-print" style="font-size: 16px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 14px;">Desain Label Zebra</span>
        </a>
      </li>

      <!-- Menu Pengaturan -->
      <?php $isPengaturan = (strpos(uri_string(), 'pengaturan') !== false) ? 'active' : ''; ?>
      <li class="<?= $isPengaturan ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isPengaturan ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('pengaturan') ?>" style="color: <?= $isPengaturan ? '#1e5fa6' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 12px; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isPengaturan ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-cog" style="font-size: 16px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 14px;">Pengaturan</span>
        </a>
      </li>

      <!-- Menu Persetujuan (Hanya Super Admin) -->
      <?php if(session()->get('role') === 'superadmin'): ?>
      <?php $isApproval = (strpos(uri_string(), 'approval') !== false) ? 'active' : ''; ?>
      <li class="<?= $isApproval ?>" style="margin: 2px 15px; border-radius: 12px; <?= $isApproval ? 'background-color: #fee2e2 !important;' : '' ?>">
        <a href="<?= base_url('approval') ?>" style="color: <?= $isApproval ? '#dc2626' : '#64748b' ?> !important; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 12px; border-radius: 12px; transition: 0.3s;">
          <i class="fa fa-check-square-o" style="font-size: 16px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 14px;">Persetujuan Data</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Menu Cepat (Judul) -->
      <li class="menu-cepat-header">
        <p>Menu Cepat</p>
      </li>
      
      <!-- Menu Stok Sparepart -->
      <?php $isStok = (strpos(uri_string(), 'stok_sparepart') !== false) ? 'active' : ''; ?>
      <li class="<?= $isStok ?>" style="margin: 1px 15px; border-radius: 10px; <?= $isStok ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('stok_sparepart') ?>" style="color: <?= $isStok ? '#1e5fa6' : '#64748b' ?> !important; padding: 8px 18px; font-weight: 500; display: flex; align-items: center; gap: 12px; border-radius: 10px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isStok ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-database" style="font-size: 14px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 13px;">Stok Sparepart</span>
        </a>
      </li>
      
      <!-- Menu Antrian Pergantian Sparepart -->
      <?php $isAntrian = (strpos(uri_string(), 'antrian_sparepart') !== false) ? 'active' : ''; ?>
      <li class="<?= $isAntrian ?>" style="margin: 1px 15px; border-radius: 10px; <?= $isAntrian ? 'background-color: #e8f2fd !important;' : '' ?>">
        <a href="<?= base_url('antrian_sparepart') ?>" style="color: <?= $isAntrian ? '#1e5fa6' : '#64748b' ?> !important; padding: 8px 18px; font-weight: 500; display: flex; align-items: center; gap: 12px; border-radius: 10px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='<?= $isAntrian ? '#e8f2fd' : 'transparent' ?>'">
          <i class="fa fa-list-ol" style="font-size: 14px; width: 20px; text-align: center;"></i> 
          <span style="font-size: 13px;">Antrian Sparepart</span>
        </a>
      </li>

    </ul> 
  </section>
</aside>

<!-- WRAPPER KONTEN UTAMA -->
<div class="content-wrapper">
  
  <!-- NAVBAR ATAS -->
  <div class="top-navbar">
    <div class="search-box">
      <i class="fa fa-search" style="color: #a0a5ba;"></i>
      <input type="text" placeholder="Cari menu, laporan, atau informasi...">
    </div>
    <div class="user-profile">
      <i class="fa fa-bell-o" style="font-size: 20px; color: #1e5fa6; margin-right: 15px;"></i>
      
      <?php 
        $foto_user = session()->get('photo');
        if (empty($foto_user)) {
            $foto_user = 'user_blank.png';
        }
      ?>
      <img src="<?= base_url('assets/images/' . $foto_user) ?>" alt="User" onerror="this.src='<?= base_url('assets/images/user_blank.png') ?>'">
      
      <div>
        <b style="display: block; font-size: 14px; color: #333;">
          <?= session()->get('nama') ? session()->get('nama') : 'Administrator' ?>
        </b>
        <span style="font-size: 12px; color: #888; display: block;">Administrator</span>
        
        <a href="<?= base_url('logout') ?>" style="font-size: 11px; color: #fa5252; text-decoration: none; font-weight: 700; margin-top: 2px; display: inline-block;" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
          <i class="fa fa-sign-out"></i> Logout
        </a>
      </div>
    </div>
  </div>

  <!-- Konten Halaman Mulai -->
  <section class="content">