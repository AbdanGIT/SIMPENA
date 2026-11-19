<?php

namespace App\Controllers;

use App\Models\AntrianModel;

class AntrianSparepart extends BaseController
{
    /** Status yang diizinkan. */
    private const STATUS = ['MENUNGGU', 'DIPROSES', 'SELESAI', 'DIBATALKAN'];

    protected AntrianModel $mAntrian;

    public function __construct()
    {
        $this->mAntrian = new AntrianModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Antrian Sparepart | SIMPENA',
            'antrian' => $this->mAntrian->orderBy('tanggal', 'DESC')->findAll(),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('antrian_sparepart/index', $data)
             . view('layout/footer', $data);
    }

    public function simpan()
    {
        if (! $this->validate([
            'nama_barang'          => 'required|max_length[150]',
            'ruangan'              => 'required|max_length[100]',
            'kerusakan'            => 'required|max_length[255]',
            'sparepart_dibutuhkan' => 'required|max_length[150]',
        ])) {
            return $this->json(false, implode(' ', $this->validator->getErrors()), [], 422);
        }

        $this->mAntrian->save([
            'nama_barang'          => trim((string) $this->request->getPost('nama_barang')),
            'ruangan'              => trim((string) $this->request->getPost('ruangan')),
            'kerusakan'            => trim((string) $this->request->getPost('kerusakan')),
            'sparepart_dibutuhkan' => trim((string) $this->request->getPost('sparepart_dibutuhkan')),
            'status'               => 'MENUNGGU',
            'tanggal'              => date('Y-m-d H:i:s'),
        ]);

        return $this->json(true, 'Antrian berhasil disimpan!');
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id_antrian');

        if ($id <= 0) {
            return $this->json(false, 'ID antrian tidak valid.', [], 422);
        }

        if (! $this->mAntrian->find($id)) {
            return $this->json(false, 'Data antrian tidak ditemukan.', [], 404);
        }

        $status = (string) $this->request->getPost('status');

        $this->mAntrian->update($id, [
            'nama_barang'          => trim((string) $this->request->getPost('nama_barang')),
            'ruangan'              => trim((string) $this->request->getPost('ruangan')),
            'kerusakan'            => trim((string) $this->request->getPost('kerusakan')),
            'sparepart_dibutuhkan' => trim((string) $this->request->getPost('sparepart_dibutuhkan')),
            'status'               => in_array($status, self::STATUS, true) ? $status : 'MENUNGGU',
        ]);

        return $this->json(true, 'Antrian berhasil diupdate!');
    }

    public function hapus($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID antrian tidak valid.', [], 422);
        }

        if (! $this->mAntrian->find($id)) {
            return $this->json(false, 'Data antrian tidak ditemukan.', [], 404);
        }

        $this->mAntrian->delete($id);

        return $this->json(true, 'Antrian berhasil dihapus!');
    }
}
