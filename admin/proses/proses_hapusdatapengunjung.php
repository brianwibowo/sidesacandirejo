<?php 
include '../../koneksi/koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";

if (mysqli_query($db, $sql)) {
  session_start();
  $_SESSION['notif_hapus'] = 'berhasil';
} else {
  session_start();
  $_SESSION['notif_hapus'] = 'gagal';
}

mysqli_close($db);
header('Location: ../datapengunjung.php');
exit;