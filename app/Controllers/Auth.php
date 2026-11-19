<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    /** Maksimal percobaan login sebelum dikunci sementara. */
    private const MAKS_COBA = 5;

    /** Lama kunci (detik) setelah percobaan login gagal terlalu banyak. */
    private const DURASI_KUNCI = 60;

    public function index()
    {
        if (session()->get('masuk')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login');
    }

    public function authenticate()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        // ---- Proteksi brute force (berbasis session) ----
        $kunciSampai = (int) session()->get('login_kunci_sampai');
        if ($kunciSampai > time()) {
            $sisa = $kunciSampai - time();
            session()->setFlashdata('msg', '<div class="alert alert-danger">Terlalu banyak percobaan login. Coba lagi dalam ' . $sisa . ' detik.</div>');
            return redirect()->to(base_url('login'));
        }

        if ($username === '' || $password === '') {
            session()->setFlashdata('msg', '<div class="alert alert-danger">Username dan password wajib diisi.</div>');
            return redirect()->to(base_url('login'));
        }

        $model = new UserModel();
        $user  = $model->cekLogin($username, $password);

        if ($user === null) {
            $coba = (int) session()->get('login_coba') + 1;
            session()->set('login_coba', $coba);

            if ($coba >= self::MAKS_COBA) {
                session()->set([
                    'login_coba'         => 0,
                    'login_kunci_sampai' => time() + self::DURASI_KUNCI,
                ]);
                session()->setFlashdata('msg', '<div class="alert alert-danger">Terlalu banyak percobaan login. Akun dikunci sementara selama ' . self::DURASI_KUNCI . ' detik.</div>');
            } else {
                $sisa = self::MAKS_COBA - $coba;
                session()->setFlashdata('msg', '<div class="alert alert-danger">Username atau Password Salah! (sisa ' . $sisa . ' percobaan)</div>');
            }

            return redirect()->to(base_url('login'));
        }

        // ---- Login berhasil ----
        // Naikkan hash password lama (MD5 / plaintext / cost lama) ke bcrypt.
        $hashDb = (string) $user['pengguna_password'];
        $info   = password_get_info($hashDb);
        if (($info['algoName'] ?? 'unknown') === 'unknown' || password_needs_rehash($hashDb, PASSWORD_DEFAULT)) {
            $model->update($user['pengguna_id'], [
                'pengguna_password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        }

        // Cegah session fixation: buat ID session baru.
        session()->regenerate(true);
        session()->remove(['login_coba', 'login_kunci_sampai']);

        session()->set([
            'masuk'   => true,
            'user'    => $user['pengguna_username'],
            'akses'   => $user['pengguna_level'],
            'idadmin' => $user['pengguna_id'],
            'nama'    => $user['pengguna_nama'],
            'photo'   => $user['pengguna_photo'] ?? null,
            'role'    => ((string) $user['pengguna_level'] === '1') ? 'superadmin' : 'admin',
        ]);

        return redirect()->to(base_url('dashboard'));
    }

    public function logout()
    {
        $sesi = session();
        $sesi->remove(['masuk', 'user', 'akses', 'idadmin', 'nama', 'photo', 'role']);
        $sesi->destroy();

        return redirect()->to(base_url('login'));
    }
}
