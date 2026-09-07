-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Apr 2026 pada 16.44
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
-- Database: `u655368359_db_surat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(11) NOT NULL,
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
(1, 'pengelola', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin.jpg', 'superadmin', '2026-04-11 21:39:19'),
(2, 'admin2', 'admin2', '315f166c5aca63a157f7d41007675cb44a948b33', 'admin2.png', 'admin', NULL),
(3, 'Desa Wisata Candirejo', 'admin123', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'admin123.png', 'admin', NULL),
(4, 'Kirom', 'Kirom123', 'd38080aa37fc78572d7bb81cf5422f0e23ec6d0c', 'admin2.png', 'superadmin', '2026-04-09 10:52:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_arsip_surat_keluar`
--

CREATE TABLE `tb_arsip_surat_keluar` (
  `No` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `jenis_surat` varchar(20) NOT NULL DEFAULT 'keterangan',
  `perihal` varchar(200) NOT NULL,
  `tempat_acara` varchar(150) DEFAULT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `jam_kegiatan` time DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `file_surat` varchar(255) NOT NULL,
  `lampiran_absensi` text DEFAULT NULL,
  `lampiran_notulen` text DEFAULT NULL,
  `dokumentasi_foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_booking`
--

CREATE TABLE `tb_booking` (
  `id` int(11) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `pilihan_paket_wisata` varchar(50) NOT NULL COMMENT 'Kode paket, misal: cycling_tour, meal_only, dll.',
  `opsi_makan_tour` enum('without_lunch','with_lunch') DEFAULT NULL,
  `jenis_makanan_paket` enum('breakfast','lunch','dinner') DEFAULT NULL,
  `opsi_cooking_lesson` enum('lesson_only','lesson_with_tour') DEFAULT NULL,
  `opsi_gamelan` varchar(50) DEFAULT NULL,
  `jenis_wisatawan` enum('Domestik','Mancanegara') NOT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `negara` varchar(100) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `pax` int(11) NOT NULL DEFAULT 1,
  `agen_wisata` varchar(100) DEFAULT NULL,
  `driver_agent_guide` varchar(100) DEFAULT NULL,
  `local_guide` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','checkin','tidak_hadir') NOT NULL DEFAULT 'pending',
  `id_pengunjung` int(11) DEFAULT NULL COMMENT 'FK ke tb_data_pengunjung.id setelah check-in',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `checkin_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel booking pengunjung Desa Wisata Candirejo';

--
-- Dumping data untuk tabel `tb_booking`
--

INSERT INTO `tb_booking` (`id`, `tanggal_kunjungan`, `pilihan_paket_wisata`, `opsi_makan_tour`, `jenis_makanan_paket`, `opsi_cooking_lesson`, `opsi_gamelan`, `jenis_wisatawan`, `kota`, `negara`, `nama`, `pax`, `agen_wisata`, `driver_agent_guide`, `local_guide`, `status`, `id_pengunjung`, `created_at`, `checkin_at`) VALUES
(1, '2026-04-11', 'cycling_tour', 'with_lunch', NULL, NULL, NULL, 'Domestik', 'Yogyakarta', NULL, 'Rombongan SMA Taruna', 12, 'EduTour Jogja', 'Belum Ada', 'Belum Ada', 'checkin', 34, '2026-04-11 17:18:27', '2026-04-11 21:10:47'),
(2, '2026-04-11', 'cooking_lesson', NULL, NULL, NULL, NULL, 'Mancanegara', NULL, NULL, 'John Smith', 2, NULL, 'Belum Ada', 'Belum Ada', 'tidak_hadir', NULL, '2026-04-11 17:18:27', NULL),
(3, '2026-04-11', 'traditional_dance', NULL, NULL, NULL, NULL, 'Domestik', 'Semarang', NULL, 'Keluarga Budi', 5, NULL, 'Pak Arief', 'Bu Sari', 'checkin', 30, '2026-04-11 17:18:27', '2026-04-11 17:21:03'),
(4, '2026-04-11', 'meal_only', NULL, NULL, NULL, NULL, 'Domestik', 'Magelang', NULL, 'Komunitas Hiking', 8, 'WisataJawa.id', 'Belum Ada', 'Belum Ada', 'checkin', NULL, '2026-04-11 17:18:27', NULL),
(5, '2026-04-12', 'dokar_tour', 'without_lunch', NULL, NULL, NULL, 'Mancanegara', NULL, NULL, 'Maria Garcia', 3, 'Go Kedu', 'Belum Ada', 'Belum Ada', 'pending', NULL, '2026-04-11 17:18:27', NULL),
(6, '2026-04-12', 'gamelan_class', 'with_lunch', NULL, NULL, NULL, 'Domestik', 'Klaten', NULL, 'Grup Musisi Muda', 6, NULL, 'Belum Ada', 'Belum Ada', 'pending', NULL, '2026-04-11 17:18:27', NULL),
(7, '2026-04-15', 'walking_tour', NULL, NULL, NULL, NULL, 'Mancanegara', NULL, NULL, 'QIROM', 1, 'Sami', 'Belum Ada', 'Belum Ada', 'pending', NULL, '2026-04-11 20:22:28', NULL),
(8, '2026-04-15', 'walking_tour', NULL, NULL, NULL, NULL, 'Mancanegara', NULL, NULL, 'QIROM', 1, 'Sami', 'Belum Ada', 'Belum Ada', 'pending', NULL, '2026-04-11 20:22:28', NULL),
(9, '2026-04-11', 'meal_only', NULL, NULL, NULL, NULL, 'Mancanegara', NULL, NULL, 'Sahrul', 1, NULL, 'Belum Ada', 'Belum Ada', 'pending', NULL, '2026-04-11 20:22:59', NULL),
(10, '2026-04-17', 'pelajar_live_in', NULL, NULL, NULL, NULL, 'Domestik', NULL, NULL, 'v', 1, 'n', 'h', 'f', 'pending', NULL, '2026-04-11 21:22:54', NULL),
(11, '2026-04-30', 'village_experience', NULL, NULL, NULL, NULL, 'Domestik', 'j', NULL, 'j', 1, 'm', 'Belum Ada', 'Belum Ada', 'pending', NULL, '2026-04-11 21:25:26', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_buat_surat`
--

CREATE TABLE `tb_buat_surat` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `kop_surat` varchar(255) NOT NULL,
  `lampiran` text DEFAULT NULL,
  `perihal` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `kepada` varchar(100) NOT NULL,
  `pembuka` text NOT NULL,
  `isi` text NOT NULL,
  `penutup` text NOT NULL,
  `penandatangan_surat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_mitra`
--

CREATE TABLE `tb_data_mitra` (
  `id` int(11) NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `nama_usaha` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `nomor_telp` varchar(20) NOT NULL,
  `legalitas_usaha` text NOT NULL,
  `bukti_legalitas` varchar(255) DEFAULT NULL,
  `foto_kegiatan` text DEFAULT NULL,
  `kategori_usaha` enum('UMKM','Local Guide','Catering','Dokar','Homestay','Kerajinan') DEFAULT NULL
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
  `id` int(11) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `pilihan_paket_wisata` varchar(50) NOT NULL COMMENT 'Kode paket utama, misal: cycling_tour, meal_only, dll.',
  `opsi_makan_tour` enum('without_lunch','with_lunch') DEFAULT NULL COMMENT 'Opsi makan untuk paket tour (cycling, dokar, walking)',
  `jenis_makanan_paket` enum('breakfast','lunch','dinner') DEFAULT NULL COMMENT 'Jenis makanan untuk paket meal_only',
  `opsi_cooking_lesson` enum('lesson_only','lesson_with_tour') DEFAULT NULL COMMENT 'Opsi untuk paket cooking_lesson',
  `opsi_gamelan` varchar(50) DEFAULT NULL,
  `jenis_wisatawan` enum('Domestik','Mancanegara') NOT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `negara` varchar(100) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `agen_wisata` varchar(100) DEFAULT NULL,
  `pax` int(11) DEFAULT 1,
  `driver_agent_guide` varchar(100) NOT NULL COMMENT 'Driver atau Agent Guide',
  `local_guide` varchar(100) NOT NULL COMMENT 'Local Guide',
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_pengunjung`
--

INSERT INTO `tb_data_pengunjung` (`id`, `tanggal_kunjungan`, `pilihan_paket_wisata`, `opsi_makan_tour`, `jenis_makanan_paket`, `opsi_cooking_lesson`, `opsi_gamelan`, `jenis_wisatawan`, `kota`, `negara`, `nama`, `agen_wisata`, `pax`, `driver_agent_guide`, `local_guide`, `foto`) VALUES
(1, '2020-05-20', 'cooking_lesson', NULL, NULL, 'lesson_with_tour', NULL, 'Domestik', 'semarang', NULL, 'Nizar Arhamni', 'arhamcorp', 2, 'Belum Ada', 'Belum Ada', '6845bfd96ff86.jpeg'),
(34, '2026-04-11', 'cycling_tour', 'with_lunch', NULL, NULL, NULL, 'Domestik', 'Yogyakarta', NULL, 'Rombongan SMA Taruna', 'EduTour Jogja', 12, 'Belum Ada', 'Belum Ada', NULL),
(35, '2026-05-27', 'walking_tour', 'with_lunch', '', '', '', 'Domestik', 'cirebon', '', 'agies', 'qiromtour', 12, 'fahrel', 'basyam', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_pengurus`
--

CREATE TABLE `tb_data_pengurus` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `pas_foto` varchar(255) DEFAULT NULL
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
  `id` int(11) NOT NULL,
  `produk` enum('Paket wisata','Listrik','Pulsa') NOT NULL,
  `paket_wisata` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS (`jumlah` * `harga`) STORED
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
-- Indexes for dumped tables
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
-- Indeks untuk tabel `tb_booking`
--
ALTER TABLE `tb_booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tanggal` (`tanggal_kunjungan`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_pengunjung` (`id_pengunjung`);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `tb_arsip_surat_keluar`
--
ALTER TABLE `tb_arsip_surat_keluar`
  MODIFY `No` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  MODIFY `No` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_booking`
--
ALTER TABLE `tb_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tb_buat_surat`
--
ALTER TABLE `tb_buat_surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_data_mitra`
--
ALTER TABLE `tb_data_mitra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_data_pengunjung`
--
ALTER TABLE `tb_data_pengunjung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `tb_data_pengurus`
--
ALTER TABLE `tb_data_pengurus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_data_penjualan_usaha`
--
ALTER TABLE `tb_data_penjualan_usaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
