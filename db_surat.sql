-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2024 at 06:10 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_surat`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `username_admin` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `nama_admin`, `username_admin`, `password`, `gambar`) VALUES
(1, 'admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin.jpg'),
(2, 'admin2', 'admin2', '315f166c5aca63a157f7d41007675cb44a948b33', 'admin2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_arsip_surat_keluar`
--

CREATE TABLE `tb_arsip_surat_keluar` (
  `id` int NOT NULL,
  `nomor_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `penerima` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `perihal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_arsip_surat_masuk`
--

CREATE TABLE `tb_arsip_surat_masuk` (
  `id` int NOT NULL,
  `nomor_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_terima` date NOT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `perihal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_buat_surat`
--

CREATE TABLE `tb_buat_surat` (
  `id` int NOT NULL,
  `nomor_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kop_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lampiran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `perihal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `kepada` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pembuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `penutup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `penandatangan_surat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_data_mitra`
--

CREATE TABLE `tb_data_mitra` (
  `id` int NOT NULL,
  `nama_pemilik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_usaha` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `legalitas_usaha` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bukti_legalitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_data_pengunjung`
--

CREATE TABLE `tb_data_pengunjung` (
  `id` int NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `pilihan_paket_wisata` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_wisatawan` enum('Domestik','Mancanegara') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `negara` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `usia` int NOT NULL,
  `agen_wisata` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_data_penjualan_usaha`
--

CREATE TABLE `tb_data_penjualan_usaha` (
  `id` int NOT NULL,
  `produk` enum('Paket wisata','Listrik','Pulsa') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `paket_wisata` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS ((`jumlah` * `harga`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username_admin` (`username_admin`);

--
-- Indexes for table `tb_arsip_surat_keluar`
--
ALTER TABLE `tb_arsip_surat_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_buat_surat`
--
ALTER TABLE `tb_buat_surat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_data_mitra`
--
ALTER TABLE `tb_data_mitra`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tb_data_pengunjung`
--
ALTER TABLE `tb_data_pengunjung`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tb_data_penjualan_usaha`
--
ALTER TABLE `tb_data_penjualan_usaha`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_arsip_surat_keluar`
--
ALTER TABLE `tb_arsip_surat_keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_arsip_surat_masuk`
--
ALTER TABLE `tb_arsip_surat_masuk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_buat_surat`
--
ALTER TABLE `tb_buat_surat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_data_mitra`
--
ALTER TABLE `tb_data_mitra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_data_pengunjung`
--
ALTER TABLE `tb_data_pengunjung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_data_penjualan_usaha`
--
ALTER TABLE `tb_data_penjualan_usaha`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
