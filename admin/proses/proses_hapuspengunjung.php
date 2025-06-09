<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    // Delete from database
    $query = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";
    
    if (mysqli_query($db, $query)) {
        // Reorder IDs
        $query = "SET @count = 0";
        mysqli_query($db, $query);
        
        $query = "UPDATE tb_data_pengunjung SET id = @count:= @count + 1";
        mysqli_query($db, $query);
        
        $query = "ALTER TABLE tb_data_pengunjung AUTO_INCREMENT = 1";
        mysqli_query($db, $query);
        
        echo "<script>alert('Data berhasil dihapus!'); window.location='../datapengunjung.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datapengunjung.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location='../datapengunjung.php';</script>";
}

mysqli_close($db);
?> 