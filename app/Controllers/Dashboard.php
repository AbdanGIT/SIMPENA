<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'title' => 'Dashboard | SIMPENA',
            'stats' => $this->statistik($db),
            'grafik' => $this->grafikService($db, 8),
            'aktivitas' => $this->aktivitasTerbaru($db),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('dashboard/index', $data)
             . view('layout/footer', $data);
    }

    /**
     * Statistik utama dashboard (memakai view v_dashboard_stats bila tersedia).
     *
     * @return array<string, int>
     */
    private function statistik(BaseConnection $db): array
    {
        $default = [
            'sedang_service'        => 0,
            'selesai_service'       => 0,
            'rusak'                 => 0,
            'penghapusan_aset'      => 0,
            'total_service_report'  => 0,
            'total_arsip'           => 0,
            'total_part_number'     => 0,
            'total_inventaris'      => 0,
            'antrian_sparepart'     => 0,
        ];

        try {
            $baris = $db->table('v_dashboard_stats')->get()->getRowArray();
        } catch (\Throwable $e) {
            $baris = null;
        }

        if (is_array($baris)) {
            foreach ($baris as $kunci => $nilai) {
                $default[$kunci] = (int) $nilai;
            }
        }

        // Angka tambahan yang tidak ada di view.
        $default['total_inventaris']  = $db->table('tbl_pencatatan_komputer')->countAllResults();
        $default['antrian_sparepart'] = $db->table('tbl_antrian_sparepart')
            ->whereIn('status', ['MENUNGGU', 'DIPROSES'])
            ->countAllResults();

        return $default;
    }

    /**
     * Jumlah service report & kerusakan per hari untuk N hari terakhir.
     *
     * @return array{labels: list<string>, service: list<int>, kerusakan: list<int>}
     */
    private function grafikService(BaseConnection $db, int $jumlahHari): array
    {
        $tanggalMulai = date('Y-m-d', strtotime('-' . ($jumlahHari - 1) . ' days'));

        try {
            $service = $db->table('tbl_service_report')
                ->select('tanggal, COUNT(*) AS jumlah')
                ->where('tanggal >=', $tanggalMulai)
                ->where('tanggal <=', date('Y-m-d'))
                ->groupBy('tanggal')
                ->get()->getResultArray();

            $kerusakan = $db->table('tbl_kerusakan')
                ->select('tgl_service AS tanggal, COUNT(*) AS jumlah')
                ->where('tgl_service >=', $tanggalMulai)
                ->where('tgl_service <=', date('Y-m-d'))
                ->groupBy('tgl_service')
                ->get()->getResultArray();
        } catch (\Throwable $e) {
            $service   = [];
            $kerusakan = [];
        }

        $petaService   = array_column($service, 'jumlah', 'tanggal');
        $petaKerusakan = array_column($kerusakan, 'jumlah', 'tanggal');

        $labels    = [];
        $dataServ  = [];
        $dataRusak = [];

        for ($i = $jumlahHari - 1; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime('-' . $i . ' days'));

            $labels[]    = date('d/m', strtotime($tgl));
            $dataServ[]  = (int) ($petaService[$tgl] ?? 0);
            $dataRusak[] = (int) ($petaKerusakan[$tgl] ?? 0);
        }

        return [
            'labels'    => $labels,
            'service'   => $dataServ,
            'kerusakan' => $dataRusak,
        ];
    }

    /**
     * Aktivitas terbaru (service report + kerusakan) untuk panel kanan.
     *
     * @return list<array{tanggal: string, judul: string, ket: string, jenis: string}>
     */
    private function aktivitasTerbaru(BaseConnection $db): array
    {
        $hasil = [];

        try {
            $reports = $db->table('tbl_service_report')
                ->select('no_report, nama_prasarana, tanggal')
                ->orderBy('id_report', 'DESC')
                ->limit(4)
                ->get()->getResultArray();

            foreach ($reports as $r) {
                $hasil[] = [
                    'tanggal' => (string) ($r['tanggal'] ?? ''),
                    'judul'   => (string) ($r['nama_prasarana'] ?? '-'),
                    'ket'     => 'Service report ' . ($r['no_report'] ?? '-'),
                    'jenis'   => 'service',
                ];
            }

            $rusak = $db->table('tbl_kerusakan')
                ->select('nama_barang, status, tgl_service')
                ->orderBy('id_kerusakan', 'DESC')
                ->limit(4)
                ->get()->getResultArray();

            foreach ($rusak as $r) {
                $hasil[] = [
                    'tanggal' => (string) ($r['tgl_service'] ?? ''),
                    'judul'   => (string) ($r['nama_barang'] ?? '-'),
                    'ket'     => 'Kerusakan: ' . ($r['status'] ?? '-'),
                    'jenis'   => 'kerusakan',
                ];
            }
        } catch (\Throwable $e) {
            return [];
        }

        usort($hasil, static fn ($a, $b) => strcmp($b['tanggal'], $a['tanggal']));

        return array_slice($hasil, 0, 5);
    }
}
