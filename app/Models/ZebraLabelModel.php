<?php

namespace App\Models;

use CodeIgniter\Model;

class ZebraLabelModel extends Model
{
    protected $table            = 'zebra_labels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_desain', 'ukuran_kertas', 'zpl_code'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}