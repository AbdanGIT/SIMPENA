<?php

namespace App\Controllers;

use App\Libraries\Berkas;
use App\Models\ApprovalModel;

/**
 * Persetujuan perubahan data (khusus Super Admin).
 *
 * Admin biasa tidak langsung mengubah/menghapus data kerusakan; aksinya
 * masuk ke tbl_approval lalu dieksekusi di sini setelah disetujui.
 */
class Approval extends BaseController
{
    /** Tabel yang boleh dieksekusi lewat approval (whitelist keamanan). */
    private const TABEL = [
        'kerusakan' => 'tbl_kerusakan',
    ];

    /** Kolom yang boleh ditulis ulang saat approval "edit" dieksekusi. */
    private const KOLOM = [
        'kerusakan' => [
            'no_barang', 'nama_barang', 'merek', 'ruangan', 'tempat_service',
            'tgl_ambil', 'tgl_service', 'kelengkapan', 'masalah', 'keterangan', 'status',
        ],
    ];

    public function index()
    {
        if (! $this->isSuperadmin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak. Hanya Super Admin.');
        }

        $model = new ApprovalModel();
        $data  = [
            'title'    => 'Persetujuan Perubahan Data | SIMPENA',
            'approval' => $model->where('status', 'PENDING')->orderBy('id', 'DESC')->findAll(),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('approval/index', $data)
             . view('layout/footer', $data);
    }

    public function setujui($id = null)
    {
        if (! $this->isSuperadmin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak. Hanya Super Admin.');
        }

        $id    = (int) $id;
        $model = new ApprovalModel();
        $req   = $id > 0 ? $model->find($id) : null;

        if (! $req) {
            return redirect()->to(base_url('approval'))->with('error', 'Permintaan tidak ditemukan.');
        }

        if ($req['status'] !== 'PENDING') {
            return redirect()->to(base_url('approval'))->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        if (! isset(self::TABEL[$req['tabel_target']])) {
            return redirect()->to(base_url('approval'))->with('error', 'Tabel target tidak dikenali: ' . esc($req['tabel_target']));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->eksekusi($req);

        $model->update($id, ['status' => 'APPROVED']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('approval'))->with('error', 'Gagal mengeksekusi permintaan (transaksi dibatalkan).');
        }

        return redirect()->to(base_url('approval'))->with('success', 'Permintaan berhasil disetujui dan dieksekusi!');
    }

    public function tolak($id = null)
    {
        if (! $this->isSuperadmin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak. Hanya Super Admin.');
        }

        $id    = (int) $id;
        $model = new ApprovalModel();
        $req   = $id > 0 ? $model->find($id) : null;

        if (! $req) {
            return redirect()->to(base_url('approval'))->with('error', 'Permintaan tidak ditemukan.');
        }

        if ($req['status'] !== 'PENDING') {
            return redirect()->to(base_url('approval'))->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $model->update($id, ['status' => 'REJECTED']);

        return redirect()->to(base_url('approval'))->with('success', 'Permintaan berhasil ditolak.');
    }

    /**
     * Jalankan aksi yang disetujui.
     *
     * @param array<string, mixed> $req Baris tbl_approval
     */
    private function eksekusi(array $req): void
    {
        $db     = \Config\Database::connect();
        $tabel  = self::TABEL[$req['tabel_target']] ?? null;
        $idUser = (int) $req['id_target'];

        if ($tabel === null || $idUser <= 0) {
            return;
        }

        if ($req['tipe_aksi'] === 'hapus') {
            // Hapus foto bukti (kalau ada) supaya tidak jadi file yatim.
            if ($req['tabel_target'] === 'kerusakan') {
                $baris = $db->table($tabel)->where('id_kerusakan', $idUser)->get()->getRowArray();
                if ($baris !== null) {
                    Berkas::hapus('', $baris['bukti_foto'] ?? null);
                }
            }

            $db->table($tabel)->where($this->primaryKey($req['tabel_target']), $idUser)->delete();

            return;
        }

        if ($req['tipe_aksi'] === 'edit') {
            $dataBaru = json_decode((string) $req['data_baru'], true);
            if (! is_array($dataBaru)) {
                return;
            }

            $kolom   = self::KOLOM[$req['tabel_target']] ?? [];
            $bersih  = array_intersect_key($dataBaru, array_flip($kolom));

            if ($bersih !== []) {
                $db->table($tabel)->where($this->primaryKey($req['tabel_target']), $idUser)->update($bersih);
            }
        }
    }

    private function primaryKey(string $tabel): string
    {
        return $tabel === 'kerusakan' ? 'id_kerusakan' : 'id';
    }
}
