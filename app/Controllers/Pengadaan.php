<?php

namespace App\Controllers;

use App\Libraries\Berkas;
use App\Models\IncomingModel;
use App\Models\OutgoingModel;
use App\Models\StokModel;
use CodeIgniter\Database\BaseConnection;
use RuntimeException;

class Pengadaan extends BaseController
{
    /** Dokumentasi pengadaan disimpan di public/uploads. */
    private const FOLDER_DOK = '';

    private const MAKS_DOK_MB = 10;

    protected StokModel $mStok;
    protected IncomingModel $mIncoming;
    protected OutgoingModel $mOutgoing;
    protected BaseConnection $db;

    public function __construct()
    {
        $this->mStok     = new StokModel();
        $this->mIncoming = new IncomingModel();
        $this->mOutgoing = new OutgoingModel();
        $this->db        = \Config\Database::connect();
    }

    public function index()
    {
        $listPart = $this->mStok->select('part_number, description')->groupBy('part_number')->orderBy('part_number', 'ASC')->findAll();

        $data = [
            'title'     => 'Pengadaan Barang PDE | SIMPENA',
            'pengadaan' => $this->mStok->orderBy('id_pengadaan', 'DESC')->findAll(),
            'incoming'  => $this->mIncoming->orderBy('id_incoming', 'DESC')->findAll(),
            'outgoing'  => $this->mOutgoing->orderBy('id_outgoing', 'DESC')->findAll(),
            'list_part' => $listPart,
        ];

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view('pengadaan/index', $data)
             . view('layout/footer', $data);
    }

    public function simpan()
    {
        $partNumber = trim((string) $this->request->getPost('addPart'));

        if ($partNumber === '') {
            return $this->json(false, 'Part number wajib diisi.', [], 422);
        }

        if ($this->mStok->where('part_number', $partNumber)->countAllResults() > 0) {
            return $this->json(false, 'Part number ' . $partNumber . ' sudah terdaftar.', [], 409);
        }

        try {
            $namaFile = Berkas::simpan(
                $this->request->getFile('addDokumentasi'),
                self::FOLDER_DOK,
                Berkas::DOKUMEN,
                self::MAKS_DOK_MB
            );

            $awal     = max(0, (int) $this->request->getPost('addAwal'));
            $incoming = max(0, (int) $this->request->getPost('addIncoming'));
            $outgoing = max(0, (int) $this->request->getPost('addOutgoing'));

            // stock_actual SELALU dihitung, tidak dipercaya dari input manual.
            $this->mStok->save([
                'part_number'  => $partNumber,
                'description'  => trim((string) $this->request->getPost('addDesc')),
                'stock_awal'   => $awal,
                'incoming'     => $incoming,
                'outgoing'     => $outgoing,
                'stock_actual' => $awal + $incoming - $outgoing,
                'status'       => $this->request->getPost('addStatus') ?: 'PENDING',
                'dokumentasi'  => $namaFile,
            ]);

            return $this->json(true, 'Data berhasil disimpan!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Pengadaan::simpan - ' . $e->getMessage());

            return $this->json(false, 'Gagal menyimpan data: ' . $e->getMessage(), [], 500);
        }
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id_pengadaan');

        if ($id <= 0) {
            return $this->json(false, 'ID pengadaan tidak valid.', [], 422);
        }

        $lama = $this->mStok->find($id);
        if (! $lama) {
            return $this->json(false, 'Data pengadaan tidak ditemukan.', [], 404);
        }

        $partNumber = trim((string) $this->request->getPost('editPart'));
        if ($partNumber === '') {
            return $this->json(false, 'Part number wajib diisi.', [], 422);
        }

        $duplikat = $this->mStok->where('part_number', $partNumber)->where('id_pengadaan !=', $id)->countAllResults();
        if ($duplikat > 0) {
            return $this->json(false, 'Part number ' . $partNumber . ' sudah dipakai baris lain.', [], 409);
        }

        try {
            $namaFile = $lama['dokumentasi'] ?? null;

            $fileDok = $this->request->getFile('editDokumentasi');
            if ($fileDok !== null && $fileDok->getError() !== UPLOAD_ERR_NO_FILE) {
                $namaBaru = Berkas::simpan($fileDok, self::FOLDER_DOK, Berkas::DOKUMEN, self::MAKS_DOK_MB);
                if ($namaBaru !== null) {
                    Berkas::hapus(self::FOLDER_DOK, $namaFile);
                    $namaFile = $namaBaru;
                }
            }

            $awal     = max(0, (int) $this->request->getPost('editAwal'));
            $incoming = max(0, (int) $this->request->getPost('editIncoming'));
            $outgoing = max(0, (int) $this->request->getPost('editOutgoing'));

            $this->mStok->update($id, [
                'part_number'  => $partNumber,
                'description'  => trim((string) $this->request->getPost('editDesc')),
                'stock_awal'   => $awal,
                'incoming'     => $incoming,
                'outgoing'     => $outgoing,
                'stock_actual' => $awal + $incoming - $outgoing,
                'status'       => $this->request->getPost('editStatus') ?: 'PENDING',
                'dokumentasi'  => $namaFile,
            ]);

            // Jika part number berubah, stok kedua part perlu dihitung ulang.
            $this->recalcStok((string) ($lama['part_number'] ?? ''), $partNumber);

            return $this->json(true, 'Data berhasil diupdate!');
        } catch (RuntimeException $e) {
            return $this->json(false, $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Pengadaan::update - ' . $e->getMessage());

            return $this->json(false, 'Gagal mengupdate data: ' . $e->getMessage(), [], 500);
        }
    }

    public function hapus($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID pengadaan tidak valid.', [], 422);
        }

        $data = $this->mStok->find($id);
        if (! $data) {
            return $this->json(false, 'Data tidak ditemukan.', [], 404);
        }

        $partNumber = (string) $data['part_number'];

        $this->db->transStart();
        $this->mIncoming->where('part_number', $partNumber)->delete();
        $this->mOutgoing->where('part_number', $partNumber)->delete();
        $this->mStok->delete($id);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal menghapus data (transaksi dibatalkan).', [], 500);
        }

        Berkas::hapus(self::FOLDER_DOK, $data['dokumentasi'] ?? null);

        return $this->json(true, 'Data berhasil dihapus!');
    }

    public function simpan_incoming()
    {
        $partNumber = trim((string) $this->request->getPost('addIncPart'));
        $qtyMasuk   = (int) $this->request->getPost('addIncQty');

        if ($partNumber === '' || $this->mStok->where('part_number', $partNumber)->countAllResults() === 0) {
            return $this->json(false, 'Part number tidak terdaftar di data pengadaan.', [], 422);
        }

        if ($qtyMasuk <= 0) {
            return $this->json(false, 'Qty masuk harus lebih dari 0.', [], 422);
        }

        $this->db->transStart();

        $this->mIncoming->save([
            'part_number' => $partNumber,
            'description' => trim((string) $this->request->getPost('addIncDesc')),
            'qty_masuk'   => $qtyMasuk,
            'tanggal'     => $this->tanggalAtauNull($this->request->getPost('addIncTgl')),
            'status'      => $this->request->getPost('addIncStatus') ?: 'PENDING',
        ]);

        $this->recalcStok($partNumber);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal menyimpan incoming (transaksi dibatalkan).', [], 500);
        }

        return $this->json(true, 'Incoming disimpan & stok bertambah!');
    }


    public function update_incoming()
    {
        $id     = (int) $this->request->getPost('editIncId');
        $qtyBaru = (int) $this->request->getPost('editIncQty');
        $partBaru = trim((string) $this->request->getPost('editIncPart'));

        if ($id <= 0) {
            return $this->json(false, 'ID incoming tidak valid.', [], 422);
        }

        $lama = $this->mIncoming->find($id);
        if (! $lama) {
            return $this->json(false, 'Data incoming tidak ditemukan.', [], 404);
        }

        if ($partBaru === '' || $this->mStok->where('part_number', $partBaru)->countAllResults() === 0) {
            return $this->json(false, 'Part number tidak terdaftar di data pengadaan.', [], 422);
        }

        if ($qtyBaru <= 0) {
            return $this->json(false, 'Qty masuk harus lebih dari 0.', [], 422);
        }

        $this->db->transStart();

        $this->mIncoming->update($id, [
            'part_number' => $partBaru,
            'description' => trim((string) $this->request->getPost('editIncDesc')),
            'qty_masuk'   => $qtyBaru,
            'tanggal'     => $this->tanggalAtauNull($this->request->getPost('editIncTgl')),
            'status'      => $this->request->getPost('editIncStatus') ?: 'PENDING',
        ]);

        $this->recalcStok((string) $lama['part_number'], $partBaru);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal mengupdate incoming (transaksi dibatalkan).', [], 500);
        }

        return $this->json(true, 'Incoming berhasil diupdate & stok disesuaikan.');
    }

    public function hapus_incoming($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID incoming tidak valid.', [], 422);
        }

        $incoming = $this->mIncoming->find($id);
        if (! $incoming) {
            return $this->json(false, 'Data incoming tidak ditemukan.', [], 404);
        }

        $this->db->transStart();
        $this->mIncoming->delete($id);
        $this->recalcStok((string) $incoming['part_number']);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal menghapus incoming (transaksi dibatalkan).', [], 500);
        }

        return $this->json(true, 'Incoming berhasil dihapus & stok disesuaikan.');
    }

    public function simpan_outgoing()
    {
        $partNumber = trim((string) $this->request->getPost('addOutPart'));
        $qtyKeluar  = (int) $this->request->getPost('addOutQty');

        $stok = $partNumber !== '' ? $this->mStok->where('part_number', $partNumber)->first() : null;
        if (! $stok) {
            return $this->json(false, 'Part number tidak terdaftar di data pengadaan.', [], 422);
        }

        if ($qtyKeluar <= 0) {
            return $this->json(false, 'Qty keluar harus lebih dari 0.', [], 422);
        }

        $tersedia = $this->stokTersedia($partNumber);
        if ($qtyKeluar > $tersedia) {
            return $this->json(false, 'Stok tidak cukup. Tersedia ' . $tersedia . ', diminta ' . $qtyKeluar . '.', [], 422);
        }

        $this->db->transStart();

        $this->mOutgoing->save([
            'part_number' => $partNumber,
            'description' => trim((string) $this->request->getPost('addOutDesc')),
            'qty_keluar'  => $qtyKeluar,
            'tanggal'     => $this->tanggalAtauNull($this->request->getPost('addOutTgl')),
            'distribusi'  => trim((string) $this->request->getPost('addOutDist')),
            'status'      => $this->request->getPost('addOutStatus') ?: 'PENDING',
        ]);

        $this->recalcStok($partNumber);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal menyimpan outgoing (transaksi dibatalkan).', [], 500);
        }

        return $this->json(true, 'Outgoing disimpan & stok berkurang!');
    }


    public function update_outgoing()
    {
        $id       = (int) $this->request->getPost('editOutId');
        $qtyBaru  = (int) $this->request->getPost('editOutQty');
        $partBaru = trim((string) $this->request->getPost('editOutPart'));

        if ($id <= 0) {
            return $this->json(false, 'ID outgoing tidak valid.', [], 422);
        }

        $lama = $this->mOutgoing->find($id);
        if (! $lama) {
            return $this->json(false, 'Data outgoing tidak ditemukan.', [], 404);
        }

        if ($partBaru === '' || $this->mStok->where('part_number', $partBaru)->countAllResults() === 0) {
            return $this->json(false, 'Part number tidak terdaftar di data pengadaan.', [], 422);
        }

        if ($qtyBaru <= 0) {
            return $this->json(false, 'Qty keluar harus lebih dari 0.', [], 422);
        }

        $partLama = (string) $lama['part_number'];
        $qtyLama  = (int) $lama['qty_keluar'];

        // Stok yang boleh dipakai: stok tersedia + qty lama (karena baris ini akan diganti).
        $tersedia = $this->stokTersedia($partBaru) + ($partBaru === $partLama ? $qtyLama : 0);
        if ($qtyBaru > $tersedia) {
            return $this->json(false, 'Stok tidak cukup. Tersedia ' . $tersedia . ', diminta ' . $qtyBaru . '.', [], 422);
        }

        $this->db->transStart();

        $this->mOutgoing->update($id, [
            'part_number' => $partBaru,
            'description' => trim((string) $this->request->getPost('editOutDesc')),
            'qty_keluar'  => $qtyBaru,
            'tanggal'     => $this->tanggalAtauNull($this->request->getPost('editOutTgl')),
            'distribusi'  => trim((string) $this->request->getPost('editOutDist')),
            'status'      => $this->request->getPost('editOutStatus') ?: 'PENDING',
        ]);

        $this->recalcStok($partLama, $partBaru);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal mengupdate outgoing (transaksi dibatalkan).', [], 500);
        }

        return $this->json(true, 'Outgoing berhasil diupdate & stok disesuaikan.');
    }

    public function hapus_outgoing($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json(false, 'ID outgoing tidak valid.', [], 422);
        }

        $outgoing = $this->mOutgoing->find($id);
        if (! $outgoing) {
            return $this->json(false, 'Data outgoing tidak ditemukan.', [], 404);
        }

        $this->db->transStart();
        $this->mOutgoing->delete($id);
        $this->recalcStok((string) $outgoing['part_number']);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->json(false, 'Gagal menghapus outgoing (transaksi dibatalkan).', [], 500);
        }

        return $this->json(true, 'Outgoing berhasil dihapus & stok disesuaikan.');
    }

    /**
     * Hitung ulang stok satu/beberapa part number dari data incoming & outgoing.
     *
     * stock_actual = stock_awal + SUM(qty_masuk) - SUM(qty_keluar)
     */
    private function recalcStok(string ...$partNumbers): void
    {
        foreach (array_unique(array_filter($partNumbers)) as $part) {
            $stok = $this->mStok->where('part_number', $part)->first();
            if (! $stok) {
                continue;
            }

            $masuk  = $this->totalMasuk($part);
            $keluar = $this->totalKeluar($part);
            $awal   = (int) $stok['stock_awal'];

            $this->mStok->update($stok['id_pengadaan'], [
                'incoming'     => $masuk,
                'outgoing'     => $keluar,
                'stock_actual' => $awal + $masuk - $keluar,
            ]);
        }
    }

    /** Stok yang masih bisa dikeluarkan untuk satu part number. */
    private function stokTersedia(string $partNumber): int
    {
        $stok = $this->mStok->where('part_number', $partNumber)->first();
        if (! $stok) {
            return 0;
        }

        return (int) $stok['stock_awal'] + $this->totalMasuk($partNumber) - $this->totalKeluar($partNumber);
    }

    private function totalMasuk(string $partNumber): int
    {
        $row = $this->mIncoming
            ->selectSum('qty_masuk', 'total')
            ->where('part_number', $partNumber)
            ->first();

        return (int) ($row['total'] ?? 0);
    }

    private function totalKeluar(string $partNumber): int
    {
        $row = $this->mOutgoing
            ->selectSum('qty_keluar', 'total')
            ->where('part_number', $partNumber)
            ->first();

        return (int) ($row['total'] ?? 0);
    }

    /** Ubah tanggal kosong menjadi NULL supaya tidak error di kolom DATE. */
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

