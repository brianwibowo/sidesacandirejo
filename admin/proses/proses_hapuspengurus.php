<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    // Get file names before deleting
    $query = "SELECT foto_ktp, pas_foto FROM tb_data_pengurus WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    
    // Delete files if they exist
    $upload_dir = '../admin/uploads/pengurus/';
    
    if (!empty($data['foto_ktp']) && file_exists($upload_dir . $data['foto_ktp'])) {
        @unlink($upload_dir . $data['foto_ktp']);
    }
    
    if (!empty($data['pas_foto']) && file_exists($upload_dir . $data['pas_foto'])) {
        @unlink($upload_dir . $data['pas_foto']);
    }
    
    // Delete from database
    $query = "DELETE FROM tb_data_pengurus WHERE id = '$id'";
    
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='../datapengurus.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datapengurus.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location='../datapengurus.php';</script>";
}

mysqli_close($db);
?> 