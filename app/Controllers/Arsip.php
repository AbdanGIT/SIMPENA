<?php

namespace App\Controllers;

use App\Libraries\Berkas;
use App\Models\ArsipModel;
use RuntimeException;

class Arsip extends BaseController
{
    private const FOLDER_DOK = 'arsip';

    private const MAKS_DOK_MB = 25;

    protected ArsipModel $mArsip;

    public function __construct()
    {
        $this->mArsip = new ArsipModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Arsip Dokumen | SIMPENA',
            'arsip' => $this->mArsip->orderBy('id_arsip', 'DESC')->findAll(),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('arsip/index', $data)
             . view('layout/footer', $data);
    }

    public function simpan()
    {
        $nama = trim((string) $this->request->getPost('addNama'));

        if ($nama === '') {
            return $this->json(false, 'Nama dokumen wajib diisi.', [], 422);
        }

        try {
            $namaFile = Berkas::simpan(
                $this->request->getFile('addFile'),
                self::FOLDER_DOK,
                Berkas::DOKUMEN,
                self::MAKS_DOK_MB
            );

            if ($namaFile === null) {
                return $this->json(false, 'File dokumen wajib diunggah.', [], 422);
            }

            $this->mArsip->save([
                'nama_dokumen'   => $nama,
                'kategori'       => $this->request->getPost('addKategori') ?: 'Lainnya',
                'deskripsi'      => trim((string) $this->request->getPost('addDeskripsi')),
                'file_dokumen'   => $namaFile,
                'tanggal_upload' => $this->tanggalAtauNull($this->request->getPost('addTanggal')) ?? date('Y-m-d'),
            ]);

            return $this->json(true, 'Dokumen berhasil diarsipkan!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Arsip::simpan - ' . $e->getMessage());

            return $this->json(false, 'Gagal menyimpan dokumen: ' . $e->getMessage(), [], 500);
        }
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id_arsip');

        if ($id <= 0) {
            return $this->json(false, 'ID arsip tidak valid.', [], 422);
        }

        $dataLama = $this->mArsip->find($id);
        if (! $dataLama) {
            return $this->json(false, 'Dokumen tidak ditemukan.', [], 404);
        }

        $nama = trim((string) $this->request->getPost('addNama'));
        if ($nama === '') {
            return $this->json(false, 'Nama dokumen wajib diisi.', [], 422);
        }

        try {
            $namaFile = $dataLama['file_dokumen'] ?? null;

            $fileDok = $this->request->getFile('editFile');
            if ($fileDok !== null && $fileDok->getError() !== UPLOAD_ERR_NO_FILE) {
                $namaBaru = Berkas::simpan($fileDok, self::FOLDER_DOK, Berkas::DOKUMEN, self::MAKS_DOK_MB);
                if ($namaBaru !== null) {
                    Berkas::hapus(self::FOLDER_DOK, $namaFile);
                    $namaFile = $namaBaru;
                }
            }

            $this->mArsip->update($id, [
                'nama_dokumen'   => $nama,
                'kategori'       => $this->request->getPost('addKategori') ?: ($dataLama['kategori'] ?? 'Lainnya'),
                'deskripsi'      => trim((string) $this->request->getPost('addDeskripsi')),
                'file_dokumen'   => $namaFile,
                'tanggal_upload' => $this->tanggalAtauNull($this->request->getPost('addTanggal')) ?? ($dataLama['tanggal_upload'] ?? date('Y-m-d')),
            ]);

            return $this->json(true, 'Dokumen berhasil diupdate!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Arsip::update - ' . $e->getMessage());

            return $this->json(false, 'Gagal mengupdate dokumen: ' . $e->getMessage(), [], 500);
        }
    }

    public function hapus($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID arsip tidak valid.', [], 422);
        }

        $data = $this->mArsip->find($id);
        if (! $data) {
            return $this->json(false, 'Dokumen tidak ditemukan.', [], 404);
        }

        try {
            $this->mArsip->delete($id);
            Berkas::hapus(self::FOLDER_DOK, $data['file_dokumen'] ?? null);

            return $this->json(true, 'Dokumen berhasil dihapus!');
        } catch (\Throwable $e) {
            log_message('error', 'Arsip::hapus - ' . $e->getMessage());

            return $this->json(false, 'Gagal menghapus dokumen: ' . $e->getMessage(), [], 500);
        }
    }

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
