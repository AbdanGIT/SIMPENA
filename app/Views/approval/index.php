<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #dc2626; font-weight: 600; margin-bottom: 5px;">Super Admin Area,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;">Persetujuan Perubahan Data</h2>
    <p style="margin-top: 5px; color: #555;">Daftar permintaan edit/hapus data dari Admin yang menunggu persetujuan Anda.</p>
  </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
  <div class="alert alert-success" style="border-radius: 10px; font-weight: 600;"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
  <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600;"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="custom-card" style="padding: 25px; border-radius: 15px;">
  <div style="overflow-x: auto;">
    <table class="table table-hover table-bordered">
      <thead style="background: #f8fafc;">
        <tr>
          <th style="padding: 15px; text-align: center;">No</th>
          <th style="padding: 15px;">Waktu Permintaan</th>
          <th style="padding: 15px;">Diminta Oleh</th>
          <th style="padding: 15px; text-align: center;">Tipe Aksi</th>
          <th style="padding: 15px;">Target Tabel</th>
          <th style="padding: 15px; text-align: center;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($approval)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada permintaan persetujuan saat ini.</td></tr>
        <?php else: ?>
            <?php $no = 1; foreach($approval as $row): ?>
            <tr>
              <td style="padding: 15px; text-align: center;"><?= $no++ ?></td>
              <td style="padding: 15px;"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
              <td style="padding: 15px; font-weight: 600;"><?= esc($row['diajukan_oleh']) ?></td>
              <td style="padding: 15px; text-align: center;">
                <?php if($row['tipe_aksi'] == 'hapus'): ?>
                  <span class="label label-danger" style="padding: 6px 12px; border-radius: 10px;">HAPUS</span>
                <?php else: ?>
                  <span class="label label-warning" style="padding: 6px 12px; border-radius: 10px;">EDIT</span>
                <?php endif; ?>
              </td>
              <td style="padding: 15px;">Tabel: <strong><?= esc($row['tabel_target']) ?></strong> (ID: <?= $row['id_target'] ?>)</td>
              <td style="padding: 15px; text-align: center; white-space: nowrap;">
                <form action="<?= base_url('approval/setujui/'.$row['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Setujui dan eksekusi perubahan ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px;">
                    <i class="fa fa-check"></i> Setujui
                  </button>
                </form>
                <form action="<?= base_url('approval/tolak/'.$row['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Tolak permintaan ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;">
                    <i class="fa fa-times"></i> Tolak
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>