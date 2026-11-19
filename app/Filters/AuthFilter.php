<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Memastikan semua halaman/aksi hanya bisa diakses setelah login.
 *
 * Sebelumnya setiap controller memeriksa session sendiri-sendiri sehingga
 * banyak endpoint tulis (simpan/update/hapus) yang bisa dipanggil tanpa login.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $masuk = session()->get('masuk');

        if ($masuk) {
            return null;
        }

        // Permintaan AJAX: balas JSON 401 supaya JavaScript bisa menanganinya.
        if ($request instanceof IncomingRequest && $request->isAJAX()) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Sesi Anda sudah berakhir. Silakan login kembali.',
                ]);
        }

        return redirect()
            ->to(base_url('login'))
            ->with('msg', '<div class="alert alert-danger">Silakan login terlebih dahulu.</div>');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
