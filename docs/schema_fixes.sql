-- =====================================================================
-- Perbaikan struktur tabel SIMPENA
-- Jalankan setelah import dump:  mysql -u root simpena < docs\schema_fixes.sql
--
-- Latar belakang: dump lama tidak punya PRIMARY KEY / AUTO_INCREMENT pada
-- beberapa tabel, sehingga setiap INSERT menghasilkan id = 0 dan operasi
-- update/hapus memakai id 0 (error "Invalid primary key: '0' is not allowed").
-- =====================================================================

-- ---------------------------------------------------------------------
-- tbl_pengguna : PK + auto increment + username unik
-- ---------------------------------------------------------------------
UPDATE tbl_pengguna SET pengguna_id = 3 WHERE pengguna_id = 0;
ALTER TABLE tbl_pengguna
    MODIFY pengguna_id INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (pengguna_id),
    ADD UNIQUE KEY uq_pengguna_username (pengguna_username);

-- ---------------------------------------------------------------------
-- tbl_kerusakan : sebelumnya tanpa PK sama sekali
-- ---------------------------------------------------------------------
ALTER TABLE tbl_kerusakan
    MODIFY id_kerusakan INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_kerusakan);

-- ---------------------------------------------------------------------
-- tbl_pengadaan : PK lama di part_number, id_pengadaan tidak auto
-- ---------------------------------------------------------------------
-- Jika part_number masih menjadi PRIMARY KEY, lepaskan dulu:
-- ALTER TABLE tbl_pengadaan DROP PRIMARY KEY;
ALTER TABLE tbl_pengadaan
    MODIFY id_pengadaan INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_pengadaan),
    ADD UNIQUE KEY uq_part_number (part_number);

-- ---------------------------------------------------------------------
-- tbl_service_report : PK lama di no_report, id_report tidak auto
-- ---------------------------------------------------------------------
-- Jika no_report masih menjadi PRIMARY KEY, lepaskan dulu:
-- ALTER TABLE tbl_service_report DROP PRIMARY KEY;
ALTER TABLE tbl_service_report
    MODIFY id_report INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_report),
    ADD UNIQUE KEY uq_no_report (no_report);

-- ---------------------------------------------------------------------
-- Tabel lain yang id-nya harus auto increment
-- ---------------------------------------------------------------------
ALTER TABLE tbl_arsip
    MODIFY id_arsip INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_arsip);

ALTER TABLE tbl_incoming
    MODIFY id_incoming INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_incoming);

ALTER TABLE tbl_outgoing
    MODIFY id_outgoing INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_outgoing);

ALTER TABLE tbl_desain_zebra
    MODIFY id_zebra INT NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (id_zebra);

-- ---------------------------------------------------------------------
-- Cek hasil
-- ---------------------------------------------------------------------
SELECT table_name, column_name, column_key, extra
FROM information_schema.columns
WHERE table_schema = DATABASE()
  AND extra LIKE '%auto_increment%'
ORDER BY table_name;
