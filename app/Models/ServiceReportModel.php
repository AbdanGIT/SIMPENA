<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceReportModel extends Model
{
    protected $table      = 'tbl_service_report';
    protected $primaryKey = 'id_report';
    
    protected $allowedFields = [
        'no_report', 'tanggal', 'nama_prasarana', 'merek', 'tipe_model', 'no_seri', 
        'nama_ruangan', 'tahun', 'service_type', 'pemeriksaan', 'permasalahan', 
        'keterangan', 'hasil_pemeriksaan', 'ttd_kiri', 'ttd_tengah', 'ttd_kanan'
    ];

    protected $useTimestamps = false;
}