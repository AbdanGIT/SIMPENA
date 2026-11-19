<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'tbl_pengguna';
    protected $primaryKey       = 'pengguna_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'pengguna_nama',
        'pengguna_username',
        'pengguna_password',
        'pengguna_level',
        'pengguna_email',
        'pengguna_photo',
    ];

    /**
     * Cek kredensial login.
     *
     * Mendukung hash modern (bcrypt/argon) dan hash lama (MD5) supaya user
     * lama tetap bisa masuk. Password plaintext TIDAK lagi diterima.
     */
    public function cekLogin(string $username, string $password): ?array
    {
        $user = $this->where('pengguna_username', $username)->first();

        if ($user === null) {
            return null;
        }

        $hashDb = (string) $user['pengguna_password'];

        // 1. Bcrypt / Argon2
        if (password_verify($password, $hashDb)) {
            return $user;
        }

        // 2. MD5 (legacy) - akan otomatis di-upgrade ke bcrypt oleh Auth::authenticate()
        if (strlen($hashDb) === 32 && hash_equals($hashDb, md5($password))) {
            return $user;
        }

        return null;
    }
}