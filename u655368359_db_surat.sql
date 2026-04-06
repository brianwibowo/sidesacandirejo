-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Mar 2026 pada 04.35
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `u655368359_db_surat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `username_admin` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `last_active` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `nama_admin`, `username_admin`, `password`, `gambar`, `role`, `last_active`) VALUES
(1, 'pengelola', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin.jpg', 'superadmin', '2026-03-11 12:58:06'),
(2, 'admin2', 'admin2', '315f166c5aca63a157f7d41007675cb44a948b33', 'admin2.png', 'admin', NULL),
(3, 'Desa Wisata Candirejo', 'admin123', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'admin123.png', 'admin', NULL),
(4, 'Kirom', 'Kirom123', 'd38080aa37fc78572d7bb81cf5422f0e23ec6d0c', 'admin2.png', 'superadmin', '2026-03-12 09:35:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_arsip_surat_keluar`
--

CREATE TABLE `tb_arsip_surat_keluar` (
  `No` int NOT NULL,
  `nomor_surat` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `perihal` varchar(200) NOT NULL,
  `tempat_acara` varchar(150) DEFAULT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `file_surat` varchar(255) NOT NULL,
  `lampiran_absensi` text DEFAULT NULL,
  `lampiran_notulen` text DEFAULT NULL,
  `dokumentasi_foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_arsip_surat_keluar`
--

INSERT INTO `tb_arsip_surat_keluar` (`No`, `nomor_surat`, `tanggal_keluar`, `penerima`, `perihal`, `keterangan`, `file_surat`) VALUES
(1, '045/KDWC/X/2024', '2024-10-16', 'Pengeelola KOperasi Desa WIsata Candirejo', 'Undangan Sosialisasi SIDESA ', 'Dibuat dari fitur Buat surat', '../uploads/Surat_045/KDWC/X/2024.pdf'),
(2, '005/SK-SF/ECO/2024', '2024-10-10', 'Yth., Dr. Ir. Rahmat Darmawan Kepala Departemen Pertanian Institut Teknologi Pertanian ', ' Permohonan Kerjasama Penelitian Smart Farming', 'Dibuat dari fitur Buat surat', '../uploads/Surat_005/SK-SF/ECO/2024.pdf'),
(3, '005/SK-SF/ECO/2024', '2024-10-10', 'Yth., Dr. Ir. Rahmat Darmawan ', ' Permohonan Kerjasama Penelitian Smart Farming', 'Dibuat dari fitur Buat surat', '../uploads/Surat_005/SK-SF/ECO/2024.pdf'),
(4, '123', '2025-05-08', 'test', 'test', 'testing', '../uploads/test_2025-05-08_16-54-33.pdf'),
(5, '034/V/KDWC/2025', '2025-05-19', 'Pemandu Lokal', 'UNDANGAN', 'Dibuat dari fitur Buat surat', '../uploads/Surat_034/V/KDWC/2025.pdf'),
(6, '12', '2025-05-29', '12', '123', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(7, '12', '2025-05-29', '12', '123', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(8, '12', '2025-05-29', 'Bapak/Ibu Dekan Fakultas Teknik UNNES di Tempat', 'Penampilan Boxing antar Kelas', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(9, '12', '2025-05-29', 'Bapak/Ibu Dekan Fakultas Teknik UNNES di Tempat', 'Penampilan Boxing antar Kelas', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(10, '12', '2025-05-16', '12', '12', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(11, 'x', '2025-06-07', 'z', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_x.pdf'),
(12, 'x', '2025-06-07', 'z', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_x.pdf'),
(13, 'x', '2025-06-07', 'z', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_x.pdf'),
(14, 'a', '2025-06-07', 'c', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_a.pdf'),
(15, 'a', '2025-06-07', 'c', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_a.pdf'),
(16, '907999', '2025-06-07', 'cz', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_907999.pdf'),
(17, '907999', '2025-06-07', 'cz', 'x', 'Dibuat dari fitur Buat surat', '../uploads/Surat_907999.pdf'),
(18, 'x', '2025-06-11', 'z', 'Surat Keterangan', 'Dibuat dari fitur Buat surat keterangan', '../uploads/Surat_Keterangan_x.pdf'),
(19, 's', '2025-06-08', 's', '', 'Dibuat dari fitur Buat surat', '../uploads/Surat_s.pdf'),
(20, 's', '2025-06-08', 's', '', 'Dibuat dari fitur Buat surat', '../uploads/Surat_s.pdf'),
(21, 'z', '2025-06-11', 'x', 'Surat Keterangan', 'Dibuat dari fitur Buat surat keterangan', '../uploads/Surat_Keterangan_z.pdf'),
(22, 'x', '2025-06-08', 'c', 'Surat Keterangan', 'Dibuat dari fitur Buat surat keterangan', '../uploads/Surat_Keterangan_x.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_arsip_surat_masuk`
--

CREATE TABLE `tb_arsip_surat_masuk` (
  `No` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `tanggal_terima` varchar(50) NOT NULL,
  `tanggal_surat` varchar(50) NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `penerima_surat` varchar(100) DEFAULT NULL,
  `disposisi` text DEFAULT NULL,
  `perihal` varchar(200) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_surat` varchar(255) NOT NULL,
  `lampiran_foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_arsip_surat_masuk`
--

INSERT INTO `tb_arsip_surat_masuk` (`No`, `nomor_surat`, `tanggal_terima`, `tanggal_surat`, `pengirim`, `penerima_surat`, `disposisi`, `perihal`, `keterangan`, `file_surat`, `lampiran_foto`) VALUES
(1, '05/514/19/2025', '2025-05-19', '2025-05-16', 'Disparpora Kab.Magelang', NULL, NULL, 'UNDANGAN', 'Sosialisasi Inovasi Daerah', '../uploads/disparpora_kab.magelang_2025-05-16_13-13-15.pdf', NULL),
(2, 'B/UND/135/PM.01.00/D.4.3/2025', '2025-05-19', '2025-05-19', 'Kemenpar - Deputi Bidang Pemasaran', NULL, NULL, 'Undangan Rapat', 'Promosi Edu Trip', '../uploads/kemenpar_-_deputi_bidang_pemasaran_2025-05-19_13-23-00.pdf', NULL),
(3, '500.13.2.3/912/2025', '2025-05-21', '2025-05-07', 'Dinas Kepemudaan, Olahraga, dan Pariwisata - Prov.Jateng', NULL, NULL, 'Permintaan Peserta Kegiatan Usaha Pondok Wisata/Homestay Kabupaten Magelang', 'Permohonan peserta homestay 2 orang dari Desa Wisata Candirejo.', '../uploads/dinas_kepemudaan,_olahraga,_dan_pariwisata_-_prov.jateng_2025-05-07_06-07-57.pdf', NULL),
(4, '4446/UN1/FSP.1/AKD/TA/2025', '2025-05-22', '2025-05-22', 'UNIVERSITAS GADJAH MADA - FAKULTAS ILMU SOSIAL DAN ILMU POLITIK', NULL, NULL, 'Izin Penelitian', 'a.n Andrianto Setiawan', '../uploads/universitas_gadjah_mada_-_fakultas_ilmu_sosial_dan_ilmu_politik_2025-05-22_06-09-39.pdf', NULL),
(5, '09999/AB/2026', '2026-03-11', '2026-03-11', 'UNNES', 'Desa Candirejo', 'Kepala Bagian', 'Masukann', 'Mengubah', '../uploads/unnes_2026-03-11_04-29-41.pdf', '[\"unnes_foto0_2026-03-11_04-29-41.webp\",\"unnes_foto1_2026-03-11_04-29-41.webp\"]');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_buat_surat`
--

CREATE TABLE `tb_buat_surat` (
  `id` int NOT NULL,
  `nomor_surat` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `kop_surat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lampiran` text COLLATE utf8mb4_general_ci,
  `perihal` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `kepada` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pembuka` text COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `penutup` text COLLATE utf8mb4_general_ci NOT NULL,
  `penandatangan_surat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_mitra`
--

CREATE TABLE `tb_data_mitra` (
  `id` int NOT NULL,
  `nama_pemilik` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_usaha` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_telp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `legalitas_usaha` text COLLATE utf8mb4_general_ci NOT NULL,
  `bukti_legalitas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_kegiatan` text COLLATE utf8mb4_general_ci,
  `kategori_usaha` enum('UMKM','Local Guide','Catering','Dokar','Homestay','Kerajinan') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_mitra`
--

INSERT INTO `tb_data_mitra` (`id`, `nama_pemilik`, `nama_usaha`, `alamat`, `nomor_telp`, `legalitas_usaha`, `bukti_legalitas`, `foto_kegiatan`, `kategori_usaha`) VALUES
(1, 'Agus Santoso', 'Food', '', '', 'PIRT', '../uploads/food_12-00-22.pdf', NULL, NULL),
(2, 'Apriansyah Wibowo', 'Pembuatan Website', '', '', 'NIB', '../uploads/pembuatan_website_18-06-16.pdf', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_pengunjung`
--

CREATE TABLE `tb_data_pengunjung` (
  `id` int NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `pilihan_paket_wisata` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Kode paket utama, misal: cycling_tour, meal_only, dll.',
  `opsi_makan_tour` enum('without_lunch','with_lunch') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Opsi makan untuk paket tour (cycling, dokar, walking)',
  `jenis_makanan_paket` enum('breakfast','lunch','dinner') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Jenis makanan untuk paket meal_only',
  `opsi_cooking_lesson` enum('lesson_only','lesson_with_tour') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Opsi untuk paket cooking_lesson',
  `jenis_wisatawan` enum('Domestik','Mancanegara') COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `negara` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `agen_wisata` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pax` int DEFAULT '1',
  `driver_agent_guide` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Driver atau Agent Guide',
  `local_guide` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Local Guide',
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_pengunjung`
--

INSERT INTO `tb_data_pengunjung` (`id`, `tanggal_kunjungan`, `pilihan_paket_wisata`, `opsi_makan_tour`, `jenis_makanan_paket`, `opsi_cooking_lesson`, `jenis_wisatawan`, `kota`, `negara`, `nama`, `agen_wisata`, `pax`, `driver_agent_guide`, `local_guide`, `foto`) VALUES
(1, '2020-05-20', 'cooking_lesson', NULL, NULL, 'lesson_with_tour', 'Domestik', 'semarang', NULL, 'Nizar Arhamni', 'arhamcorp', 2, 'Belum Ada', 'Belum Ada', '6845bfd96ff86.jpeg'),
(2, '2020-06-20', 'traditional_dance', NULL, NULL, NULL, 'Domestik', 'semarang', NULL, 'Nizar', 'arhamcorp', 3, 'Belum Ada', 'Belum Ada', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_pengurus`
--

CREATE TABLE `tb_data_pengurus` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_ktp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `periode` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `foto_ktp` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pas_foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_pengurus`
--

INSERT INTO `tb_data_pengurus` (`id`, `nama`, `no_ktp`, `jabatan`, `periode`, `alamat`, `no_telp`, `foto_ktp`, `pas_foto`) VALUES
(1, 'nizar chuy', '90920', 'CEO', '2022-2025', 'gang berekor', '028032803', '1749408894_—Pngtree—graphic default avatar_5938131.png', '1749408894_—Pngtree—graphic default avatar_5938131.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_penjualan_usaha`
--

CREATE TABLE `tb_data_penjualan_usaha` (
  `id` int NOT NULL,
  `produk` enum('Paket wisata','Listrik','Pulsa') COLLATE utf8mb4_general_ci NOT NULL,
  `paket_wisata` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS ((`jumlah` * `harga`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_penjualan_usaha`
--

INSERT INTO `tb_data_penjualan_usaha` (`id`, `produk`, `paket_wisata`, `jumlah`, `harga`) VALUES
(1, 'Pulsa', 'Paket Pelajar - Live In Candirejo', 2, 22000.00),
(2, 'Pulsa', '', 123, 2000.00),
(3, 'Listrik', '', 2, 50000.00),
(4, 'Paket wisata', 'Paket Pelajar - Live In Candirejo', 3, 350000.00),
(5, 'Listrik', '', 2, 20000.00);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username_admin` (`username_admin`);

--
-- Indeks untuk tabel `tb_arsip_surat_keluar`
--
ALTER TABLE `tb_arsip_surat_keluar`
  ADD PRIMARY KEY (`No`);

--
-- Indeks untuk tabel `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  ADD PRIMARY KEY (`No`);

--
-- Indeks untuk tabel `tb_buat_surat`
--
ALTER TABLE `tb_buat_surat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_data_mitra`
--
ALTER TABLE `tb_data_mitra`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `tb_data_pengunjung`
--
ALTER TABLE `tb_data_pengunjung`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `tb_data_pengurus`
--
ALTER TABLE `tb_data_pengurus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_data_penjualan_usaha`
--
ALTER TABLE `tb_data_penjualan_usaha`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tb_arsip_surat_keluar`
--
ALTER TABLE `tb_arsip_surat_keluar`
  MODIFY `No` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  MODIFY `No` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tb_buat_surat`
--
ALTER TABLE `tb_buat_surat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_data_mitra`
--
ALTER TABLE `tb_data_mitra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_data_pengunjung`
--
ALTER TABLE `tb_data_pengunjung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `tb_data_pengurus`
--
ALTER TABLE `tb_data_pengurus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_data_penjualan_usaha`
--
ALTER TABLE `tb_data_penjualan_usaha`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
