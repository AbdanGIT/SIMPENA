<?php

namespace App\Controllers;

use App\Models\ServiceReportModel;

class ServiceReport extends BaseController
{
    private const BULAN_ROMAWI = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    protected ServiceReportModel $mServiceReport;

    public function __construct()
    {
        $this->mServiceReport = new ServiceReportModel();
    }

    /**
     * Nomor report otomatis dengan format: 001/SR-XI/2026
     * Urutan dihitung per bulan + tahun berjalan (tidak dobel).
     */
    private function generateNoReport(): string
    {
        $tahun       = date('Y');
        $bulanRomawi = self::BULAN_ROMAWI[(int) date('n') - 1];

        $baris = $this->mServiceReport
            ->select("MAX(CAST(SUBSTRING_INDEX(no_report, '/', 1) AS UNSIGNED)) AS urut", false)
            ->like('no_report', '/SR-' . $bulanRomawi . '/' . $tahun, 'before')
            ->first();

        $urutan = ((int) ($baris['urut'] ?? 0)) + 1;

        // Pastikan nomor benar-benar belum dipakai.
        do {
            $nomor = sprintf('%03d', $urutan) . '/SR-' . $bulanRomawi . '/' . $tahun;
            $dipakai = $this->mServiceReport->where('no_report', $nomor)->countAllResults();
            $urutan++;
        } while ($dipakai > 0);

        return $nomor;
    }

    public function index()
    {
        $data = [
            'title'              => 'Service Report | SIMPENA',
            'reports'            => $this->mServiceReport->orderBy('id_report', 'DESC')->findAll(),
            'no_report_otomatis' => $this->generateNoReport(),
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('service_report/index', $data)
             . view('layout/footer', $data);
    }

    public function simpan()
    {
        $data = $this->request->getPost();

        if (trim((string) ($data['nama_prasarana'] ?? '')) === '') {
            return $this->json(false, 'Nama prasarana wajib diisi.', [], 422);
        }

        $data['service_type']      = $this->gabung($data['service_type'] ?? null);
        $data['pemeriksaan']       = $this->gabung($data['pemeriksaan'] ?? null);
        $data['hasil_pemeriksaan'] = $this->gabung($data['hasil_pemeriksaan'] ?? null);
        $data['tanggal']           = $this->tanggalAtauNull($data['tanggal'] ?? null) ?? date('Y-m-d');

        // Nomor report: pakai input bila diisi, kalau kosong buat otomatis.
        $noReport = trim((string) ($data['no_report'] ?? ''));
        if ($noReport === '') {
            $noReport = $this->generateNoReport();
        } elseif ($this->mServiceReport->where('no_report', $noReport)->countAllResults() > 0) {
            return $this->json(false, 'Nomor report ' . $noReport . ' sudah dipakai.', [], 409);
        }
        $data['no_report'] = $noReport;

        try {
            $this->mServiceReport->save($data);

            return $this->json(true, 'Service Report berhasil disimpan!', ['no_report' => $noReport]);
        } catch (\Throwable $e) {
            log_message('error', 'ServiceReport::simpan - ' . $e->getMessage());

            return $this->json(false, 'Gagal menyimpan: ' . $e->getMessage(), [], 500);
        }
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id_report');

        if ($id <= 0) {
            return $this->json(false, 'ID report tidak valid.', [], 422);
        }

        $lama = $this->mServiceReport->find($id);
        if (! $lama) {
            return $this->json(false, 'Service report tidak ditemukan.', [], 404);
        }

        $data = $this->request->getPost();

        if (trim((string) ($data['nama_prasarana'] ?? '')) === '') {
            return $this->json(false, 'Nama prasarana wajib diisi.', [], 422);
        }

        $data['service_type']      = $this->gabung($data['service_type'] ?? null);
        $data['pemeriksaan']       = $this->gabung($data['pemeriksaan'] ?? null);
        $data['hasil_pemeriksaan'] = $this->gabung($data['hasil_pemeriksaan'] ?? null);
        $data['tanggal']           = $this->tanggalAtauNull($data['tanggal'] ?? null) ?? (string) ($lama['tanggal'] ?? date('Y-m-d'));

        $noReport = trim((string) ($data['no_report'] ?? ''));
        if ($noReport === '') {
            $noReport = (string) ($lama['no_report'] ?? $this->generateNoReport());
        }
        $data['no_report'] = $noReport;

        try {
            $this->mServiceReport->update($id, $data);

            return $this->json(true, 'Service Report berhasil diupdate!');
        } catch (\Throwable $e) {
            log_message('error', 'ServiceReport::update - ' . $e->getMessage());

            return $this->json(false, 'Gagal mengupdate: ' . $e->getMessage(), [], 500);
        }
    }

    public function hapus($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID report tidak valid.', [], 422);
        }

        if (! $this->mServiceReport->find($id)) {
            return $this->json(false, 'Service report tidak ditemukan.', [], 404);
        }

        $this->mServiceReport->delete($id);

        return $this->json(true, 'Service Report berhasil dihapus!');
    }

    public function print($id = null)
    {
        $id     = (int) $id;
        $report = $id > 0 ? $this->mServiceReport->find($id) : null;

        if (! $report) {
            return redirect()->to(base_url('service_report'))->with('error', 'Service report tidak ditemukan.');
        }

        return view('service_report/print', ['report' => $report]);
    }

    /** Gabungkan checkbox array menjadi string dipisah koma. */
    private function gabung($nilai): string
    {
        if (is_array($nilai)) {
            $nilai = array_filter(array_map('trim', $nilai), static fn ($v) => $v !== '');

            return implode(', ', $nilai);
        }

        return trim((string) $nilai);
    }

    /** Ubah tanggal kosong menjadi NULL (kolom DATE tidak menerima string kosong). */
    private function tanggalAtauNull($nilai): ?string
    {
        $nilai = trim((string) $nilai);

        if ($nilai === '') {
            return null;
        }

        $ts = strtotime($nilai);

        return $ts === false ? null : date('Y-m-d', $ts);
    }
}
