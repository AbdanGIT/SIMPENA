<?php
namespace App\Models;
use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table      = 'tbl_pengadaan';
    protected $primaryKey = 'id_pengadaan';
    protected $allowedFields = [
        'part_number', 'description', 'stock_awal', 'incoming', 
        'outgoing', 'stock_actual', 'status', 'dokumentasi'
    ];
}