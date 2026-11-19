<?php

namespace App\Controllers;

use App\Libraries\Berkas;
use App\Models\MutasiModel;
use App\Models\PencatatanModel;
use RuntimeException;

class Pencatatan extends BaseController
{
    /** Folder unggahan foto inventaris (relatif terhadap public/uploads). */
    private const FOLDER_FOTO = 'inventaris';

    /** Batas ukuran foto (MB). */
    private const MAKS_FOTO_MB = 10;

    protected PencatatanModel $mPencatatan;

    public function __construct()
    {
        $this->mPencatatan = new PencatatanModel();
    }

    public function index()
    {
        $semuaBarang = $this->mPencatatan->orderBy('id_komputer', 'DESC')->findAll();

        // Statistik dihitung dari kolom kategori, bukan menebak nama barang.
        $totalPrinter  = 0;
        $totalKomputer = 0;
        $totalMonitor  = 0;
        $sedangService = 0;

        foreach ($semuaBarang as $b) {
            $kategori = strtolower(trim((string) ($b['kategori'] ?? '')));

            if (str_contains($kategori, 'printer')) {
                $totalPrinter++;
            } elseif (str_contains($kategori, 'monitor')) {
                $totalMonitor++;
            } elseif (str_contains($kategori, 'komputer') || str_contains($kategori, 'laptop')) {
                $totalKomputer++;
            }

            if (($b['status'] ?? '') === 'Dalam Perbaikan') {
                $sedangService++;
            }
        }

        $modelMutasi  = new MutasiModel();
        $totalRiwayat = $modelMutasi->countAllResults();

        $data = [
            'title'          => 'Pencatatan Komputer | SIMPENA',
            'barang'         => $semuaBarang,
            'total_printer'  => $totalPrinter,
            'total_komputer' => $totalKomputer,
            'total_monitor'  => $totalMonitor,
            'total_riwayat'  => $totalRiwayat,
            'sedang_service' => $sedangService,
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('pencatatan/index', $data)
             . view('layout/footer', $data);
    }

    public function simpan()
    {
        if (! $this->validate([
            'nama_barang' => 'required|max_length[150]',
            'kategori'    => 'required|max_length[100]',
        ], [
            'nama_barang' => ['required' => 'Nama barang wajib diisi.'],
            'kategori'    => ['required' => 'Kategori/ruangan wajib dipilih.'],
        ])) {
            return $this->json(false, implode(' ', $this->validator->getErrors()), [], 422);
        }

        try {
            $namaFoto = Berkas::simpan(
                $this->request->getFile('foto'),
                self::FOLDER_FOTO,
                Berkas::GAMBAR,
                self::MAKS_FOTO_MB
            );

            $this->mPencatatan->save([
                'kategori'    => $this->request->getPost('kategori'),
                'nama_barang' => $this->request->getPost('nama_barang'),
                'merek'       => $this->request->getPost('merek'),
                'seri_tipe'   => $this->request->getPost('seri_tipe'),
                'status'      => $this->request->getPost('status') ?: 'Berfungsi',
                'status_fix'  => $this->request->getPost('status_fix') ?: 'belum_fix',
                'keterangan'  => $this->request->getPost('keterangan'),
                'foto'        => $namaFoto,
            ]);

            return $this->json(true, 'Data berhasil disimpan!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Pencatatan::simpan - ' . $e->getMessage());

            return $this->json(false, 'Gagal menyimpan data: ' . $e->getMessage(), [], 500);

        }
    }


    public function update()
    {
        $id = (int) $this->request->getPost('id_komputer');

        if ($id <= 0) {
            return $this->json(false, 'ID barang tidak valid.', [], 422);
        }

        $dataLama = $this->mPencatatan->find($id);
        if (! $dataLama) {
            return $this->json(false, 'Data barang tidak ditemukan.', [], 404);
        }

        if (! $this->validate([
            'nama_barang' => 'required|max_length[150]',
            'kategori'    => 'required|max_length[100]',
        ])) {
            return $this->json(false, implode(' ', $this->validator->getErrors()), [], 422);
        }

        try {
            $namaFoto = $dataLama['foto'] ?? null;

            $fileFoto = $this->request->getFile('foto');
            if ($fileFoto !== null && $fileFoto->getError() !== UPLOAD_ERR_NO_FILE) {
                $namaBaru = Berkas::simpan($fileFoto, self::FOLDER_FOTO, Berkas::GAMBAR, self::MAKS_FOTO_MB);
                if ($namaBaru !== null) {
                    $this->hapusFotoLama($namaFoto);
                    $namaFoto = $namaBaru;
                }
            }

            $this->mPencatatan->update($id, [
                'kategori'    => $this->request->getPost('kategori'),
                'nama_barang' => $this->request->getPost('nama_barang'),
                'merek'       => $this->request->getPost('merek'),
                'seri_tipe'   => $this->request->getPost('seri_tipe'),
                'status'      => $this->request->getPost('status') ?: 'Berfungsi',
                'status_fix'  => $this->request->getPost('status_fix') ?: 'belum_fix',
                'keterangan'  => $this->request->getPost('keterangan'),
                'foto'        => $namaFoto,
            ]);

            return $this->json(true, 'Data berhasil diupdate!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Pencatatan::update - ' . $e->getMessage());

            return $this->json(false, 'Gagal mengupdate data: ' . $e->getMessage(), [], 500);
        }
    }

    public function hapus($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID barang tidak valid.', [], 422);
        }

        $data = $this->mPencatatan->find($id);
        if (! $data) {
            return $this->json(false, 'Data barang tidak ditemukan.', [], 404);
        }

        try {
            $this->mPencatatan->delete($id);
            $this->hapusFotoLama($data['foto'] ?? null);

            return $this->json(true, 'Data berhasil dihapus!');
        } catch (\Throwable $e) {
            log_message('error', 'Pencatatan::hapus - ' . $e->getMessage());

            return $this->json(false, 'Gagal menghapus data: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Pindahkan barang ke ruangan lain + catat riwayat mutasi.
     */
    public function pindah_ruangan()
    {
        $id            = (int) $this->request->getPost('id_komputer');
        $ruanganTujuan = trim((string) $this->request->getPost('ruangan_tujuan'));
        $keterangan    = trim((string) $this->request->getPost('keterangan'));

        if ($id <= 0) {
            return $this->json(false, 'ID barang tidak valid.', [], 422);
        }

        if ($ruanganTujuan === '') {
            return $this->json(false, 'Ruangan tujuan wajib dipilih.', [], 422);
        }

        $dataLama = $this->mPencatatan->find($id);
        if (! $dataLama) {
            return $this->json(false, 'Barang tidak ditemukan.', [], 404);
        }

        $ruanganAsal = (string) ($dataLama['kategori'] ?? '');
        if ($ruanganAsal === $ruanganTujuan) {
            return $this->json(false, 'Ruangan tujuan masih sama dengan ruangan asal.', [], 422);
        }

        try {
            $namaFoto = $dataLama['foto'] ?? null;

            $fileFoto = $this->request->getFile('foto');
            if ($fileFoto !== null && $fileFoto->getError() !== UPLOAD_ERR_NO_FILE) {
                $namaBaru = Berkas::simpan($fileFoto, self::FOLDER_FOTO, Berkas::GAMBAR, self::MAKS_FOTO_MB);
                if ($namaBaru !== null) {
                    $this->hapusFotoLama($namaFoto);
                    $namaFoto = $namaBaru;
                }
            }

            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Update kategori (ruangan) barang.
            $this->mPencatatan->update($id, [
                'kategori' => $ruanganTujuan,
                'foto'     => $namaFoto,
            ]);

            // 2. Catat riwayat mutasi.
            $modelMutasi = new MutasiModel();
            $modelMutasi->save([
                'id_komputer'    => $id,
                'nama_barang'    => $dataLama['nama_barang'],
                'ruangan_asal'   => $ruanganAsal,
                'ruangan_tujuan' => $ruanganTujuan,
                'keterangan'     => $keterangan,
                'foto'           => $namaFoto,
                'tanggal_mutasi' => date('Y-m-d H:i:s'),
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->json(false, 'Gagal memindahkan barang (transaksi dibatalkan).', [], 500);
            }

            return $this->json(true, 'Barang berhasil dipindahkan!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Pencatatan::pindah_ruangan - ' . $e->getMessage());

            return $this->json(false, 'Gagal memindahkan barang: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Hapus foto inventaris hanya bila tidak dipakai di riwayat mutasi,
     * supaya arsip riwayat tidak menampilkan gambar rusak.
     */
    private function hapusFotoLama(?string $nama): void
    {
        if ($nama === null || $nama === '') {
            return;
        }

        $dipakai = (new MutasiModel())->where('foto', $nama)->countAllResults();

        if ($dipakai === 0) {
            Berkas::hapus(self::FOLDER_FOTO, $nama);
        }
    }
}
