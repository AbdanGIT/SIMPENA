<!-- HEADER BANNER -->
<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Selamat Datang,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;"><?= session()->get('nama') ?></h2>
    <p style="margin-top: 5px; color: #555;">Kelola arsip dokumen penting dengan mudah dan aman</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
    <i class="fa fa-calendar" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;"><?= date('l, d F Y') ?></p>
    <p style="margin: 0; font-size: 13px; color: #888;"><?= date('H:i') ?> WIB</p>
  </div>
</div>

<!-- TABEL DATA ARSIP -->
<div class="custom-card" style="padding: 25px; border-radius: 15px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 15px;">
      <div style="background: #e6f0fa; color: #1e5fa6; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 20px;"><i class="fa fa-folder-open"></i></div>
      <div>
        <h4 style="margin: 0; font-weight: 800; color: #1a1a1a;">Arsip Dokumen</h4>
        <p style="margin: 0; color: #888; font-size: 13px;">Daftar dokumen yang tersimpan</p>
      </div>
    </div>
    
    <div style="display: flex; gap: 10px;">
      <button class="btn btn-primary" onclick="bukaModalTambah()" style="background: #1e5fa6; border-radius: 10px; font-weight: 600;"><i class="fa fa-plus"></i> Tambah Dokumen</button>
      
      <!-- TOMBOL FILTER KATEGORI -->
      <div class="dropdown" style="display: inline-block;">
        <button class="btn btn-default dropdown-toggle" type="button" id="filterDropdown" onclick="toggleFilter()" style="border-radius: 10px; border: 1px solid #ddd; background: #fff; color: #64748b; font-weight: 600;">
          <i class="fa fa-filter"></i> Filter Kategori <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" id="filterMenu" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none; display: none; position: absolute; z-index: 9999;">
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="all">Semua Kategori</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="SOP">SOP</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Laporan">Laporan</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Akreditasi">Akreditasi</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Tanda Terima">Tanda Terima</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Service Report">Service Report</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Telaah Staff">Telaah Staff</a></li>
          <li><a href="javascript:void(0)" class="filter-btn" data-filter="Jadwal Piket">Jadwal Piket</a></li>
        </ul>
      </div>

      <div style="position: relative;">
        <i class="fa fa-search" style="position: absolute; left: 15px; top: 12px; color: #aaa;"></i>
        <input type="text" id="searchInput" class="form-control" placeholder="Cari dokumen..." style="border-radius: 10px; padding-left: 40px; width: 250px; border: 1px solid #ddd;">
      </div>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table class="table table-hover table-bordered" style="margin-bottom: 0; width: 100%; table-layout: fixed;" id="tabelArsip">
      <thead style="background: #f8fafc;">
        <tr>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 5%; text-align: center;">No</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 20%;">Nama Dokumen</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 15%; text-align: center;">Kategori</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 25%;">Deskripsi</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 15%; text-align: center;">Tanggal Upload</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 10%; text-align: center;">File</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 10%; text-align: center;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($arsip)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada arsip dokumen.</td></tr>
        <?php else: ?>
            <?php $no = 1; foreach($arsip as $row): ?>
            <tr class="data-row">
              <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= $no++ ?></td>
              <td style="padding: 15px; vertical-align: middle; font-weight: 600; color: #1e5fa6; word-wrap: break-word;"><?= esc($row['nama_dokumen']) ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;">
                  <span class="kategori-text" style="background: #e6f0fa; color: #1e5fa6; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap;"><?= esc($row['kategori']) ?></span>
              </td>
              <td style="padding: 15px; vertical-align: middle; word-wrap: break-word;"><?= esc($row['deskripsi']) ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;"><?= date('d-m-Y', strtotime($row['tanggal_upload'])) ?></td>
              <td style="padding: 15px; vertical-align: middle; text-align: center;">
                <?php if(!empty($row['file_dokumen'])): ?>
                    <a href="<?= base_url('uploads/arsip/' . $row['file_dokumen']) ?>" target="_blank" class="btn btn-xs btn-info" title="Download / Lihat" style="background: #17a2b8; border-radius: 5px;">
                        <i class="fa fa-download"></i>
                    </a>
                <?php else: ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td style="padding: 15px; vertical-align: middle; text-align: center; white-space: nowrap;">
                <button class="btn btn-sm btn-primary edit-btn" 
                        data-id="<?= $row['id_arsip'] ?>"
                        data-nama="<?= esc($row['nama_dokumen']) ?>"
                        data-kategori="<?= esc($row['kategori']) ?>"
                        data-deskripsi="<?= esc($row['deskripsi']) ?>"
                        data-tanggal="<?= esc($row['tanggal_upload']) ?>"
                        style="border-radius: 8px; background: #1e5fa6;"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id_arsip'] ?>" style="border-radius: 8px; background: #fa5252;"><i class="fa fa-trash"></i></button>
              </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->include('arsip/modals') ?>