<?php
session_start();
include '../../koneksi/koneksi.php';

$kode_data = $_POST['kode_data'];
$jenis = $_POST['jenis_produk'];
$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];


$query = "INSERT INTO tb_data_penjualan_usaha (kode_data, produk, jumlah, harga)
          VALUES ('$kode_data', '$jenis', '$jumlah', '$harga')";

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil disimpan!'); window.location='../datapenjualanusaha.php';</script>";
} else{
    echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datapengunjung.php';</script>";
}