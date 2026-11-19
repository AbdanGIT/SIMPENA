<style>
  .nav-tabs-custom { border-bottom: none; margin-bottom: 15px; }
  .nav-tabs-custom > li > a { border: none; border-radius: 10px; margin-right: 5px; font-weight: 700; color: #64748b; background: #f1f5f9; }
  .nav-tabs-custom > li.active > a, .nav-tabs-custom > li.active > a:hover { background: #1e5fa6; color: #fff; }
  .tab-content-custom { background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
</style>

<!-- HEADER BANNER -->
<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Selamat Datang,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;"><?= session()->get('nama') ?></h2>
    <p style="margin-top: 5px; color: #555;">Kelola data Pengadaan Barang PDE dengan mudah dan cepat</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
    <i class="fa fa-calendar" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;"><?= date('l, d F Y') ?></p>
    <p style="margin: 0; font-size: 13px; color: #888;"><?= date('H:i') ?> WIB</p>
  </div>
</div>

<!-- TABEL DATA PENGADAAN UTAMA -->
<div class="custom-card" style="padding: 25px; border-radius: 15px; margin-bottom: 20px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 15px;">
      <div style="background: #e6f0fa; color: #1e5fa6; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 20px;"><i class="fa fa-box-open"></i></div>
      <div>
        <h4 style="margin: 0; font-weight: 800; color: #1a1a1a;">Data Pengadaan Barang PDE</h4>
        <p style="margin: 0; color: #888; font-size: 13px;">Daftar inventaris stok masuk & keluar</p>
      </div>
    </div>
    
    <div style="display: flex; gap: 10px;">
      <button class="btn btn-primary" onclick="bukaModalTambah()" style="background: #1e5fa6; border-radius: 10px; font-weight: 600;"><i class="fa fa-plus"></i> Tambah Data</button>
      
      <!-- TOMBOL FILTER -->
      <div class="dropdown" style="display: inline-block;">
        <button class="btn btn-default dropdown-toggle" type="button" id="filterDropdown" onclick="toggleFilter()" style="border-radius: 10px; border: 1px solid #ddd; background: #fff; color: #64748b; font-weight: 600;">
          <i class="fa fa-filter"></i> Filter Status <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" id="filterMenu" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none; display: none; position: absolute; z-index: 9999;">
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="all">Semua Status</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="DONE">DONE</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="PENDING">PENDING</a></li>
        </ul>
      </div>

      <div style="position: relative;">
        <i class="fa fa-search" style="position: absolute; left: 15px; top: 12px; color: #aaa;"></i>
        <input type="text" id="searchInput" class="form-control" placeholder="Cari data..." style="border-radius: 10px; padding-left: 40px; width: 250px; border: 1px solid #ddd;">
      </div>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table class="table table-hover table-bordered" style="margin-bottom: 0; width: 100%; table-layout: fixed;" id="tabelPengadaan">
      <thead style="background: #f8fafc;">
        <tr>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 4%; text-align: center;">No</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 18%;">Part Number</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 25%;">Description</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 8%; text-align: center;">Stock Awal</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 8%; text-align: center;">Incoming</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 8%; text-align: center;">Outgoing</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 8%; text-align: center;">Stock Actual</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 8%; text-align: center;">Dokumentasi</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 8%; text-align: center;">Status</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 10%; text-align: center;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($pengadaan)): ?>
            <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data pengadaan.</td></tr>
        <?php else: ?>
            <?php $no = 1; foreach($pengadaan as $row): ?>
            <tr class="data-row">
              <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= $no++ ?></td>
              <td style="padding: 15px; vertical-align: middle; font-weight: 600; color: #1e5fa6; white-space: normal; word-wrap: break-word;"><?= esc($row['part_number']) ?></td>
              <td style="padding: 15px; vertical-align: middle; white-space: normal; word-wrap: break-word;"><?= esc($row['description']) ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= $row['stock_awal'] ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center; color: #065f46; font-weight: bold;">+<?= $row['incoming'] ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center; color: #dc2626; font-weight: bold;">-<?= $row['outgoing'] ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center; font-weight: bold;"><?= $row['stock_actual'] ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;">
                <?php if(!empty($row['dokumentasi'])): ?>
                    <a href="<?= base_url('uploads/' . $row['dokumentasi']) ?>" target="_blank" style="color: #1e5fa6; text-decoration: underline;">Lihat</a>
                <?php else: ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;">
                <span class="status-text" style="background: <?= ($row['status'] == 'DONE') ? '#d1fae5' : '#fef3c7' ?>; color: <?= ($row['status'] == 'DONE') ? '#065f46' : '#92400e' ?>; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap;">
                    <?= $row['status'] ?>
                </span>
              </td>
              <td style="padding: 15px; vertical-align: middle; text-align: center; white-space: nowrap;">
                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalLihat<?= $row['id_pengadaan'] ?>" title="Lihat Detail" style="border-radius: 8px; background: #17a2b8;">
                  <i class="fa fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-primary edit-btn" 
                        data-id="<?= $row['id_pengadaan'] ?>"
                        data-part="<?= esc($row['part_number']) ?>"
                        data-desc="<?= esc($row['description']) ?>"
                        data-awal="<?= $row['stock_awal'] ?>"
                        data-incoming="<?= $row['incoming'] ?>"
                        data-outgoing="<?= $row['outgoing'] ?>"
                        data-aktual="<?= $row['stock_actual'] ?>"
                        data-status="<?= $row['status'] ?>"
                        data-dok="<?= $row['dokumentasi'] ?>"
                        style="border-radius: 8px; background: #1e5fa6;"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id_pengadaan'] ?>" style="border-radius: 8px; background: #fa5252;"><i class="fa fa-trash"></i></button>
              </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ============================================== -->
<!-- AREA TAB INCOMING & OUTGOING                   -->
<!-- ============================================== -->
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_incoming" data-toggle="tab"><i class="fa fa-arrow-down text-success"></i> Data Incoming (Masuk)</a></li>
        <li><a href="#tab_outgoing" data-toggle="tab"><i class="fa fa-arrow-up text-danger"></i> Data Outgoing (Keluar)</a></li>
      </ul>
    </div>
    
    <div class="tab-content tab-content-custom">
      <!-- TAB INCOMING -->
      <div class="tab-pane fade in active" id="tab_incoming">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <h4 style="font-weight: 800; margin: 0;">Tabel Incoming (Barang Masuk)</h4>
          <button class="btn btn-success btn-sm" onclick="bukaModalTambahInc()"><i class="fa fa-plus"></i> Tambah Incoming</button>
        </div>
        <div style="overflow-x: auto;">
          <table class="table table-bordered" style="width: 100%;">
            <thead style="background: #e8f5e9; color: #2e7d32;">
              <tr>
                <th style="text-align: center; width: 5%;">NO</th>
                <th style="width: 25%;">PART NUMBER</th>
                <th style="width: 30%;">DESCRIPTION</th>
                <th style="text-align: center; width: 10%;">QTY MASUK</th>
                <th style="text-align: center; width: 15%;">TANGGAL</th>
                <th style="text-align: center; width: 10%;">STATUS</th>
                <th style="text-align: center; width: 5%;">AKSI</th>
              </tr>
            </thead>
            <tbody>
            <?php if(empty($incoming)): ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
            <?php else: $no=1; foreach($incoming as $inc): ?>
            <tr style="background: <?= ($inc['status'] == 'DONE') ? '#d4edda' : '#fff3cd' ?>;">
                <td align="center"><?= $no++ ?></td>
                <td><?= esc($inc['part_number']) ?></td>
                <td><?= esc($inc['description']) ?></td>
                <td align="center"><?= $inc['qty_masuk'] ?></td>
                <td align="center"><?= $inc['tanggal'] ?></td>
                <td align="center">
                <span style="background: <?= ($inc['status'] == 'DONE') ? '#155724' : '#856404' ?>; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; display: inline-block;">
                    <?= $inc['status'] ?>
                </span>
                </td>
                <td align="center">
                <button class="btn btn-xs btn-primary edit-inc" 
                        data-id="<?= $inc['id_incoming'] ?>" data-part="<?= esc($inc['part_number']) ?>" data-desc="<?= esc($inc['description']) ?>" data-qty="<?= $inc['qty_masuk'] ?>" data-tgl="<?= $inc['tanggal'] ?>" data-status="<?= $inc['status'] ?>">
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-xs btn-danger hapus-inc" data-id="<?= $inc['id_incoming'] ?>"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB OUTGOING -->
      <div class="tab-pane fade" id="tab_outgoing">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <h4 style="font-weight: 800; margin: 0;">Tabel Outgoing (Barang Keluar)</h4>
          <button class="btn btn-danger btn-sm" onclick="bukaModalTambahOut()"><i class="fa fa-plus"></i> Tambah Outgoing</button>
        </div>
        <div style="overflow-x: auto;">
          <table class="table table-bordered" style="width: 100%;">
            <thead style="background: #ffebee; color: #c62828;">
              <tr>
                <th style="text-align: center; width: 5%;">NO</th>
                <th style="width: 20%;">PART NUMBER</th>
                <th style="width: 25%;">DESCRIPTION</th>
                <th style="text-align: center; width: 8%;">QTY KELUAR</th>
                <th style="text-align: center; width: 12%;">TANGGAL</th>
                <th style="text-align: center; width: 15%;">DISTRIBUSI</th>
                <th style="text-align: center; width: 10%;">STATUS</th>
                <th style="text-align: center; width: 5%;">AKSI</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($outgoing)): ?>
                <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
              <?php else: $no=1; foreach($outgoing as $out): ?>
              <tr style="background: <?= (isset($out['status']) && $out['status'] == 'DONE') ? '#ffcdd2' : '#fff3cd' ?>; color: <?= (isset($out['status']) && $out['status'] == 'DONE') ? '#b71c1c' : '#856404' ?>;">
                <td align="center"><?= $no++ ?></td>
                <td><?= esc($out['part_number']) ?></td>
                <td><?= esc($out['description']) ?></td>
                <td align="center"><?= $out['qty_keluar'] ?></td>
                <td align="center"><?= $out['tanggal'] ?></td>
                <td><?= esc($out['distribusi']) ?></td>
                <td align="center">
                  <span style="background: <?= (isset($out['status']) && $out['status'] == 'DONE') ? '#dc3545' : '#ffc107' ?>; color: <?= (isset($out['status']) && $out['status'] == 'DONE') ? '#fff' : '#000' ?>; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; display: inline-block;">
                      <?= isset($out['status']) ? $out['status'] : 'PENDING' ?>
                  </span>
                </td>
                <td align="center">
                  <button class="btn btn-xs btn-primary edit-out" 
                          data-id="<?= $out['id_outgoing'] ?>" data-part="<?= esc($out['part_number']) ?>" data-desc="<?= esc($out['description']) ?>" data-qty="<?= $out['qty_keluar'] ?>" data-tgl="<?= $out['tanggal'] ?>" data-dist="<?= esc($out['distribusi']) ?>" data-status="<?= isset($out['status']) ? $out['status'] : 'PENDING' ?>">
                    <i class="fa fa-pencil"></i>
                  </button>
                  <button class="btn btn-xs btn-danger hapus-out" data-id="<?= $out['id_outgoing'] ?>"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL & JS -->
<?= $this->include('pengadaan/modals') ?>