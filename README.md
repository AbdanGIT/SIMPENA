# SIMPENA — Sistem Informasi Manajemen Pemeliharaan & Inventaris

Aplikasi internal (CodeIgniter 4.7) untuk mencatat inventaris komputer/printer,
kerusakan & service, pengadaan + stok sparepart, service report, arsip dokumen,
antrian sparepart, dan desain label Zebra.

Alamat aplikasi: `http://localhost/SIMPENA/` — cukup sampai nama folder,
tidak perlu menambahkan `/public`. URL lama `http://localhost/SIMPENA/public/`
tetap bisa dipakai.

Aplikasi bisa dibuka dari HP / PC lain di jaringan yang sama memakai IP server,
mis. `http://10.51.47.129/SIMPENA/` (huruf besar/kecil sama saja, mis.
`http://10.51.47.129/simpena/`) — alamat dasar (CSS, JS, form, redirect)
dideteksi otomatis dari host yang dipakai, jadi tidak perlu diubah manual.

---

## 0. Mengambil source dari GitHub (clone)

Repo publik: <https://github.com/AbdanGIT/SIMPENA> — branch `main`, tanpa perlu login.

```powershell
git clone https://github.com/AbdanGIT/SIMPENA.git c:\wamp64\www\SIMPENA
cd c:\wamp64\www\SIMPENA
composer install                        # vendor/ tidak disertakan di repo
Copy-Item .env.example .env             # lalu isi database.default.*
```

Clone cepat (hanya commit terakhir, tanpa riwayat): tambahkan `--depth 1`.

Setelah clone: lanjut ke [bagian 2](#2-instalasi--memulihkan-database) untuk membuat
database, lalu buka `http://localhost/SIMPENA/`.

| Ada di repo | Tidak ada (disiapkan sendiri) |
|---|---|
| `app/`, `public/` (tanpa `uploads/`), `tests/`, `docs/`, `composer.json`, `composer.lock` | `vendor/` → `composer install` |
| `.env.example`, `.htaccess`, `spark`, `preload.php` | `.env` → salin dari `.env.example` |
| `public/assets/` (AdminLTE, Bootstrap, jQuery, JS modul) | `public/uploads/` dan `public/assets/images/` (berkas unggahan) |
| `docs/*.sql` (skema) | dump data asli `simpena*.sql` (tidak ikut, lihat bagian 2) |

Memperbarui salinan lokal:

```powershell
cd c:\wamp64\www\SIMPENA
git pull --rebase
```

Mengirim perubahan dari PC ini (lihat juga `c:\wamp64\www\github-sync\SYNC.bat`):

```powershell
git add -A
git commit -m "Update SIMPENA <tanggal>"
git push origin main
```

---


## 1. Kebutuhan server

| Komponen | Versi |
|---|---|
| PHP | **8.2 atau lebih baru** (di mesin ini dipakai PHP 8.3.28) |
| Ekstensi PHP | `intl`, `mbstring`, `mysqli`, `curl`, `gd`, `fileinfo` |
| Database | MySQL 8.x, nama database **`simpena`** |
| Web server | Apache 2.4 + `mod_rewrite` + `mod_fcgid` |

> Catatan penting: Apache di mesin ini masih dikonfigurasi memakai PHP 8.0.30
> (mod_php), sedangkan CodeIgniter 4.7 membutuhkan PHP >= 8.2. Karena itu
> `public/.htaccess` mengarahkan folder ini ke **PHP 8.3.28 lewat mod_fcgid**:

```apache
<IfModule fcgid_module>
    Options +ExecCGI
    AddHandler fcgid-script .php
    FcgidWrapper "c:/wamp64/bin/php/php8.3.28/php-cgi.exe" .php
</IfModule>
```

Efeknya: SIMPENA memakai PHP 8.3 tanpa mengubah PHP global Apache (SIMDIK dsb.
tetap di PHP 8.0) dan **tanpa perlu restart Apache**. Kalau nanti seluruh Apache
dipindah ke PHP 8.3, blok ini boleh dihapus.

Blok rewrite di file yang sama memakai bentuk `index.php?/$1` (bukan
`index.php/$1`) karena PHP dijalankan lewat FastCGI — PATH_INFO tidak dipisah
otomatis oleh Apache.

---

## 2. Instalasi / memulihkan database

```powershell
# 1. buat database
c:\wamp64\bin\mysql\mysql8.4.7\bin\mysql.exe -u root -e "CREATE DATABASE simpena CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

# 2. import dump terbaru
c:\wamp64\bin\mysql\mysql8.4.7\bin\mysql.exe -u root simpena < "simpena 17112026.sql"

# 3. terapkan perbaikan struktur tabel (PK + AUTO_INCREMENT + UNIQUE)
c:\wamp64\bin\mysql\mysql8.4.7\bin\mysql.exe -u root simpena < docs\schema_fixes.sql
```

Konfigurasi koneksi ada di `.env` (`database.default.*`). `app.baseURL` sengaja
**tidak diisi**: `Config\App` mendeteksi base URL dari request (host + folder
aplikasi), sehingga aplikasi tetap benar saat diakses lewat `localhost`, IP LAN,
maupun nama host — dengan atau tanpa `/public`. Isi `app.baseURL` hanya bila
ingin memaksa satu alamat (mis. di belakang reverse proxy).

Akun awal: lihat tabel `tbl_pengguna`
- `admin` (level 1 = Super Admin)
- `admin2` (level 2 = Admin biasa)

**Segera ganti password default** lewat menu *Pengaturan*.

---

## 3. Struktur singkat

```
app/
  Config/Routes.php        pemetaan URL
  Filters/AuthFilter.php   wajib login untuk semua halaman (kecuali login)
  Controllers/             Auth, Dashboard, Pencatatan, Kerusakan, Pengadaan,
                           ServiceReport, Arsip, AntrianSparepart, ZebraLabel,
                           RiwayatMutasi, StokSparepart, Pengaturan, Approval
  Libraries/Berkas.php     validasi + simpan + hapus berkas unggahan
  Models/                  satu model per tabel
  Views/                   layout (header/sidebar/footer) + view per modul
public/
  assets/                  AdminLTE, Bootstrap, jQuery, JS per modul
  uploads/                 foto inventaris, bukti kerusakan, dokumen arsip
  .htaccess                FastCGI PHP 8.3 + rewrite CI4
docs/
  PERBAIKAN.md             daftar bug yang ditemukan & diperbaiki
  schema_fixes.sql         ALTER tabel (PK/AUTO_INCREMENT/UNIQUE)
```

---

## 4. Alur hak akses

| Aksi | Admin (level 2) | Super Admin (level 1) |
|---|---|---|
| Lihat semua halaman | ya | ya |
| Tambah data (pencatatan, kerusakan, pengadaan, service report, arsip, dst) | ya | ya |
| Edit / hapus data kerusakan | **masuk antrean approval** | langsung dieksekusi |
| Menu *Persetujuan* | tidak tampil | ya |

Approval disimpan di `tbl_approval` (status `PENDING` → `APPROVED` / `REJECTED`).
Saat disetujui, data dieksekusi dalam transaksi dan foto bukti ikut dihapus bila
baris dihapus.

---

## 5. Aturan unggahan berkas

Semua unggahan divalidasi oleh `App\Libraries\Berkas`:

| Jenis | Folder | Ekstensi | Maks |
|---|---|---|---|
| Foto inventaris / kerusakan / profil | `uploads/inventaris`, `uploads`, `assets/images` | jpg, jpeg, png, webp | 5–10 MB |
| Dokumen arsip | `uploads/arsip` | pdf, doc(x), xls(x), ppt(x), jpg, png, webp, zip | 25 MB |
| Dokumentasi pengadaan | `uploads` | pdf, doc(x), xls(x), ppt(x), jpg, png, webp, zip | 10 MB |

Isi berkas dicek dengan MIME (`finfo`), jadi file PHP yang di-rename menjadi
`.pdf`/`.jpg` tetap ditolak. `public/uploads/.htaccess` juga mematikan eksekusi
skrip di folder unggahan.

---

## 6. Catatan operasional

- Environment sudah diset `CI_ENVIRONMENT = production` di `.env` (error detail
  tidak tampil ke pengguna, debug toolbar mati). Saat butuh debugging, ubah
  sementara ke `development` lalu kembalikan lagi.
- Log aplikasi: `writable/logs/`.
- Backup rutin: database `simpena` + folder `public/uploads` + `public/assets/images`.
- Dump `simpena*.sql` di root **belum** memuat perbaikan struktur tabel; jalankan
  `docs/schema_fixes.sql` setelah import, atau buat dump baru dari database sehat.
- Daftar perbaikan kode terbaru: lihat [`docs/PERBAIKAN.md`](docs/PERBAIKAN.md).

---

## 7. Troubleshooting `composer install`

### a) `The zip extension and unzip/7z commands are both missing`

Composer butuh ekstensi PHP `zip` (atau perintah `unzip`/`7z`) untuk membuka
paket ber-format `dist`. Tanpa itu Composer jatuh ke `--prefer-source`
(git clone) yang jauh lebih lambat dan bisa meminta login GitHub.

```powershell
php -v                 # SIMPENA mensyaratkan php ^8.2
php -m | findstr zip   # harus keluar "zip"
```

- **WAMP** — pakai PHP 8.3 bawaan WAMP:
  `c:\wamp64\bin\php\php8.3.28\php.exe C:\composer\composer.phar install`
- **XAMPP** — buka `C:\xampp\php\php.ini`, hapus `;` pada `;extension=zip`,
  pastikan `extension_dir = "C:\xampp\php\ext"` dan `ext\php_zip.dll` ada,
  lalu ulangi `php -m | findstr zip`.
- Alternatif: instal **7-Zip** (Composer mendeteksi `7z` di PATH).

### b) PHP CLI yang dipanggil bukan versi 8.2+

Di WAMP, `php` pada PATH sering masih PHP 8.0, sehingga Composer menolak
`"php": "^8.2"`. Paksa memakai PHP 8.3:

```powershell
c:\wamp64\bin\php\php8.3.28\php.exe C:\composer\composer.phar install --no-interaction
```

Di PC yang sudah punya `C:\composer\composer83.bat` cukup: `composer83 install`.

### c) Composer minta token / `Authentication required`

Repo ini publik, jadi token **tidak wajib**. Prompt token muncul karena paket
`codeigniter4/framework` diunduh dari `api.github.com` dan kena **rate limit
anonim 60 request/jam per IP**. Solusinya buat token sekali lalu simpan global:

1. GitHub → *Settings → Developer settings → Personal access tokens →
   Fine-grained tokens → Generate new token*.
2. Repository access: **Public repositories (read-only)** — tanpa scope tambahan.
3. Simpan:

```powershell
composer config --global github-oauth.github.com <TOKEN>
```

Kuota naik ke 5.000 request/jam dan prompt tidak muncul lagi. **Jangan commit token.**

### d) Tetap gagal / mesin tanpa internet

`vendor/` sengaja tidak masuk repo. Salin folder `vendor` hasil `composer install`
dari PC yang sudah sehat ke PC tujuan (struktur folder sama) — aplikasi langsung
jalan tanpa akses GitHub.

