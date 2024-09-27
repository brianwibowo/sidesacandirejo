<?php 

include '../../koneksi/koneksi.php';

$kode_data = $_POST['kode_data'];
$nama_pemilik = $_POST['nama_pemilik'];
$nama_usaha = $_POST['nama_usaha'];
$legalitas = $_POST['legalitas_usaha'];

$query = "INSERT INTO tb_data_mitra (kode_data, nama_pemilik, nama_usaha, legalitas_usaha)
  VALUES ('$kode_data', '$nama_pemilik', '$nama_usaha', '$legalitas')";

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil disimpan!'); window.location='../datamitra.php';</script>";
} else {
    echo "Error: " . mysqli_error($db);
}