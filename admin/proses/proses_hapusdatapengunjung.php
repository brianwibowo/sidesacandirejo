<?php 
include '../../koneksi/koneksi.php';

$id = $_GET['id'];

$sql = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";

if (mysqli_query($db, $sql)) {
  echo "<script>
      alert('Hapus Data Berhasil');
      window.location.href = '../datapengunjung.php';
      </script>";
} else {
  echo "Error deleting record: " . mysqli_error($db);
}

mysqli_close($db);