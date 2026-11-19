<?php

namespace App\Models;

use CodeIgniter\Model;

class ArsipModel extends Model
{
    protected $table      = 'tbl_arsip';
    protected $primaryKey = 'id_arsip';
    
    protected $allowedFields = [
        'nama_dokumen', 
        'kategori', 
        'deskripsi', 
        'file_dokumen', 
        'tanggal_upload'
    ];

    protected $useTimestamps = false;
}