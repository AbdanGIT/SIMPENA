<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @param CLIRequest|IncomingRequest $request
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
        /**
     * Cek apakah user sudah login.
     * Kalau belum, redirect ke login.
     */
    protected function requireLogin()
    {
        if (! session()->get('masuk')) {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    /**
     * Cek apakah user punya level tertentu.
     * Contoh: requireLevel('1') untuk admin.
     */
    protected function requireLevel(string $level)
    {
        if (session()->get('akses') !== $level) {
            return redirect()->to(base_url('dashboard'))->with('msg', 'Akses ditolak.');
        }
        return null;
    }

    /**
     * Apakah user yang login adalah super admin (level 1)?
     */
    protected function isSuperadmin(): bool
    {
        return session()->get('role') === 'superadmin';
    }

    /**
     * Apakah user yang login adalah admin biasa (level 2)?
     */
    protected function isAdmin(): bool
    {
        return session()->get('role') === 'admin';
    }

    /**
     * Kirim balasan JSON standar untuk request AJAX.
     */
    protected function json(bool $sukses, string $pesan, array $data = [], int $kode = 200)
    {
        return $this->response
            ->setStatusCode($kode)
            ->setJSON(array_merge([
                'status'  => $sukses ? 'success' : 'error',
                'message' => $pesan,
            ], $data));
    }
}
