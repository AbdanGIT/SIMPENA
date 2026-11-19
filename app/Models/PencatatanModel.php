<?php

namespace App\Models;

use CodeIgniter\Model;

class PencatatanModel extends Model
{
    protected $table      = 'tbl_pencatatan_komputer';
    protected $primaryKey = 'id_komputer';
    
    protected $allowedFields = [
        'kategori', 
        'nama_barang', 
        'merek', 
        'seri_tipe', 
        'status', 
        'status_fix', 
        'keterangan', 
        'foto'
    ];

    // Karena di database sudah ada DEFAULT CURRENT_TIMESTAMP
    protected $useTimestamps = false;
}