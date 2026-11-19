<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?? 'SIMSS | RSUD' ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- CSS Bawaan -->
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css') ?>">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
      /* ====================================================
         FOOTER STYLING (OTOMATIS MENYESUAIKAN SIDEBAR)
         ==================================================== */
      .main-footer {
          background: #ffffff;
          border-top: 1px solid #f0f0f0;
          padding: 15px 30px;
          position: relative;
          z-index: 10;
          
          /* Kondisi Normal */
          margin-left: 270px !important;
          width: calc(100% - 270px) !important;
          box-sizing: border-box;
          transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out !important;
      }

      /* Kondisi Saat Sidebar Di-Minimize */
      body.sidebar-minimize .main-footer { 
          margin-left: 80px !important; 
          width: calc(100% - 80px) !important; 
      }


      /* ====================================================
        PERBAIKAN ISI TABEL KERUSAKAN (AGAR TIDAK TURUN)
        ==================================================== */
      .table-custom th, 
      .table-custom td {
          white-space: nowrap !important; 
          vertical-align: middle !important;
      }
    body { font-family: 'Plus Jakarta Sans', sans-serif !important; background-color: #f4f7fb !important; color: #333; overflow-x: hidden; }
    .main-header { display: none !important; }
    
    /* Layout Dasar */
    .main-sidebar {
      width: 270px !important;
      background: #ffffff !important;
      box-shadow: 2px 0 15px rgba(0,0,0,0.03) !important;
      height: 100vh !important;
      position: fixed !important;
      top: 0;
      left: 0;
      overflow-y: auto;
      z-index: 1000;
      transition: width 0.3s ease-in-out !important;
    }
    
    /* Perbaikan Content Wrapper agar tidak mengecil */
    .content-wrapper {
      margin-left: 270px !important;
      background: transparent !important;
      min-height: 100vh !important;
      display: flex;
      flex-direction: column;
      width: calc(100% - 270px) !important; 
      box-sizing: border-box;
      transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out !important;
    }
    
    /* Top Navbar */
    .top-navbar { padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .search-box { background: #ffffff; border-radius: 30px; padding: 12px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 400px; display: flex; align-items: center; }
    .search-box input { border: none; outline: none; background: transparent; width: 100%; margin-left: 10px; color: #6c757d; font-size: 14px; }
    .user-profile { display: flex; align-items: center; gap: 15px; }
    .user-profile img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }
    
    /* Card Styling */
    .content { padding: 0 30px 30px 30px !important; width: 100% !important; box-sizing: border-box; }
    .custom-card { background: #fff; border-radius: 20px; padding: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.03); margin-bottom: 25px; box-sizing: border-box; }

    .main-footer { margin-top: auto !important; }

    /* Header Banner */
    .hero-banner { 
      background-size: cover; 
      background-position: center;
      border-radius: 20px; 
      padding: 30px; 
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      box-sizing: border-box;
    }
    .hero-text h2 { font-size: 32px; font-weight: 800; margin: 0; color: #1a1a1a; }
    .hero-text p { font-size: 16px; color: #555; margin-top: 5px; }

    /* --- STYLE SIDEBAR MINIMIZE (Mengecil) --- */
    body.sidebar-minimize .main-sidebar { width: 80px !important; }
    body.sidebar-minimize .content-wrapper { margin-left: 80px !important; width: calc(100% - 80px) !important; }
    body.sidebar-minimize .sidebar-menu span, 
    body.sidebar-minimize .sidebar-menu .fa-angle-right { display: none !important; }
    body.sidebar-minimize .logo-sidebar { display: none !important; }
    body.sidebar-minimize .menu-cepat-title { display: none !important; }
    body.sidebar-minimize .sidebar-menu a { justify-content: center !important; padding: 12px 0 !important; }
    body.sidebar-minimize .sidebar-menu i { font-size: 20px !important; margin: 0; }
    body.sidebar-minimize .main-sidebar > div:first-child { justify-content: center !important; }
    
    /* Memperbaiki footer saat minimize */
    body.sidebar-minimize .main-footer { margin-left: 80px !important; }
  </style>

  <script>
      // Definisikan Base URL dan CSRF Token untuk Javascript
      window.baseUrl = "<?= base_url() ?>";
      window.csrfName = "<?= csrf_token() ?>";
      window.csrfHash = "<?= csrf_hash() ?>";
  </script>

  <!-- Penyesuaian tampilan HP/tablet (dipasang paling akhir agar menimpa style desktop) -->
  <link rel="stylesheet" href="<?= base_url('assets/css/mobile.css') ?>">
</head>
<!-- TAMBAHKAN CLASS 'sidebar-minimize' DI SINI AGAR SELALU TERTUTUP SAAT HALAMAN DIMUAT -->
<body class="hold-transition sidebar-mini sidebar-minimize">
<div class="wrapper">

<!-- Tombol menu khusus layar kecil (HP/tablet) -->
<button type="button" class="mobile-nav-toggle" id="mobileNavToggle"
        aria-label="Buka menu" aria-controls="sidebarKustom" aria-expanded="false">
  <i class="fa fa-bars"></i>
</button>
<div class="mobile-nav-backdrop" id="mobileNavBackdrop"></div>