<?php
include '../../koneksi/koneksi.php';

$id = $_GET['id'];

$query_get_file = "SELECT file_surat FROM tb_arsip_surat_keluar WHERE id='$id'";
$result_get_file = mysqli_query($db, $query_get_file);

if ($result_get_file) {
    $data = mysqli_fetch_assoc($result_get_file);
    $file_path = $data['file_surat'];

    $query_delete = "DELETE FROM tb_arsip_surat_keluar WHERE id='$id'";

    if (mysqli_query($db, $query_delete)) {
        if (file_exists($file_path)) {
            unlink($file_path); 
        }
        echo "<script>alert('Data berhasil dihapus'); window.location='../datasuratkeluar.php'; </script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "Error: " . mysqli_error($db);
}

mysqli_close($db);