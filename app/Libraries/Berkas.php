<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

/**
 * Penjaga unggahan berkas (foto & dokumen).
 *
 * Semua controller memakai kelas ini supaya:
 *  - hanya ekstensi yang diizinkan yang bisa diunggah,
 *  - isi berkas (MIME) benar-benar sesuai ekstensinya,
 *  - ukuran berkas dibatasi,
 *  - folder tujuan otomatis dibuat.
 */
final class Berkas
{
    public const GAMBAR = ['jpg', 'jpeg', 'png', 'webp'];

    public const DOKUMEN = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'webp', 'zip'];

    /** MIME yang sah untuk tiap ekstensi. */
    private const MIME = [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        'pdf'  => ['application/pdf'],
        'doc'  => ['application/msword', 'application/vnd.ms-office'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
        'xls'  => ['application/vnd.ms-excel', 'application/vnd.ms-office'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
        'ppt'  => ['application/vnd.ms-powerpoint', 'application/vnd.ms-office'],
        'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
        'zip'  => ['application/zip', 'application/x-zip-compressed'],
    ];

    /**
     * Simpan berkas unggahan ke FCPATH/<root>/<folder>.
     *
     * @param UploadedFile|null $file      Berkas dari $this->request->getFile()
     * @param string            $folder    Sub folder di dalam root (mis. "inventaris")
     * @param list<string>      $ekstensi  Ekstensi yang diizinkan
     * @param int               $maksMb    Ukuran maksimal dalam MB
     * @param string            $root      Folder induk relatif terhadap FCPATH
     *
     * @return string|null Nama berkas baru, atau null bila tidak ada berkas diunggah.
     *
     * @throws RuntimeException Bila berkas tidak valid / tidak diizinkan.
     */
    public static function simpan(?UploadedFile $file, string $folder, array $ekstensi, int $maksMb, string $root = 'uploads'): ?string
    {
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $file->isValid()) {
            throw new RuntimeException(self::pesanError($file->getError()));
        }

        if ($file->hasMoved()) {
            throw new RuntimeException('Berkas sudah dipindahkan sebelumnya.');
        }

        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if ($ext === '' || ! in_array($ext, $ekstensi, true)) {
            throw new RuntimeException('Tipe berkas .' . $ext . ' tidak diizinkan. Yang boleh: ' . strtoupper(implode(', ', $ekstensi)) . '.');
        }

        $maksByte = $maksMb * 1024 * 1024;
        if ($file->getSize() > $maksByte) {
            throw new RuntimeException('Ukuran berkas terlalu besar (' . self::formatUkuran($file->getSize()) . '). Maksimal ' . $maksMb . ' MB.');
        }

        $mime    = (string) $file->getMimeType();
        $mimeSah = self::MIME[$ext] ?? [];
        if ($mimeSah !== [] && ! in_array($mime, $mimeSah, true)) {
            throw new RuntimeException('Isi berkas tidak cocok dengan ekstensinya (' . $mime . ').');
        }

        $folder = trim($folder, '/\\');
        $dir    = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . trim($root, '/\\')
                . ($folder !== '' ? DIRECTORY_SEPARATOR . $folder : '');
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            throw new RuntimeException('Folder unggahan tidak bisa dibuat: ' . $root . '/' . $folder);
        }

        $namaBaru = $file->getRandomName();
        $file->move($dir, $namaBaru, true);

        return $namaBaru;
    }

    /** Hapus berkas lama dengan aman (hanya di dalam folder yang dituju). */
    public static function hapus(string $folder, ?string $nama, string $root = 'uploads'): void
    {
        if ($nama === null || $nama === '' || str_contains($nama, '..') || str_contains($nama, '/') || str_contains($nama, '\\')) {
            return;
        }

        $path = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . trim($root, '/\\')
              . DIRECTORY_SEPARATOR . trim($folder, '/\\') . DIRECTORY_SEPARATOR . $nama;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private static function pesanError(int $kode): string
    {
        return match ($kode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran berkas melebihi batas server.',
            UPLOAD_ERR_PARTIAL                        => 'Berkas hanya terunggah sebagian, silakan ulangi.',
            UPLOAD_ERR_NO_TMP_DIR                     => 'Folder sementara server tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE                     => 'Server gagal menulis berkas ke disk.',
            UPLOAD_ERR_EXTENSION                      => 'Unggahan dihentikan oleh ekstensi PHP.',
            default                                   => 'Berkas gagal diunggah.',
        };
    }

    private static function formatUkuran(int $byte): string
    {
        if ($byte >= 1048576) {
            return number_format($byte / 1048576, 1, ',', '.') . ' MB';
        }
        if ($byte >= 1024) {
            return number_format($byte / 1024, 1, ',', '.') . ' KB';
        }

        return $byte . ' B';
    }
}
