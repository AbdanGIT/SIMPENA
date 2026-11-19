<?php
namespace App\Models;
use CodeIgniter\Model;

class IncomingModel extends Model
{
    protected $table      = 'tbl_incoming';
    protected $primaryKey = 'id_incoming';
    protected $allowedFields = [
        'part_number', 'description', 'qty_masuk', 'tanggal', 'status'
    ];
    protected $useTimestamps = false;
}