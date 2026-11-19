<?php
namespace App\Models;
use CodeIgniter\Model;

class AntrianModel extends Model
{
    protected $table      = 'tbl_antrian_sparepart';
    protected $primaryKey = 'id_antrian';
    protected $allowedFields = [
        'nama_barang', 'ruangan', 'kerusakan', 'sparepart_dibutuhkan', 
        'status', 'tanggal'
    ];
    protected $useTimestamps = false;
}