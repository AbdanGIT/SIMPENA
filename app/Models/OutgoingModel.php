<?php
namespace App\Models;
use CodeIgniter\Model;

class OutgoingModel extends Model
{
    protected $table      = 'tbl_outgoing';
    protected $primaryKey = 'id_outgoing';
    protected $allowedFields = [
        'part_number', 'description', 'qty_keluar', 'tanggal', 'distribusi', 'status'
    ];
    protected $useTimestamps = false;
}