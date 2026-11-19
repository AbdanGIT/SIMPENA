<?php

namespace App\Controllers;

use App\Models\MutasiModel;

class RiwayatMutasi extends BaseController
{
    public function index()
    {
        if (! session()->get('masuk')) {
            return redirect()->to(base_url('login'));
        }

        $model = new MutasiModel();
        
        $data = [
            'title'   => 'Riwayat Mutasi | SIMPENA',
            'riwayat' => $model->orderBy('tanggal_mutasi', 'DESC')->findAll()
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('riwayat_mutasi/index', $data)
             . view('layout/footer', $data);
    }
}