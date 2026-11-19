<?php

namespace App\Controllers;

use App\Models\ZebraLabelModel;

class ZebraLabel extends BaseController
{
    private const UKURAN = ['50x25', '100x50'];

    protected ZebraLabelModel $zebraModel;

    public function __construct()
    {
        $this->zebraModel = new ZebraLabelModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Desain Label Zebra | SIMPENA',
            'labels' => $this->zebraModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('zebra/index', $data)
             . view('layout/footer', $data);
    }

    public function save()
    {
        $namaDesain = trim((string) $this->request->getPost('nama_desain'));
        $zpl        = (string) $this->request->getPost('zpl_code');
        $ukuran     = (string) $this->request->getPost('ukuran_kertas');

        if ($namaDesain === '') {
            return redirect()->to(base_url('zebra_label'))->with('error', 'Nama desain wajib diisi.');
        }

        if (trim($zpl) === '') {
            return redirect()->to(base_url('zebra_label'))->with('error', 'Kode ZPL kosong. Isi minimal Teks Baris 1 atau Baris 2.');
        }

        $this->zebraModel->save([
            'nama_desain'   => $namaDesain,
            'ukuran_kertas' => in_array($ukuran, self::UKURAN, true) ? $ukuran : '50x25',
            'zpl_code'      => $zpl,
        ]);

        return redirect()->to(base_url('zebra_label'))->with('success', 'Desain berhasil disimpan!');
    }

    public function delete($id = null)
    {
        $id = (int) $id;

        if ($id > 0 && $this->zebraModel->find($id)) {
            $this->zebraModel->delete($id);

            return redirect()->to(base_url('zebra_label'))->with('success', 'Desain berhasil dihapus!');
        }

        return redirect()->to(base_url('zebra_label'))->with('error', 'Desain tidak ditemukan.');
    }
}
