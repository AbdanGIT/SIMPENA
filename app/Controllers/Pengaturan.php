<?php

namespace App\Controllers;

use App\Libraries\Berkas;
use App\Models\UserModel;
use RuntimeException;

class Pengaturan extends BaseController
{
    /** Foto profil disimpan di public/assets/images. */
    private const ROOT_FOTO = 'assets/images';

    private const MAKS_FOTO_MB = 5;

    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $id   = (int) session()->get('idadmin');
        $user = $id > 0 ? $this->userModel->find($id) : null;

        if (! $user) {
            return redirect()->to(base_url('logout'))->with('error', 'Data pengguna tidak ditemukan, silakan login ulang.');
        }

        $data = [
            'title' => 'Pengaturan Profil | SIMPENA',
            'user'  => $user,
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('pengaturan/index', $data)
             . view('layout/footer', $data);
    }

    public function update_profil()
    {
        $id       = (int) session()->get('idadmin');
        $dataLama = $id > 0 ? $this->userModel->find($id) : null;

        if (! $dataLama) {
            return redirect()->to(base_url('login'))->with('msg', 'Sesi tidak valid, silakan login ulang.');
        }

        $nama     = trim((string) $this->request->getPost('nama'));
        $email    = trim((string) $this->request->getPost('email'));
        $username = trim((string) $this->request->getPost('username'));

        if ($nama === '' || $username === '') {
            return redirect()->to(base_url('pengaturan'))->with('error', 'Nama dan username wajib diisi.');
        }

        if ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to(base_url('pengaturan'))->with('error', 'Format email tidak valid.');
        }

        $dipakai = $this->userModel
            ->where('pengguna_username', $username)
            ->where('pengguna_id !=', $id)
            ->countAllResults();

        if ($dipakai > 0) {
            return redirect()->to(base_url('pengaturan'))->with('error', 'Username "' . esc($username) . '" sudah dipakai pengguna lain.');
        }

        $namaFile = $dataLama['pengguna_photo'] ?? 'user_blank.png';

        try {
            $fileFoto = $this->request->getFile('foto');
            if ($fileFoto !== null && $fileFoto->getError() !== UPLOAD_ERR_NO_FILE) {
                $namaBaru = Berkas::simpan($fileFoto, '', Berkas::GAMBAR, self::MAKS_FOTO_MB, self::ROOT_FOTO);
                if ($namaBaru !== null) {
                    if ($namaFile !== 'user_blank.png') {
                        Berkas::hapus('', $namaFile, self::ROOT_FOTO);
                    }
                    $namaFile = $namaBaru;
                }
            }

            $this->userModel->update($id, [
                'pengguna_nama'     => $nama,
                'pengguna_email'    => $email !== '' ? $email : null,
                'pengguna_username' => $username,
                'pengguna_photo'    => $namaFile,
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('pengaturan'))->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', 'Pengaturan::update_profil - ' . $e->getMessage());

            return redirect()->to(base_url('pengaturan'))->with('error', 'Profil gagal diperbarui: ' . $e->getMessage());
        }

        session()->set([
            'nama'  => $nama,
            'user'  => $username,
            'photo' => $namaFile,
        ]);

        return redirect()->to(base_url('pengaturan'))->with('success', 'Profil berhasil diperbarui!');
    }

    public function update_password()
    {
        $id       = (int) session()->get('idadmin');
        $dataLama = $id > 0 ? $this->userModel->find($id) : null;

        if (! $dataLama) {
            return redirect()->to(base_url('login'))->with('msg', 'Sesi tidak valid, silakan login ulang.');
        }

        $passLama       = (string) $this->request->getPost('pass_lama');
        $passBaru       = (string) $this->request->getPost('pass_baru');
        $passKonfirmasi = (string) $this->request->getPost('pass_konfirmasi');

        $cek = $this->userModel->cekLogin((string) $dataLama['pengguna_username'], $passLama);

        if ($cek === null) {
            return redirect()->to(base_url('pengaturan'))->with('error', 'Password lama salah!');
        }

        if (strlen($passBaru) < 6) {
            return redirect()->to(base_url('pengaturan'))->with('error', 'Password baru minimal 6 karakter.');
        }

        if ($passBaru !== $passKonfirmasi) {
            return redirect()->to(base_url('pengaturan'))->with('error', 'Konfirmasi password baru tidak cocok!');
        }

        $this->userModel->update($id, [
            'pengguna_password' => password_hash($passBaru, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(base_url('pengaturan'))->with('success', 'Password berhasil diubah!');
    }
}
