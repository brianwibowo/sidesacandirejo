<?php
session_start();
include '../../koneksi/koneksi.php';

$kode_data = $_POST['kode_data'];
$pilihan = $_POST['pilihan_paket_wisata'];
$jenis = $_POST['jenis_wisatawan'];
$nama = $_POST['nama'];
$kelamin = $_POST['jenis_kelamin'];
$usia = $_POST['usia'];
$agen_wisata = !empty($_POST['agen_wisata']) ? $_POST['agen_wisata'] : NULL; 

if ($jenis == "Domestik") {
    $kota = $_POST['kota'];
    $negara = NULL;
} else {
    $negara = $_POST['negara'];
    $kota = NULL;
}

$query = "INSERT INTO tb_data_pengunjung (kode_data, pilihan_paket_wisata, jenis_wisatawan, kota, negara, nama, jenis_kelamin, usia, agen_wisata)
          VALUES ('$kode_data', '$pilihan', '$jenis', '$kota', '$negara', '$nama', '$kelamin', '$usia', '$agen_wisata')";

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil disimpan!'); window.location='../datapengunjung.php';</script>";
} else{
    echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datapengunjung.php';</script>";
}