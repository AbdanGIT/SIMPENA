<?php

namespace App\Models;

use CodeIgniter\Model;

class KerusakanModel extends Model
{
    protected $table      = 'tbl_kerusakan';
    protected $primaryKey = 'id_kerusakan';

    protected $allowedFields = [
        'no_barang',
        'nama_barang',
        'merek',
        'ruangan',
        'tempat_service',
        'tgl_ambil',
        'tgl_service',
        'kelengkapan',
        'masalah',
        'keterangan',
        'status',
        'bukti_foto',
    ];
}