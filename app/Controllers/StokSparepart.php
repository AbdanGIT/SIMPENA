<?php

namespace App\Controllers;

use App\Models\StokModel;

class StokSparepart extends BaseController
{
    public function index()
    {
        if (! session()->get('masuk')) {
            return redirect()->to(base_url('login'));
        }

        $mStok = new StokModel();
        // Ambil data dengan stok > 0
        $sparepart = $mStok->where('stock_actual >', 0)->orderBy('part_number', 'ASC')->findAll();

        $data = [
            'title'     => 'Stok Sparepart | SIMSS',
            'sparepart' => $sparepart
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('stok_sparepart/index', $data)
             . view('layout/footer', $data);
    }
}