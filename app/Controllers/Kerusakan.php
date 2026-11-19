<?php

namespace App\Controllers;

use App\Libraries\Berkas;
use App\Models\ApprovalModel;
use App\Models\KerusakanModel;
use RuntimeException;

class Kerusakan extends BaseController
{
    /** Foto bukti disimpan langsung di public/uploads. */
    private const FOLDER_FOTO = '';

    private const MAKS_FOTO_MB = 10;

    /** Status yang diizinkan tabel (enum). */
    private const STATUS = [
        'Dalam Perbaikan',
        'Selesai Perbaikan',
        'Di Gudang',
        'Di Ruangan',
        'Sudah Penghapusan Aset',
    ];

    protected KerusakanModel $mKerusakan;

    public function __construct()
    {
        $this->mKerusakan = new KerusakanModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Kerusakan Barang | SIMPENA',
            'kerusakan' => $this->mKerusakan->orderBy('id_kerusakan', 'DESC')->findAll(),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('kerusakan/index', $data)
             . view('layout/footer', $data);
    }

    public function simpan()
    {
        $noBarang   = trim((string) $this->request->getPost('addNoBarang'));
        $namaBarang = trim((string) $this->request->getPost('addNama'));
        $merek      = trim((string) $this->request->getPost('addMerek'));
        $tglService = $this->tanggalAtauNull($this->request->getPost('addTglService'));

        if ($namaBarang === '') {
            return $this->json(false, 'Nama barang wajib diisi.', [], 422);
        }

        // Cegah data dobel (barang + merek + tanggal service yang sama).
        $cekData = $this->mKerusakan->where([
            'no_barang'   => $noBarang,
            'nama_barang' => $namaBarang,
            'merek'       => $merek,
            'tgl_service' => $tglService,
        ])->first();

        if ($cekData) {
            return $this->json(false, 'Data dengan Nomor Barang, Nama, Merek, dan Tanggal Service yang sama sudah ada!', [], 409);
        }

        try {
            $namaFile = Berkas::simpan(
                $this->request->getFile('addFoto'),
                self::FOLDER_FOTO,
                Berkas::GAMBAR,
                self::MAKS_FOTO_MB
            );

            $this->mKerusakan->save($this->payload() + ['bukti_foto' => $namaFile]);

            return $this->json(true, 'Data berhasil disimpan!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Kerusakan::simpan - ' . $e->getMessage());

            return $this->json(false, 'Gagal menyimpan data: ' . $e->getMessage(), [], 500);
        }
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id_kerusakan');

        if ($id <= 0) {
            return $this->json(false, 'ID data tidak valid.', [], 422);
        }

        $dataLama = $this->mKerusakan->find($id);
        if (! $dataLama) {
            return $this->json(false, 'Data tidak ditemukan.', [], 404);
        }

        if (trim((string) $this->request->getPost('addNama')) === '') {
            return $this->json(false, 'Nama barang wajib diisi.', [], 422);
        }

        // ---- Admin biasa: ajukan perubahan ke Super Admin ----
        if ($this->isAdmin()) {
            $modelApproval = new ApprovalModel();
            $modelApproval->save([
                'tipe_aksi'     => 'edit',
                'tabel_target'  => 'kerusakan',
                'id_target'     => $id,
                // Simpan HANYA kolom tabel, bukan seluruh POST (csrf, file, dll).
                'data_baru'     => json_encode($this->payload(), JSON_UNESCAPED_UNICODE),
                'diajukan_oleh' => (string) session()->get('nama'),
                'status'        => 'PENDING',
            ]);

            return $this->json(true, 'Perubahan dikirim ke Super Admin untuk disetujui.');
        }

        // ---- Super Admin: langsung eksekusi ----
        try {
            $namaFile = $dataLama['bukti_foto'] ?? null;

            $fileFoto = $this->request->getFile('editFoto');
            if ($fileFoto !== null && $fileFoto->getError() !== UPLOAD_ERR_NO_FILE) {
                $namaBaru = Berkas::simpan($fileFoto, self::FOLDER_FOTO, Berkas::GAMBAR, self::MAKS_FOTO_MB);
                if ($namaBaru !== null) {
                    Berkas::hapus(self::FOLDER_FOTO, $namaFile);
                    $namaFile = $namaBaru;
                }
            }

            $this->mKerusakan->update($id, $this->payload() + ['bukti_foto' => $namaFile]);

            return $this->json(true, 'Data berhasil diupdate!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Kerusakan::update - ' . $e->getMessage());

            return $this->json(false, 'Gagal mengupdate data: ' . $e->getMessage(), [], 500);
        }
    }

    public function hapus($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID data tidak valid.', [], 422);
        }

        $data = $this->mKerusakan->find($id);
        if (! $data) {
            return $this->json(false, 'Data tidak ditemukan.', [], 404);
        }

        // Admin biasa: ajukan penghapusan.
        if ($this->isAdmin()) {
            $modelApproval = new ApprovalModel();
            $modelApproval->save([
                'tipe_aksi'     => 'hapus',
                'tabel_target'  => 'kerusakan',
                'id_target'     => $id,
                'diajukan_oleh' => (string) session()->get('nama'),
                'status'        => 'PENDING',
            ]);

            return $this->json(true, 'Permintaan hapus dikirim ke Super Admin.');
        }

        try {
            $this->mKerusakan->delete($id);
            Berkas::hapus(self::FOLDER_FOTO, $data['bukti_foto'] ?? null);

            return $this->json(true, 'Data berhasil dihapus!');
        } catch (\Throwable $e) {
            log_message('error', 'Kerusakan::hapus - ' . $e->getMessage());

            return $this->json(false, 'Gagal menghapus data: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Susun data kerusakan dari input form (hanya kolom yang ada di tabel).
     *
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        $status = (string) $this->request->getPost('addStatus');

        return [
            'no_barang'      => trim((string) $this->request->getPost('addNoBarang')),
            'nama_barang'    => trim((string) $this->request->getPost('addNama')),
            'merek'          => trim((string) $this->request->getPost('addMerek')),
            'ruangan'        => trim((string) $this->request->getPost('addRuangan')),
            'tempat_service' => trim((string) $this->request->getPost('addTempat')),
            'tgl_ambil'      => $this->tanggalAtauNull($this->request->getPost('addTglAmbil')),
            'tgl_service'    => $this->tanggalAtauNull($this->request->getPost('addTglService')),
            'kelengkapan'    => trim((string) $this->request->getPost('addKelengkapan')),
            'masalah'        => trim((string) $this->request->getPost('addMasalah')),
            'keterangan'     => trim((string) $this->request->getPost('addKeterangan')),
            'status'         => in_array($status, self::STATUS, true) ? $status : 'Dalam Perbaikan',
        ];
    }

    /** Ubah tanggal kosong menjadi NULL supaya tidak error di kolom DATE. */
    private function tanggalAtauNull($nilai): ?string
    {
        $nilai = trim((string) $nilai);

        if ($nilai === '') {
            return null;
        }

        $ts = strtotime($nilai);

        return $ts === false ? null : date('Y-m-d', $ts);
    }
}

