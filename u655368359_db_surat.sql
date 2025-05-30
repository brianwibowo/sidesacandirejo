-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Bulan Mei 2025 pada 23.37
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
  `password` varchar(50) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `nama_admin`, `username_admin`, `password`, `gambar`) VALUES
(1, 'pengelola', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin.jpg'),
(2, 'admin2', 'admin2', '315f166c5aca63a157f7d41007675cb44a948b33', 'admin2.png'),
(3, 'Desa Wisata Candirejo', 'admin123', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'admin123.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_arsip_surat_keluar`
--

CREATE TABLE `tb_arsip_surat_keluar` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `perihal` varchar(200) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_surat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_arsip_surat_keluar`
--

INSERT INTO `tb_arsip_surat_keluar` (`id`, `nomor_surat`, `tanggal_keluar`, `penerima`, `perihal`, `kode`, `keterangan`, `file_surat`) VALUES
(9, '045/KDWC/X/2024', '2024-10-16', 'Pengeelola KOperasi Desa WIsata Candirejo', 'Undangan Sosialisasi SIDESA ', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_045/KDWC/X/2024.pdf'),
(10, '005/SK-SF/ECO/2024', '2024-10-10', 'Yth., Dr. Ir. Rahmat Darmawan Kepala Departemen Pertanian Institut Teknologi Pertanian ', ' Permohonan Kerjasama Penelitian Smart Farming', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_005/SK-SF/ECO/2024.pdf'),
(13, '005/SK-SF/ECO/2024', '2024-10-10', 'Yth., Dr. Ir. Rahmat Darmawan ', ' Permohonan Kerjasama Penelitian Smart Farming', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_005/SK-SF/ECO/2024.pdf'),
(15, '123', '2025-05-08', 'test', 'test', 'test123', 'testing', '../uploads/test_2025-05-08_16-54-33.pdf'),
(17, '034/V/KDWC/2025', '2025-05-19', 'Pemandu Lokal', 'UNDANGAN', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_034/V/KDWC/2025.pdf'),
(18, '12', '2025-05-29', '12', '123', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(19, '12', '2025-05-29', '12', '123', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(20, '12', '2025-05-29', 'Bapak/Ibu Dekan Fakultas Teknik UNNES di Tempat', 'Penampilan Boxing antar Kelas', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(21, '12', '2025-05-29', 'Bapak/Ibu Dekan Fakultas Teknik UNNES di Tempat', 'Penampilan Boxing antar Kelas', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(22, '12', '2025-05-16', '12', '12', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf'),
(23, '12', '2025-05-16', '12', '12', '-', 'Dibuat dari fitur Buat surat', '../uploads/Surat_12.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_arsip_surat_masuk`
--

CREATE TABLE `tb_arsip_surat_masuk` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `tanggal_terima` varchar(50) NOT NULL,
  `tanggal_surat` varchar(50) NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `penerima_surat` varchar(100) DEFAULT NULL,
  `disposisi` text DEFAULT NULL,
  `perihal` varchar(200) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_surat` varchar(255) NOT NULL,
  `lampiran_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_arsip_surat_masuk`
--

INSERT INTO `tb_arsip_surat_masuk` (`id`, `nomor_surat`, `tanggal_terima`, `tanggal_surat`, `pengirim`, `penerima_surat`, `disposisi`, `perihal`, `kode`, `keterangan`, `file_surat`, `lampiran_foto`) VALUES
(10, '05/514/19/2025', '2025-05-19', '2025-05-16', 'Disparpora Kab.Magelang', NULL, NULL, 'UNDANGAN', 'UND', 'Sosialisasi Inovasi Daerah', '../uploads/disparpora_kab.magelang_2025-05-16_13-13-15.pdf', NULL),
(11, 'B/UND/135/PM.01.00/D.4.3/2025', '2025-05-19', '2025-05-19', 'Kemenpar - Deputi Bidang Pemasaran', NULL, NULL, 'Undangan Rapat', 'UND', 'Promosi Edu Trip', '../uploads/kemenpar_-_deputi_bidang_pemasaran_2025-05-19_13-23-00.pdf', NULL),
(12, '500.13.2.3/912/2025', '2025-05-21', '2025-05-07', 'Dinas Kepemudaan, Olahraga, dan Pariwisata - Prov.Jateng', NULL, NULL, 'Permintaan Peserta Kegiatan Usaha Pondok Wisata/Homestay Kabupaten Magelang', 'UND', 'Permohonan peserta homestay 2 orang dari Desa Wisata Candirejo.', '../uploads/dinas_kepemudaan,_olahraga,_dan_pariwisata_-_prov.jateng_2025-05-07_06-07-57.pdf', NULL),
(13, '4446/UN1/FSP.1/AKD/TA/2025', '2025-05-22', '2025-05-22', 'UNIVERSITAS GADJAH MADA - FAKULTAS ILMU SOSIAL DAN ILMU POLITIK', NULL, NULL, 'Izin Penelitian', 'IZP', 'a.n Andrianto Setiawan', '../uploads/universitas_gadjah_mada_-_fakultas_ilmu_sosial_dan_ilmu_politik_2025-05-22_06-09-39.pdf', NULL);

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
  `foto_kegiatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_mitra`
--

INSERT INTO `tb_data_mitra` (`id`, `nama_pemilik`, `nama_usaha`, `alamat`, `nomor_telp`, `legalitas_usaha`, `bukti_legalitas`, `foto_kegiatan`) VALUES
(8, 'Agus Santoso', 'Food', '', '', 'PIRT', '../uploads/food_12-00-22.pdf', NULL),
(10, 'Apriansyah Wibowo', 'Pembuatan Website', '', '', 'NIB', '../uploads/pembuatan_website_18-06-16.pdf', NULL);

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
  `jenis_wisatawan` enum('Domestik','Mancanegara') NOT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `negara` varchar(100) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `agen_wisata` varchar(100) DEFAULT NULL,
  `pax` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_data_pengunjung`
--

INSERT INTO `tb_data_pengunjung` (`id`, `tanggal_kunjungan`, `pilihan_paket_wisata`, `opsi_makan_tour`, `jenis_makanan_paket`, `opsi_cooking_lesson`, `jenis_wisatawan`, `kota`, `negara`, `nama`, `agen_wisata`, `pax`) VALUES
(23, '2025-05-29', 'walking_tour', 'with_lunch', NULL, NULL, 'Domestik', 'semarang', NULL, 'arham', 'arhamcorp', 2),
(25, '2025-06-02', 'traditional_dance', NULL, NULL, NULL, 'Domestik', 'semarang', NULL, 'Nizar Arhamni', 'arhamcorp', 3);

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
(5, 'Pulsa', 'Paket Pelajar - Live In Candirejo', 2, 22000.00),
(6, 'Pulsa', '', 123, 2000.00),
(7, 'Listrik', '', 2, 50000.00),
(8, 'Paket wisata', 'Paket Pelajar - Live In Candirejo', 3, 350000.00),
(9, 'Listrik', '', 2, 20000.00);

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
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_arsip_surat_keluar`
--
ALTER TABLE `tb_arsip_surat_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tb_buat_surat`
--
ALTER TABLE `tb_buat_surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_data_mitra`
--
ALTER TABLE `tb_data_mitra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tb_data_pengunjung`
--
ALTER TABLE `tb_data_pengunjung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `tb_data_penjualan_usaha`
--
ALTER TABLE `tb_data_penjualan_usaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
