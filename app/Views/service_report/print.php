<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Service Report - <?= $report['no_report'] ?></title>
    <style>
        /* PENGATURAN KERTAS A4 & MARGIN */
        @page {
            size: A4;
            margin: 1.5cm 2cm 1.5cm 2cm; 
        }
        
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt; 
            line-height: 1.15; 
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* MENCEGAH KONTEN TERPOTONG KE HALAMAN 2 */
        .page-container {
            page-break-inside: avoid;
        }

        /* =========================================
           PERBAIKAN HEADER / KOP SURAT
           ========================================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #000; 
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
            line-height: 1.1;
            font-family: Arial, Helvetica, sans-serif;
        }
        .header-text h3 { 
            margin: 0; 
            font-size: 17pt; 
            font-weight: normal; 
            letter-spacing: 0.5px;
        }
        .header-text h2 { 
            margin: 2px 0; 
            font-size: 18pt; 
            font-weight: bold; 
            letter-spacing: 0.5px;
        }
        .header-text .alamat { 
            margin: 3px 0 0 0; 
            font-size: 9pt; 
        }
        .header-text .kontak { 
            margin: 0; 
            font-size: 9pt; 
        }
        .header-text .kontak a {
            color: blue; 
            text-decoration: underline; 
        }
        
        /* =========================================
           KUNCI UKURAN LOGO AGAR TIDAK MEMBESAR
           ========================================= */
        .header img { 
            width: 75px !important; 
            height: auto !important; 
            max-width: 75px !important;
            max-height: 85px !important;
            object-fit: contain;
        }

        /* =========================================
           JUDUL TENGAH
           ========================================= */
        .title {
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.2;
            font-family: "Times New Roman", Times, serif;
        }
        .title h4 { 
            margin: 0; 
            font-size: 12pt; 
            text-decoration: underline; 
            text-transform: uppercase; 
            font-weight: bold;
        }
        .title p { 
            margin: 0; 
            font-size: 12pt; 
            text-transform: uppercase; 
            font-weight: bold;
            text-decoration: underline;
        }

        /* =========================================
           TABEL INFORMASI
           ========================================= */
        .info-table { width: 100%; margin-bottom: 10px; }
        .info-table td { vertical-align: top; padding: 2px 0; font-size: 11pt; }
        .info-table td:first-child { width: 25%; }
        .info-table td:nth-child(2) { width: 2%; }
        .info-table td:nth-child(3) { width: 23%; }
        .info-table td:nth-child(4) { width: 2%; }
        .info-table td:nth-child(5) { width: 48%; }

        /* BAGIAN CEKLIS (CHECKBOX) */
        .checkbox-group { margin-bottom: 10px; }
        .checkbox-group table { width: 100%; border-collapse: collapse; }
        .checkbox-group td { vertical-align: top; padding: 2px 0; font-size: 11pt; }
        .box { 
            display: inline-block; 
            width: 11px; 
            height: 11px; 
            border: 1px solid #000; 
            text-align: center; 
            line-height: 11px; 
            font-size: 10px; 
            margin-right: 5px; 
            vertical-align: middle; 
        }
        .checked::before { content: "✓"; font-weight: bold; font-family: sans-serif; }

        /* BOKS KETERANGAN (AUTO-HEIGHT) */
        .box-text {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 10px;
            min-height: 40px; 
            font-size: 11pt;
        }
        .box-text-title { font-weight: bold; margin-bottom: 5px; }

        /* TANDA TANGAN */
        .ttd-container { width: 100%; margin-top: 20px; text-align: center; page-break-inside: avoid; }
        .ttd-container table { width: 100%; }
        .ttd-container td { width: 33.33%; vertical-align: top; font-size: 11pt; }
    </style>
</head>
<body onload="printAndReturn()">

    <script>
        function printAndReturn() {
            // Jalankan dialog print
            window.print();

            // Setelah dialog print ditutup (Save/Print/Cancel), arahkan kembali ke halaman tabel
            window.onafterprint = function() {
                 window.location.href = "<?= base_url('service_report') ?>"
            };
        }
    </script>

    <div class="page-container">
        <!-- ============================================== -->
        <!-- HEADER KOP SURAT -->
        <!-- ============================================== -->
        <div class="header">
            <!-- Logo Kiri -->
            <img src="<?= base_url('assets/images/logo_kiri.png') ?>" alt="Logo Kiri">
            
            <div class="header-text">
                <h3>PEMERINTAH KOTA BANJARMASIN</h3>
                <h2>RSUD SULTAN SURIANSYAH</h2>
                <p class="alamat">Jalan Rantauan Darat RT.04 RW.01 Banjarmasin Kode Pos 70246</p>
                <p class="kontak">Telepon. (0511)6782000/ (0511)6783707</p>
                <p class="kontak">Website: <a href="http://rsudss.banjarmasinkota.go.id" target="_blank">http://rsudss.banjarmasinkota.go.id</a> E-mail: <a href="mailto:rsudsultansuriansyah@gmail.com">rsudsultansuriansyah@gmail.com</a></p>
            </div>

            <!-- Logo Kanan -->
            <img src="<?= base_url('assets/images/logo_kanan.png') ?>" alt="Logo Kanan">
        </div>

        <!-- JUDUL -->
        <div class="title">
            <h4>SERVICE REPORT</h4>
            <p>RSUD SULTAN SURIANSYAH BANJARMASIN</p>
        </div>

        <!-- INFO REPORT & TANGGAL -->
        <table style="width: 100%; margin-bottom: 10px; font-weight: bold; font-size: 11pt;">
            <tr>
                <td>Service Report No: <?= $report['no_report'] ?></td>
                <td style="text-align: right;">Tanggal: 
                    <?php 
                        if (!empty($report['tanggal'])) {
                            $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $tgl = date('d', strtotime($report['tanggal']));
                            $bln = $bulanIndo[date('n', strtotime($report['tanggal'])) - 1];
                            $thn = date('Y', strtotime($report['tanggal']));
                            echo "$tgl $bln $thn"; 
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
            </tr>
        </table>

        <!-- SERVICE REPORT TYPE -->
        <table class="info-table" style="margin-bottom: 10px; table-layout: fixed;">
            <tr>
                <td style="font-weight: bold; width: 25%; vertical-align: top; white-space: nowrap;">
                    Service Report Type :
                </td>
                <td style="width: 75%; vertical-align: top;">
                    <div class="checkbox-group" style="display: flex; justify-content: space-between;">
                        <!-- Kolom Kiri -->
                        <div style="width: 50%;">
                            <div><span class="box <?= strpos($report['service_type'] ?? '', 'Instalasi Baru') !== false ? 'checked' : '' ?>"></span> Instalasi Baru</div>
                            <div><span class="box <?= strpos($report['service_type'] ?? '', 'Pemantauan Funsi Check') !== false ? 'checked' : '' ?>"></span> Pemantauan Funsi Check</div>
                            <div><span class="box <?= strpos($report['service_type'] ?? '', 'Kalibrasi') !== false ? 'checked' : '' ?>"></span> Kalibrasi</div>
                        </div>
                        <!-- Kolom Kanan -->
                        <div style="width: 50%;">
                            <div><span class="box <?= strpos($report['service_type'] ?? '', 'Pemeliharaan/Maintenance') !== false ? 'checked' : '' ?>"></span> Pemeliharaan/Maintenance</div>
                            <div><span class="box <?= strpos($report['service_type'] ?? '', 'Perbaikan/Service') !== false ? 'checked' : '' ?>"></span> Perbaikan/Service</div>
                            <div><span class="box <?= strpos($report['service_type'] ?? '', 'Recall Pemeriksaan') !== false ? 'checked' : '' ?>"></span> Recall Pemeriksaan</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- DETAIL BARANG -->
        <table class="info-table" style="margin-bottom: 10px;">
            <tr>
                <td><strong>Nama Barang</strong></td><td>:</td><td><?= $report['nama_prasarana'] ?></td>
                <td><strong>Ruangan</strong></td><td>: <?= $report['nama_ruangan'] ?></td>
            </tr>
            <tr>
                <td><strong>Merk</strong></td><td>:</td><td><?= $report['merek'] ?></td>
                <td><strong>Kalibrasi Terakhir</strong></td><td>: -</td>
            </tr>
            <tr>
                <td><strong>Tipe/Model</strong></td><td>:</td><td><?= $report['tipe_model'] ?></td>
                <td><strong>Distributor</strong></td><td>: -</td>
            </tr>
            <tr>
                <td><strong>S/N (Kode Alat)</strong></td><td>:</td><td><?= $report['no_seri'] ?></td>
                <td><strong>Tahun</strong></td><td>: <?= $report['tahun'] ?></td>
            </tr>
        </table>

        <!-- PEMERIKSAAN -->
        <div style="font-weight: bold; margin-bottom: 5px; font-size: 11pt;">Pemeriksaan:</div>
        <table class="info-table" style="margin-bottom: 10px;">
            <tr>
                <td style="width: 50%;">
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Trouble Shooting') !== false ? 'checked' : '' ?>"></span> Trouble Shooting</div>
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Uji Energi Power Supply') !== false ? 'checked' : '' ?>"></span> Uji Energi Power Supply</div>
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Uji Ritik/Roller, bearing, caring') !== false ? 'checked' : '' ?>"></span> Uji Ritik/Roller, bearing, caring</div>
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Mekanik / sensor roller, paper feed') !== false ? 'checked' : '' ?>"></span> Mekanik / sensor roller, paper feed</div>
                </td>
                <td style="width: 50%;">
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Penempatan Alat (fix error, status device)') !== false ? 'checked' : '' ?>"></span> Penempatan Alat (fix error, status device)</div>
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Kelengkapan Aksesori (kabel USB, power, dll)') !== false ? 'checked' : '' ?>"></span> Kelengkapan Aksesori (kabel USB, power, dll)</div>
                    <div><span class="box <?= strpos($report['pemeriksaan'] ?? '', 'Setting Parameter / Konfigurasi (IP, driver, port)') !== false ? 'checked' : '' ?>"></span> Setting Parameter / Konfigurasi (IP, driver, port)</div>
                </td>
            </tr>
        </table>

        <!-- PERMASALAHAN DAN SOLUSI -->
        <div class="box-text">
            <div class="box-text-title">Permasalahan dan Solusi:</div>
            <?= nl2br(htmlspecialchars($report['permasalahan'] ?? '')) ?>
        </div>

        <!-- KETERANGAN -->
        <div class="box-text">
            <div class="box-text-title">Keterangan:</div>
            <?= nl2br(htmlspecialchars($report['keterangan'] ?? '')) ?>
        </div>

        <!-- HASIL PEMERIKSAAN -->
        <div style="margin-top: 15px; display: flex; align-items: center; font-family: 'Times New Roman', Times, serif; font-size: 12pt;">
            
            <div style="font-weight: bold; margin-right: 15px; white-space: nowrap;">
                Hasil Pemeriksaan:
            </div>
            
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="white-space: nowrap;">
                    <span class="box <?= strpos($report['hasil_pemeriksaan'] ?? '', 'Berfungsi Baik') !== false ? 'checked' : '' ?>"></span> Berfungsi Baik
                </div>
                <div style="white-space: nowrap;">
                    <span class="box <?= strpos($report['hasil_pemeriksaan'] ?? '', 'Berfungsi Tidak Sempurna') !== false ? 'checked' : '' ?>"></span> Berfungsi Tidak Sempurna
                </div>
                <div style="white-space: nowrap;">
                    <span class="box <?= strpos($report['hasil_pemeriksaan'] ?? '', 'Tidak Berfungsi') !== false ? 'checked' : '' ?>"></span> Tidak Berfungsi
                </div>
            </div>

        </div>

        <!-- TANDA TANGAN -->
        <div class="ttd-container" style="margin-top: 40px;">
            <table style="width: 100%; text-align: center; border-collapse: collapse;">
                <tr>
                    <!-- KOLOM KIRI (User) -->
                    <td style="width: 33.33%; vertical-align: top; font-size: 11pt;">
                        User
                        <br><br><br><br><br><br><br>
                        ( <?= !empty($report['ttd_kiri']) ? $report['ttd_kiri'] : '.........................' ?> )
                    </td>
                    
                    <!-- KOLOM TENGAH (Mengetahui) -->
                    <td style="width: 33.33%; vertical-align: top; font-size: 11pt;">
                        Mengetahui,<br>
                        <?= !empty($report['ttd_tengah']) ? $report['ttd_tengah'] : 'Staff PDE' ?>
                        <br><br><br><br><br><br>
                        ( ......................... )
                    </td>
                    
                    <!-- KOLOM KANAN (Staff PDE) -->
                    <td style="width: 33.33%; vertical-align: top; font-size: 11pt;">
                        Staff PDE
                        <br><br><br><br><br><br><br>
                        ( <?= !empty($report['ttd_kanan']) ? $report['ttd_kanan'] : '.........................' ?> )
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>