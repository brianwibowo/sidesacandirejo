<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $No = mysqli_real_escape_string($db, $_GET['id']);
    
    // Get file name before deleting
    $query = "SELECT file_surat FROM tb_arsip_surat_masuk WHERE No = '$No'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    
    // Delete file if it exists
    $upload_dir = '../uploads/surat_masuk/';
    if (!empty($data['file_surat']) && file_exists($upload_dir . $data['file_surat'])) {
        @unlink($upload_dir . $data['file_surat']);
    }
    
    // Delete from database
    $query = "DELETE FROM tb_arsip_surat_masuk WHERE No = '$No'";
    
    if (mysqli_query($db, $query)) {
        // Reorder IDs
        $query = "SET @count = 0";
        mysqli_query($db, $query);
        
        $query = "UPDATE tb_arsip_surat_masuk SET No = @count:= @count + 1";
        mysqli_query($db, $query);
        
        $query = "ALTER TABLE tb_arsip_surat_masuk AUTO_INCREMENT = 1";
        mysqli_query($db, $query);
        
        echo "<script>alert('Data berhasil dihapus!'); window.location='../datasuratmasuk.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datasuratmasuk.php';</script>";
    }
} else {
    echo "<script>alert('Nomor tidak valid!'); window.location='../datasuratmasuk.php';</script>";
}

mysqli_close($db);