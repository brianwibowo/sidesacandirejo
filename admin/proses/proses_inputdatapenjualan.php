<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_produk = $_POST['produk'];
    $pilihan_paket_wisata = $jenis_produk == 'Paket Wisata' ? $_POST['pilihan_paket_wisata'] : null;
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO tb_data_penjualan_usaha (produk, paket_wisata, jumlah, harga) 
              VALUES ('$jenis_produk', '$pilihan_paket_wisata', '$jumlah', '$harga')";

    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data penjualan berhasil disimpan!'); window.location='../datapenjualanusaha.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datapenjualanusaha.php';</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='../datapenjualanusaha.php';</script>";
}

mysqli_close($db);
?>