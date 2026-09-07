-- ==========================================================
-- SIDESA CANDIREJO - HIGH PERFORMANCE DATABASE INDEXES
-- Script Optimasi Index & Efisiensi Memori (RAM / CPU)
-- Tanggal: 2026-09-07
-- ==========================================================

-- 1. Pastikan kolom 'catatan' ada pada tabel tb_booking
ALTER TABLE `tb_booking` 
  ADD COLUMN IF NOT EXISTS `catatan` TEXT NULL AFTER `local_guide`;

-- 2. Index Performa pada Tabel `tb_data_pengunjung`
-- Menghilangkan Full Table Scan pada Analitik & Sorting Tabel Pengunjung
ALTER TABLE `tb_data_pengunjung` 
  ADD INDEX IF NOT EXISTS `idx_tgl_id` (`tanggal_kunjungan`, `id`),
  ADD INDEX IF NOT EXISTS `idx_paket` (`pilihan_paket_wisata`),
  ADD INDEX IF NOT EXISTS `idx_jenis` (`jenis_wisatawan`),
  ADD INDEX IF NOT EXISTS `idx_negara` (`negara`);

-- 3. Composite Index pada Tabel `tb_booking`
-- Menghilangkan Filesort (pengurutan di RAM) pada halaman Pending, Check-in, & Tidak Hadir
ALTER TABLE `tb_booking` 
  ADD INDEX IF NOT EXISTS `idx_status_tgl` (`status`, `tanggal_kunjungan`, `id`);

-- 4. Index pada Tabel Arsip Surat
ALTER TABLE `tb_arsip_surat_keluar` 
  ADD INDEX IF NOT EXISTS `idx_sk_tgl` (`tanggal_keluar`);

-- Verifikasi Index yang telah dibuat:
-- SHOW INDEX FROM tb_data_pengunjung;
-- SHOW INDEX FROM tb_booking;
-- SHOW INDEX FROM tb_arsip_surat_keluar;
