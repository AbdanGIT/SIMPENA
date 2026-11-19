<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiModel extends Model
{
    protected $table      = 'tbl_riwayat_mutasi';
    protected $primaryKey = 'id_mutasi';
    
    protected $allowedFields = [
        'id_komputer', 
        'nama_barang', 
        'ruangan_asal', 
        'ruangan_tujuan', 
        'keterangan', 
        'foto',
        'tanggal_mutasi'
    ];

    protected $useTimestamps = false;
}