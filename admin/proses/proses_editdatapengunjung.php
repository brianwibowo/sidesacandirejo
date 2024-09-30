<?php
session_start();
include '../../koneksi/koneksi.php';
$id = $_POST['id'];
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

$query = "UPDATE tb_data_pengunjung SET 
            pilihan_paket_wisata = '$pilihan', 
            jenis_wisatawan = '$jenis', 
            kota = '$kota', 
            negara = '$negara', 
            nama = '$nama', 
            jenis_kelamin = '$kelamin', 
            usia = '$usia', 
            agen_wisata = '$agen_wisata' WHERE id='$id'";

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil diedit!'); window.location='../datapengunjung.php';</script>";
} else{
    echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datapengunjung.php';</script>";
}