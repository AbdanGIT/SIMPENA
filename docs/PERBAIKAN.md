# Analisis & Perbaikan SIMPENA (19 Nov 2026)

Dokumen ini mencatat masalah yang ditemukan saat audit aplikasi `c:\wamp64\www\SIMPENA`
beserta perbaikannya. Semua perubahan sudah diuji lewat HTTP (login, CRUD, upload,
approval) memakai akun uji sementara yang kemudian dihapus.

---

## A. Masalah yang membuat aplikasi mati total

1. **Database `simpena` tidak ada**
   - Gejala: seluruh halaman balas `503`, log `Unable to connect to the database`.
   - Perbaikan: database dibuat ulang lalu dump terbaru (`simpena 17112026.sql`)
     diimpor (19 objek: 13 tabel + 4 view + 1 procedure + 1 tabel laporan).

2. **PHP server (8.0.30) lebih rendah dari syarat CodeIgniter 4.7 (8.2)**
   - Gejala: `503 — Your PHP version must be 8.2 or higher`.
   - Perbaikan: `public/.htaccess` mengarahkan folder SIMPENA ke
     `php8.3.28\php-cgi.exe` melalui `mod_fcgid`. Tidak perlu restart Apache dan
     aplikasi lain (SIMDIK) tetap memakai PHP 8.0.

3. **Rewrite CI4 tidak cocok untuk FastCGI**
   - Gejala: `404 No input file specified` setelah PHP 8.3 aktif.
   - Perbaikan: rewrite diubah dari `index.php/$1` menjadi `index.php?/$1`.

---

## B. Bug logika yang ditemukan

### B1. `TypeError` saat sesi habis
`Pencatatan::index()` dan `Kerusakan::index()` dideklarasikan `: string` tetapi
mengembalikan `RedirectResponse` → fatal error (tercatat di
`writable/logs/log-2026-11-19.log`).
**Perbaikan:** tipe kembalian dihapus; pengecekan login dipindah ke filter global.

### B2. Semua endpoint tulis bisa diakses tanpa login
Sebelumnya hanya halaman `index()` yang mengecek session; `simpan`, `update`,
`hapus`, `pindah_ruangan`, `update_profil`, dst. tidak. `Approval` bahkan tanpa
pengecekan login sama sekali.
**Perbaikan:** dibuat `app/Filters/AuthFilter.php` dan didaftarkan sebagai filter
global `auth` (kecuali `login`, `login/*`). Permintaan AJAX yang belum login
dibalas JSON `401`.

### B3. Struktur tabel tanpa PRIMARY KEY / AUTO_INCREMENT
`tbl_kerusakan`, `tbl_pengadaan`, `tbl_service_report`, `tbl_arsip`,
`tbl_incoming`, `tbl_outgoing`, `tbl_desain_zebra` tidak punya auto increment
(sebagian tanpa PK). Akibatnya setiap INSERT menghasilkan `id = 0`, dan
`find(0)`/`update(0)` melempar `InvalidArgumentException: Invalid primary key`.
Selain itu `tbl_pengguna.pengguna_id = 0` untuk user `admin2` membuat menu
Pengaturan error.
**Perbaikan:** semua tabel diberi PK + `AUTO_INCREMENT`; `admin2` dipindah ke id 3;
`pengguna_username`, `part_number`, dan `no_report` diberi UNIQUE.
Skrip: `docs/schema_fixes.sql`.

### B4. Route hapus kerusakan salah
`$routes->group('kerusakan', ...)` berisi `$routes->get('kerusakan/hapus/(:num)')`
sehingga URL sebenarnya menjadi `kerusakan/kerusakan/hapus/{id}`.
**Perbaikan:** menjadi `POST kerusakan/hapus/(:num)`.

### B5. Approval edit menyimpan seluruh isi POST
Saat Admin mengedit kerusakan, `data_baru` diisi `json_encode($this->request->getPost())`
— termasuk `csrf_test_name`, `addFoto`, `id_kerusakan`. Ketika disetujui, array itu
dipakai `update()` sehingga query gagal/menulis kolom yang salah.
**Perbaikan:**
- payload dipetakan ke kolom tabel (`payload()` di `Kerusakan`);
- eksekusi approval memakai whitelist kolom (`Approval::KOLOM`);
- aksi `hapus` menghapus foto bukti dari disk;
- hanya baris `PENDING` yang bisa diproses (tidak dobel eksekusi);
- tombol Setujui/Tolak diubah dari link GET menjadi **form POST + CSRF**.

### B6. Nomor Service Report salah
`generateNoReport()` hanya mengambil baris terakhir berdasarkan `id_report` lalu
menambah 1, tanpa memperhatikan bulan. Bisa menghasilkan nomor dobel.
**Perbaikan:** urutan dihitung dari `MAX(nomor)` untuk bulan Romawi + tahun
berjalan, dicek ulang sampai tidak bentrok, dan kolom `no_report` diberi UNIQUE.
Nomor otomatis juga diisi saat form tidak mengirim `no_report`.

### B7. Perhitungan stok sparepart tidak konsisten
- nilai `incoming`/`outgoing` diubah dengan menambah/mengurangi angka kolom
  (bukan dari data riil), sehingga gampang melenceng;
- tidak ada validasi qty maupun stok tersedia → stok bisa minus;
- `update_incoming`/`update_outgoing` tidak menangani perubahan part number;
- transaksi tidak diperiksa (`transStatus()`), error bisa setengah tersimpan;
- `part_number` tidak unik sehingga `where('part_number', ...)->first()` bisa salah baris.

**Perbaikan:**
- `recalcStok()` menghitung ulang `incoming`, `outgoing`, `stock_actual` dari
  `SUM(tbl_incoming.qty_masuk)` dan `SUM(tbl_outgoing.qty_keluar)`;
- qty wajib > 0 dan part number harus terdaftar;
- outgoing ditolak bila melebihi stok tersedia (termasuk saat edit, dengan
  memperhitungkan qty lama);
- semua operasi dibungkus transaksi + cek `transStatus()`;
- `part_number` diberi UNIQUE;
- `stock_actual` saat simpan/edit tidak lagi dipercaya dari input (`addAktual`),
  selalu `stock_awal + incoming - outgoing`.

### B8. Unggahan berkas tanpa validasi
Semua upload hanya memindahkan file apa adanya (nama random) — file `.php`
berbahaya bisa masuk ke `public/uploads`. Folder `uploads/arsip` juga bisa belum
ada sehingga `move()` melempar exception.
**Perbaikan:** `App\Libraries\Berkas`:
- whitelist ekstensi + verifikasi MIME (`finfo`) + batas ukuran;
- folder dibuat otomatis;
- penghapusan file lama yang aman (menolak path traversal);
- `public/uploads/.htaccess` mematikan eksekusi skrip (`Require all denied` untuk
  `.php`, `.phtml`, `.cgi`, dst.).

### B9. Foto dihapus padahal masih dipakai riwayat mutasi
`pindah_ruangan()` dan `hapus()` menghapus file foto lama tanpa mengecek
`tbl_riwayat_mutasi`, sehingga gambar di riwayat menjadi rusak.
**Perbaikan:** `hapusFotoLama()` hanya menghapus file bila tidak direferensikan
riwayat mutasi.

### B10. CSRF token berubah tiap request
`.env` memakai `security.regenerate = true`. Halaman yang melakukan beberapa AJAX
(simpan lalu hapus) mengirim token yang sudah kedaluwarsa → `403 SecurityException`
(terlihat berulang di log).
**Perbaikan:** `security.regenerate = false` (token tetap per sesi). Semua aksi
hapus yang sebelumnya GET juga diubah menjadi **POST** dengan header
`X-CSRF-TOKEN`, jadi tidak bisa dipicu dari tautan/gambar pihak ketiga.

### B11. Keamanan login
- session tidak di-regenerate (rawan session fixation);
- tidak ada pembatasan percobaan;
- password **plaintext** masih diterima, MD5 tidak pernah di-upgrade.
**Perbaikan (`Auth`, `UserModel`):**
- `session()->regenerate(true)` setelah login sukses;
- maksimum 5 percobaan gagal, lalu dikunci 60 detik (berbasis session);
- fallback plaintext dihapus; MD5 tetap didukung tetapi otomatis di-rehash ke
  bcrypt saat login berhasil (`password_needs_rehash`).

### B12. Dashboard memakai angka statis
Empat kartu statistik berisi angka hardcode (5, 17, 12, 5) dan grafik hanya SVG
statis, padahal database punya view `v_dashboard_stats`.
**Perbaikan:** `Dashboard` membaca `v_dashboard_stats`, menambah hitungan
inventaris & antrian, dan membuat grafik 8 hari terakhir dari
`tbl_service_report.tanggal` serta `tbl_kerusakan.tgl_service`.

### B13. Validasi input minim
- `Arsip`: file tidak wajib, tanggal bisa kosong, `update()` menimpa dengan null.
- `AntrianSparepart`, `ZebraLabel`: tidak ada validasi field wajib; status bebas.
- `Pengaturan`: username bisa dobel, email tidak divalidasi, password baru tanpa
  panjang minimum, foto profil tanpa validasi tipe.
**Perbaikan:** validasi + pesan Indonesia, status di-whitelist, `update_profil`
menolak username yang sudah dipakai, password minimal 6 karakter.

### B14. Berkas sensitif di web root
`gen_pass.php` (generator hash password) bisa diakses publik dan folder
`public/null/` menyimpan session lama.
**Perbaikan:** `gen_pass.php` dihapus, `public/null/` dibersihkan.

### B15. `baseURL` dipatok `localhost` → CSS hilang di HP & login nyasar
`.env` berisi `app.baseURL = 'http://localhost/SIMPENA/public/'`. Akibatnya saat
aplikasi dibuka dari HP atau PC lain (mis. `http://10.51.47.129/SIMPENA/public/`)
semua `<link>` CSS/JS, `action` form, dan redirect login tetap menunjuk ke
`http://localhost/...`. Di perangkat pengguna `localhost` adalah perangkat itu
sendiri, sehingga:
- CSS/JS tidak termuat (halaman tampil polos tanpa gaya), dan
- form login mengirim POST ke `localhost` milik pengguna → setelah submit
  pengguna terlempar ke aplikasi lain yang berjalan di localhost tersebut
  (di mesin ini: portal NOC di `http://localhost/`).
**Perbaikan:** `app.baseURL` dikosongkan di `.env` dan `Config\App::__construct()`
mendeteksi baseURL dari request (`App::deteksiBaseURL()`): scheme (`HTTPS`/
`X-Forwarded-Proto`) + `HTTP_HOST` + folder skrip (`/SIMPENA/public/`), dengan
fallback ke `app.baseURL` bila diisi manual atau saat CLI. Hasil uji dengan
`Host: 10.51.47.129`: seluruh link aset, action form, dan `Location` login
memakai host tersebut; nol referensi `http://localhost`.

### B16. Layout tidak responsif di layar kecil
Sidebar dipatok 270px dan `.content-wrapper`/`.main-footer` memakai
`margin-left` yang sama, tanpa satu pun media query — di HP konten hanya sisa
±90px. Selain itu atribut `width="320px;"` pada logo login tidak valid sehingga
logo tampil ukuran asli dan melebihi layar.
**Perbaikan:** `public/assets/css/mobile.css` (dimuat terakhir di `header.php`)
membuat sidebar menjadi laci *off-canvas* dengan tombol menu mengambang +
backdrop, konten selebar layar, navbar/banner menumpuk, kartu dan tabel bisa
digeser ke samping, serta penyesuaian modal. `layout/footer.php` berisi JS
`toggleSidebar()` yang sadar ukuran layar (HP: buka/tutup laci; desktop:
minimize seperti sebelumnya). Media query di `auth/login.php` menjaga kotak
login dan logo tetap muat di layar 360–480px.

### B17. Harus mengetik `/public` di URL, dan huruf kecil 404
Dokumentasi lama menyuruh membuka `http://localhost/SIMPENA/public/`. Akar
folder SIMPENA tidak punya `index.php`, dan `.htaccess` root hanya me-rewrite
bila file/folder **tidak** ada, sehingga `http://localhost/SIMPENA/` (yang
merupakan folder nyata) berhenti dengan 403/404.
Selain itu, di Windows nama folder tidak peka huruf besar/kecil: URL
`/simpena/login` dijalankan oleh `SIMPENA/public/index.php`, tetapi Apache
melaporkan `SCRIPT_NAME` dengan huruf asli folder. `SiteURIFactory` milik
CodeIgniter memotong path `REQUEST_URI` memakai `SCRIPT_NAME` secara
*case-sensitive*, sehingga `/simpena/login` dibaca sebagai route
`simpena/login` → 404 (`/simpena/` pun 404).
**Perbaikan:**
- `.htaccess` root diganti: semua permintaan diteruskan ke `public/`
  (`RewriteRule ^public(/|$) - [L,NC]` lalu `RewriteRule ^(.*)$ public/$1 [L]`),
  plus perapian garis miring di akhir URL. Efek samping positif: `app/`,
  `vendor/`, `writable/`, `.env`, `*.sql`, dan `spark` tidak bisa diunduh
  langsung (semuanya 404/403).
- `App::deteksiBaseURL()` memakai huruf folder sesuai yang diketik pengguna dan
  membuang segmen `/public` bila URL tidak memintanya, sehingga link yang
  dihasilkan bersih (`http://host/SIMPENA/login`, bukan `.../public/login`).
- `public/index.php` menyamakan huruf `$_SERVER['SCRIPT_NAME']` (dan `PHP_SELF`)
  dengan segmen `REQUEST_URI`, supaya pemotongan path oleh CodeIgniter cocok
  untuk URL huruf kecil. Catatan: blok ini tambahan lokal, perlu diingat saat
  upgrade framework.
Hasil uji: `/SIMPENA/`, `/simpena/`, `/simpena/login`, `/simpena/public/`,
`/simpena/login/` (301 → `/simpena/login`), `/simpena/dashboard`
(302 → `/simpena/login`) semuanya benar, termasuk lewat `Host: 10.51.47.129`.

### B18. Foto riwayat mutasi tampil rusak (gambar pecah)
Kolom `tbl_riwayat_mutasi.foto` tidak selalu berisi gambar. Data hasil impor
menyimpan **PDF** (`1794903494_f705f90e548ad624f0af.pdf`,
`1794903844_ed647c1549de17350364.pdf`, `1794904708_a275d775c3137483de8d.pdf`),
sedangkan `riwayat_mutasi/index.php` selalu merendernya dengan `<img>` sehingga
yang tampil hanya ikon gambar rusak (paling kentara di tampilan HP).
**Perbaikan:** view memeriksa ekstensi + keberadaan berkas lewat closure
`$lampiranMutasi()`:
- gambar (`jpg/jpeg/png/gif/webp/bmp/avif`) → thumbnail 45×45 yang bisa diklik;
- PDF / dokumen lain → kartu bertanda **PDF** (atau ekstensi berkas) yang
  membuka lampiran di tab baru;
- berkas tidak ada di `public/uploads/inventaris` → tanda `-`, bukan gambar
  rusak; nama berkas di-`basename()` agar tidak bisa menunjuk ke luar folder.
Unggahan baru tetap dibatasi gambar (`Berkas::GAMBAR`) pada `pindah_ruangan()`,
jadi kasus ini hanya terjadi pada data lama.

### B19. Label menu sidebar hilang saat sidebar dalam mode minimize di HP
`layout/header.php` dan `layout/sidebar.php` menyembunyikan semua `span` menu
(`body.sidebar-minimize .sidebar-menu span { display: none !important }`) untuk
mode ikon di desktop. Di HP `mobile.css` hanya mengembalikan `a > span`,
sedangkan empat menu (**Data Kerusakan, Pencatatan, Riwayat Mutasi, Pengadaan**)
membungkus labelnya dengan `<div>` sehingga tetap tersembunyi — menunya hanya
tampil sebagai ikon tanpa tulisan.
**Perbaikan:** `public/assets/css/mobile.css` (blok `@media (max-width: 991.98px)`)
memakai selector yang lebih spesifik dan mencakup `span` maupun `p` di dalam
`section.sidebar ul.sidebar-menu li a`, lalu menormalkan aturan untuk `<div>`
label (`display: flex`). Lebar laci juga dinaikkan (270px → 300px, maksimal
86vw) agar label muat satu baris. Hasil uji headless pada 360/390/412px:
13 item menu (termasuk MENU CEPAT) tampil lengkap dengan teks.

---

## C. Hasil pengujian (HTTP, akun uji sementara)

| Uji | Hasil |
|---|---|
| Halaman tanpa login (`dashboard`, `pencatatan`, `approval`) | 302 → login |
| POST tulis tanpa login | ditolak filter |
| Login benar / salah | sukses / pesan + hitungan sisa percobaan |
| Pencatatan simpan + foto | tersimpan, foto masuk `uploads/inventaris` |
| Update, pindah ruangan, tolak ruangan sama, hapus | sesuai (mutasi tercatat, foto aman) |
| Upload `*.php` sebagai foto | ditolak (ekstensi) |
| Upload PHP menyamar `.pdf` | ditolak (MIME `text/x-php`) |
| Hapus tanpa token CSRF | 403 |
| Service report nomor otomatis | `167/SR-XI/2026`, nomor dobel ditolak 409 |
| Pengadaan: awal 10 + incoming 5 | `stock_actual = 15` |
| Outgoing 20 (> stok 15) | ditolak "Stok tidak cukup" |
| Outgoing 5 | `stock_actual = 10` |
| Admin edit kerusakan | masuk `tbl_approval`, payload hanya kolom tabel |
| Super Admin setujui | data berubah, status `APPROVED` |
| Admin hapus kerusakan → disetujui | baris + foto terhapus |
| Arsip upload dokumen + hapus | file tersimpan lalu terhapus |
| Zebra label, antrian sparepart | simpan/update/hapus OK |
| PHP di `public/uploads` | 403, gambar tetap 200 |
| Riwayat mutasi dengan lampiran PDF | tampil kartu **PDF** (bukan gambar rusak) |

---

## D. Rekomendasi lanjutan (belum dikerjakan)

1. ~~Ganti `CI_ENVIRONMENT` menjadi `production`~~ — sudah dilakukan (19 Nov 2026);
   kembalikan ke `development` hanya saat debugging.
2. Arahkan DocumentRoot virtual host langsung ke `SIMPENA/public` (bukan subfolder).
3. Tambahkan pagination pada tabel besar (`tbl_service_report` sudah 165 baris).
4. Tambahkan `ON DELETE`/`ON UPDATE` FK atau pembersihan manual untuk
   `tbl_riwayat_mutasi` bila barang dihapus.
5. Buat dump database baru setelah `docs/schema_fixes.sql` diterapkan, agar
   instalasi berikutnya tidak perlu ALTER manual.
6. Ganti password `admin` dan `admin2` (hash awal berasal dari dump lama).

