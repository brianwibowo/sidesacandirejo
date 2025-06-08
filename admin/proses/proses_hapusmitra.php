<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    // Get file name before deleting
    $query = "SELECT logo FROM tb_data_mitra WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    
    // Delete file if it exists
    $upload_dir = '../uploads/mitra/';
    if (!empty($data['logo']) && file_exists($upload_dir . $data['logo'])) {
        @unlink($upload_dir . $data['logo']);
    }
    
    // Delete from database
    $query = "DELETE FROM tb_data_mitra WHERE id = '$id'";
    
    if (mysqli_query($db, $query)) {
        // Reorder IDs
        $query = "SET @count = 0";
        mysqli_query($db, $query);
        
        $query = "UPDATE tb_data_mitra SET id = @count:= @count + 1";
        mysqli_query($db, $query);
        
        $query = "ALTER TABLE tb_data_mitra AUTO_INCREMENT = 1";
        mysqli_query($db, $query);
        
        echo "<script>alert('Data berhasil dihapus!'); window.location='../datamitra.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datamitra.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location='../datamitra.php';</script>";
}

mysqli_close($db);