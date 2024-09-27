<?php 
include '../../koneksi/koneksi.php';

$id = $_POST['id'];
$kode_data = $_POST['kode_data'];
$produk = $_POST['produk'];
$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];


$sql = "UPDATE tb_data_penjualan_usaha SET kode_data = '$kode_data', produk = '$produk', jumlah = '$jumlah', harga = '$harga' WHERE id = '$id'";

if ($db->query($sql) === TRUE) {
  echo "<script>alert('Data berhasil diedit');; window.location='../datapenjualanusaha.php';</script>";
} else {
  echo "Error updating record: " . $db->error;
}