<?php 
include '../../koneksi/koneksi.php';

$id = $_POST['id'];
$nama_pemilik = $_POST['nama_pemilik'];
$nama_usaha = $_POST['nama_usaha'];
$legalitas_usaha = $_POST['legalitas_usaha'];

$nama_file= null;
$query_get_file = "SELECT bukti_legalitas FROM tb_data_mitra WHERE id ='$id'";
$result = mysqli_query($db, $query_get_file);
$row = mysqli_fetch_assoc($result);
$file_lama = $row['bukti_legalitas'];


if(isset($_FILES['bukti_legalitas']) && $_FILES['bukti_legalitas']['error'] == UPLOAD_ERR_OK) {
  $jam = date('H-i-s');
  $nama_file = strtolower(str_replace(' ', '_', $nama_usaha)) . "_{$jam}.pdf";

  $target_dir = "../uploads/";
  $destination = $target_dir.$nama_file;

  if (move_uploaded_file($_FILES['bukti_legalitas']['tmp_name'], $destination)){
    if(!empty($file_lama) && file_exists($file_lama)){
      unlink($file_lama);
    }
    $query = "UPDATE tb_data_mitra SET nama_pemilik='$nama_pemilik', nama_usaha='$nama_usaha', legalitas_usaha='$legalitas_usaha', bukti_legalitas='$destination' WHERE id = '$id' ";
  } else {
    echo "Gagal memindahkan file";
    exit;
  }
} else {
  $query = "UPDATE tb_data_mitra SET nama_pemilik='$nama_pemilik', nama_usaha='$nama_usaha', legalitas_usaha='$legalitas_usaha' WHERE id = '$id' ";
}

if (mysqli_query($db, $query)) {
  echo "<script>alert('Data berhasil diedit'); window.location='../datamitra.php';</script>";
} else {
  echo "Error: " . mysqli_error($db);
}