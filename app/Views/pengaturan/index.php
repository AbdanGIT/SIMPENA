<!-- HEADER BANNER -->
<div class="hero-banner" style="background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.2)), url('<?= base_url('assets/images/Background_rsss.png') ?>'); background-size: cover; background-position: center; border-radius: 20px; padding: 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
  <div>
    <p style="color: #1e5fa6; font-weight: 600; margin-bottom: 5px;">Pengaturan Aplikasi,</p>
    <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin: 0;">Profil Pengguna</h2>
    <p style="margin-top: 5px; color: #555;">Kelola informasi akun dan keamanan Anda</p>
  </div>
  <div style="background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
    <i class="fa fa-user-cog" style="color: #1e5fa6; font-size: 24px; margin-bottom: 10px;"></i>
    <p style="margin: 0; font-weight: 700; color: #333;">Administrator</p>
  </div>
</div>

<!-- TAMPILKAN PESAN FLASH -->
<?php if(session()->getFlashdata('success')): ?>
  <div class="alert alert-success" style="border-radius: 10px; font-weight: 600;"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
  <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600;"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row">
  <!-- KOLOM KIRI: FORM PROFIL -->
  <div class="col-md-7">
    <div class="custom-card" style="padding: 25px; border-radius: 15px;">
      <h4 style="font-weight: 800; margin-bottom: 20px;"><i class="fa fa-id-card text-primary"></i> Informasi Profil</h4>
      
      <form action="<?= base_url('pengaturan/update_profil') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label style="font-weight: 600; color: #64748b; font-size: 14px;">Nama Lengkap</label>
              <input type="text" name="nama" class="form-control" value="<?= esc($user['pengguna_nama']) ?>" required style="border-radius: 10px; padding: 10px;">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label style="font-weight: 600; color: #64748b; font-size: 14px;">Username</label>
              <input type="text" name="username" class="form-control" value="<?= esc($user['pengguna_username']) ?>" required style="border-radius: 10px; padding: 10px;">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label style="font-weight: 600; color: #64748b; font-size: 14px;">Email</label>
          <input type="email" name="email" class="form-control" value="<?= esc($user['pengguna_email']) ?>" required style="border-radius: 10px; padding: 10px;">
        </div>

        <div class="form-group">
          <label style="font-weight: 600; color: #64748b; font-size: 14px;">Ganti Foto Profil (Opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/*" style="border-radius: 10px; padding: 10px;">
        </div>

        <button type="submit" class="btn btn-primary" style="background: #1e5fa6; border-radius: 10px; font-weight: 700; padding: 10px 20px; width: 100%;">
          <i class="fa fa-save"></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>

  <!-- KOLOM KANAN: FOTO & PASSWORD -->
  <div class="col-md-5">
    <!-- FOTO PROFIL SAAT INI -->
    <div class="custom-card text-center" style="padding: 25px; border-radius: 15px; margin-bottom: 20px;">
      <img src="<?= base_url('assets/images/' . ($user['pengguna_photo'] ?? 'user_blank.png')) ?>" 
           onerror="this.src='<?= base_url('assets/images/user_blank.png') ?>'"
           style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #f0f0f0; margin-bottom: 15px;">
      <h4 style="font-weight: 800; margin: 0;"><?= esc($user['pengguna_nama']) ?></h4>
      <p style="color: #888;"><?= esc($user['pengguna_email']) ?></p>
    </div>

    <!-- FORM GANTI PASSWORD -->
    <div class="custom-card" style="padding: 25px; border-radius: 15px;">
      <h4 style="font-weight: 800; margin-bottom: 20px;"><i class="fa fa-lock text-warning"></i> Ganti Password</h4>
      
      <form action="<?= base_url('pengaturan/update_password') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group">
          <label style="font-weight: 600; color: #64748b; font-size: 14px;">Password Lama</label>
          <input type="password" name="pass_lama" class="form-control" required style="border-radius: 10px; padding: 10px;">
        </div>
        <div class="form-group">
          <label style="font-weight: 600; color: #64748b; font-size: 14px;">Password Baru</label>
          <input type="password" name="pass_baru" class="form-control" required style="border-radius: 10px; padding: 10px;">
        </div>
        <div class="form-group">
          <label style="font-weight: 600; color: #64748b; font-size: 14px;">Konfirmasi Password Baru</label>
          <input type="password" name="pass_konfirmasi" class="form-control" required style="border-radius: 10px; padding: 10px;">
        </div>

        <button type="submit" class="btn btn-warning" style="border-radius: 10px; font-weight: 700; padding: 10px 20px; width: 100%;">
          <i class="fa fa-key"></i> Ubah Password
        </button>
      </form>
    </div>
  </div>
</div>