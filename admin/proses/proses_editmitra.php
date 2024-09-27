<?php 
include '../../koneksi/koneksi.php';

$id = $_POST['id'];
$kode_data = $_POST['kode_data'];
$nama_pemilik = $_POST['nama_pemilik'];
$nama_usaha = $_POST['nama_usaha'];
$legalitas_usaha = $_POST['legalitas_usaha'];


$sql = "UPDATE tb_data_mitra SET kode_data = '$kode_data', nama_pemilik = '$nama_pemilik', nama_usaha = '$nama_usaha', legalitas_usaha = '$legalitas_usaha' WHERE id = '$id'";

if ($db->query($sql) === TRUE) {
  echo "<script>alert('Data berhasil diedit');; window.location='../datamitra.php';</script>";
} else {
  echo "Error updating record: " . $db->error;
}