<!-- Header Banner -->
<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Selamat Datang,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;"><?= session()->get('nama') ?></h2>
    <p style="margin-top: 5px; color: #555;">Kelola laporan service IT dengan mudah dan cepat</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
    <i class="fa fa-calendar" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;"><?= date('l, d F Y') ?></p>
    <p style="margin: 0; font-size: 13px; color: #888;"><?= date('H:i') ?> WIB</p>
  </div>
</div>

<!-- Tabel Data Service Report -->
<div class="custom-card" style="padding: 25px; border-radius: 15px; width: 100%;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 15px;">
      <div style="background: #e6f0fa; color: #1e5fa6; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 20px;"><i class="fa fa-file-text"></i></div>
      <div>
        <h4 style="margin: 0; font-weight: 800; color: #1a1a1a;">Service Report</h4>
        <p style="margin: 0; color: #888; font-size: 13px;">Daftar laporan service IT</p>
      </div>
    </div>
    
    <div style="display: flex; gap: 10px;">
      <button class="btn btn-primary" onclick="bukaModalTambah()" style="background: #1e5fa6; border-radius: 10px; font-weight: 600;"><i class="fa fa-plus"></i> Buat Service Report</button>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table class="table table-hover table-bordered" style="margin-bottom: 0; width: 100%; table-layout: fixed;" id="tabelServiceReport">
      <thead style="background: #f8fafc;">
        <tr>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 5%; text-align: center;">No</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 15%;">No. Report</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 15%;">Tanggal</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 20%;">Prasarana</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 15%;">Ruangan</th>
          <th style="padding: 15px; color: #64748b; font-weight: 700; width: 15%; text-align: center;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($reports)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan service.</td></tr>
        <?php else: ?>
            <?php $no=1; foreach($reports as $row): ?>
            <tr class="data-row">
              <td style="padding: 15px; text-align: center; vertical-align: middle;"><?= $no++ ?></td>
              <td style="padding: 15px; font-weight: 600; color: #1e5fa6; vertical-align: middle;"><?= esc($row['no_report']) ?></td>
              <td style="padding: 15px; vertical-align: middle;"><?= !empty($row['tanggal']) ? date('d-m-Y', strtotime($row['tanggal'])) : '-' ?></td>
              <td style="padding: 15px; vertical-align: middle;"><?= esc($row['nama_prasarana']) ?></td>
              <td style="padding: 15px; vertical-align: middle;"><?= esc($row['nama_ruangan']) ?></td>
              <td style="padding: 15px; text-align: center; vertical-align: middle; white-space: nowrap;">
                <a href="<?= base_url('service_report/print/' . $row['id_report']) ?>" target="_blank" class="btn btn-sm btn-success">
                    <i class="fa fa-print"></i>
                </a>
                
                <button class="btn btn-sm btn-primary edit-btn" 
                        data-id="<?= $row['id_report'] ?>"
                        data-no="<?= esc($row['no_report']) ?>"
                        data-tgl="<?= esc($row['tanggal']) ?>"
                        data-prasarana="<?= esc($row['nama_prasarana']) ?>"
                        data-merek="<?= esc($row['merek']) ?>"
                        data-tipe="<?= esc($row['tipe_model']) ?>"
                        data-seri="<?= esc($row['no_seri']) ?>"
                        data-ruangan="<?= esc($row['nama_ruangan']) ?>"
                        data-tahun="<?= esc($row['tahun']) ?>"
                        data-permasalahan="<?= htmlspecialchars($row['permasalahan'] ?? '', ENT_QUOTES) ?>"
                        data-keterangan="<?= htmlspecialchars($row['keterangan'] ?? '', ENT_QUOTES) ?>"
                        data-service="<?= htmlspecialchars($row['service_type'] ?? '', ENT_QUOTES) ?>"
                        data-periksa="<?= htmlspecialchars($row['pemeriksaan'] ?? '', ENT_QUOTES) ?>"
                        data-hasil="<?= htmlspecialchars($row['hasil_pemeriksaan'] ?? '', ENT_QUOTES) ?>"
                        data-kiri="<?= htmlspecialchars($row['ttd_kiri'] ?? '', ENT_QUOTES) ?>"
                        data-tengah="<?= htmlspecialchars($row['ttd_tengah'] ?? '', ENT_QUOTES) ?>"
                        data-kanan="<?= htmlspecialchars($row['ttd_kanan'] ?? '', ENT_QUOTES) ?>"
                        style="border-radius: 8px; background: #1e5fa6;">
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id_report'] ?>" style="border-radius: 8px; background: #fa5252;">
                    <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->include('service_report/modals') ?>