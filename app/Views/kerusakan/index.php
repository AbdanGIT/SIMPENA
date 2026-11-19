<!-- HEADER BANNER -->
<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Selamat Datang,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;"><?= session()->get('nama') ?></h2>
    <p style="margin-top: 5px; color: #555;">Kelola data kerusakan barang dengan mudah dan cepat</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
    <i class="fa fa-calendar" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;"><?= date('l, d F Y') ?></p>
    <p style="margin: 0; font-size: 13px; color: #888;"><?= date('H:i') ?> WIB</p>
  </div>
</div>

<style>
  /* Perbaikan agar kolom Status tidak memotong teks */
  #tabelKerusakan th, 
  #tabelKerusakan td {
      white-space: normal !important; /* Mengizinkan teks turun baris */
      word-wrap: break-word !important; 
      vertical-align: top !important;
  }
  
  /* Perbaikan khusus pada Badge Status */
  .status-text {
      white-space: normal !important;
      word-break: break-word !important;
      display: inline-block;
      line-height: 1.4;
      text-align: center;
  }
</style>

<!-- TABEL DATA KERUSAKAN -->
<div class="custom-card" style="padding: 25px; border-radius: 15px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 15px;">
      <div style="background: #e6f0fa; color: #1e5fa6; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 20px;"><i class="fa fa-clipboard"></i></div>
      <div>
        <h4 style="margin: 0; font-weight: 800; color: #1a1a1a;">Data Kerusakan Barang</h4>
        <p style="margin: 0; color: #888; font-size: 13px;">Daftar inventaris barang yang mengalami kerusakan</p>
      </div>
    </div>
    
    <div style="display: flex; gap: 10px;">
      <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah" style="background: #1e5fa6; border-radius: 10px; font-weight: 600;"><i class="fa fa-plus"></i> Tambah Data</button>
      
      <!-- TOMBOL FILTER -->
      <div class="dropdown" style="display: inline-block;">
        <button class="btn btn-default dropdown-toggle" type="button" id="filterDropdown" onclick="toggleFilter()" style="border-radius: 10px; border: 1px solid #ddd; background: #fff; color: #64748b; font-weight: 600;">
          <i class="fa fa-filter"></i> Filter Status <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" id="filterMenu" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none; display: none; position: absolute; z-index: 9999;">
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="all">Semua Status</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Dalam Perbaikan">Dalam Perbaikan</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Selesai Perbaikan">Selesai Perbaikan</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Di Gudang">Di Gudang</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Di Ruangan">Di Ruangan</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Sudah Penghapusan Aset">Sudah Penghapusan Aset</a></li>
        </ul>
      </div>

      <div style="position: relative;">
        <i class="fa fa-search" style="position: absolute; left: 15px; top: 12px; color: #aaa;"></i>
        <input type="text" id="searchInput" class="form-control" placeholder="Cari data..." style="border-radius: 10px; padding-left: 40px; width: 250px; border: 1px solid #ddd;">
      </div>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table class="table table-hover table-bordered" style="margin-bottom: 0; width: 100%; table-layout: fixed;" id="tabelKerusakan">
      <thead style="background: #f8fafc;">
        <tr>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 4%; text-align: center;">No</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 10%; text-align: center;">Nama Barang</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 12%; text-align: center;">Merek</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 12%; text-align: center;">Ruangan</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 12%; text-align: center;">Tempat Service</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 10%; text-align: center;">Tgl Service</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 18%; text-align: center;">Masalah</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 10%; text-align: center;">Status</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 12%; text-align: center;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; foreach ($kerusakan as $row): ?>
        <tr class="data-row">
          <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= $no++ ?></td>
          <td style="padding: 15px; vertical-align: middle; text-align: center; font-weight: 600; color: #1e5fa6;"><?= esc($row['nama_barang']) ?></td>
          <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= esc($row['merek']) ?></td>
          <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= esc($row['ruangan']) ?></td>
          <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= esc($row['tempat_service']) ?></td>
          <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= esc($row['tgl_service']) ?></td>
          <td style="padding: 15px; vertical-align: middle; word-wrap: break-word; text-align: center;"><?= esc($row['masalah']) ?></td>
          <td style="padding: 15px; vertical-align: middle; text-align: center;">
            <?php 
              $status = $row['status'];
              if ($status == 'Sudah Penghapusan Aset') {
                  $bgColor = '#fee2e2'; $textColor = '#dc2626';
              } elseif ($status == 'Selesai Perbaikan') {
                  $bgColor = '#d1fae5'; $textColor = '#065f46';
              } elseif ($status == 'Dalam Perbaikan') {
                  $bgColor = '#fef3c7'; $textColor = '#92400e';
              } else {
                  $bgColor = '#e6f0fa'; $textColor = '#1e5fa6';
              }
            ?>
            <!-- Class status-text di sini yang sudah diperbaiki CSSnya -->
            <span class="status-text" style="background: <?= $bgColor ?>; color: <?= $textColor ?>; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                <?= esc($row['status']) ?>
            </span>
          </td>
          <td style="padding: 15px; vertical-align: middle; text-align: center; white-space: nowrap;">
            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalLihat<?= $row['id_kerusakan'] ?>" title="Lihat Detail" style="border-radius: 8px; background: #17a2b8;">
              <i class="fa fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-primary edit-btn" 
                    data-id="<?= $row['id_kerusakan'] ?>"
                    data-nama="<?= esc($row['nama_barang']) ?>"
                    data-merek="<?= esc($row['merek']) ?>"
                    data-ruangan="<?= esc($row['ruangan']) ?>"
                    data-tempat="<?= esc($row['tempat_service']) ?>"
                    data-tglambil="<?= esc($row['tgl_ambil']) ?>"
                    data-tglservice="<?= esc($row['tgl_service']) ?>"
                    data-kelengkapan="<?= esc($row['kelengkapan']) ?>"
                    data-masalah="<?= esc($row['masalah']) ?>"
                    data-keterangan="<?= esc($row['keterangan']) ?>"
                    data-nobarang="<?= esc($row['no_barang']) ?>"
                    data-status="<?= esc($row['status']) ?>"
                    style="border-radius: 8px; background: #1e5fa6;"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id_kerusakan'] ?>" style="border-radius: 8px; background: #fa5252;"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 
  ======================================================================
  PERBAIKAN: 
  Di bawah ini, CUKUP panggil file modals.php saja. 
  Semua kode HTML Modal Tambah, Edit, Lihat, dan Script JS sudah ada di dalam file tersebut.
  Menghindari pemanggilan ganda (double load) yang menyebabkan data tersimpan 2x.
  ======================================================================
-->
<?= $this->include('kerusakan/modals') ?>