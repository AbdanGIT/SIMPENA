<?php
namespace App\Models;
use CodeIgniter\Model;

class ApprovalModel extends Model
{
    protected $table      = 'tbl_approval';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tipe_aksi', 'tabel_target', 'id_target', 'data_baru', 'diajukan_oleh', 'status'];
    protected $useTimestamps = false;
}