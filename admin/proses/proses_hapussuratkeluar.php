<?php
include '../../koneksi/koneksi.php';

if(isset($_GET['No'])){
    $No = $_GET['No'];
    
    // Get the file path before deleting
    $query_get_file = "SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = $No";
    $result = mysqli_query($db, $query_get_file);
    $row = mysqli_fetch_assoc($result);
    $file_path = $row['file_surat'];
    
    // Delete the record
    $query = "DELETE FROM tb_arsip_surat_keluar WHERE No = $No";
    if(mysqli_query($db, $query)){
        // Delete the file if it exists
        if(file_exists($file_path)){
            unlink($file_path);
        }
        
        // Reorder the No column
        $query_reorder = "SET @count = 0; 
                         UPDATE tb_arsip_surat_keluar SET No = @count:= @count + 1 
                         ORDER BY No;";
        mysqli_multi_query($db, $query_reorder);
        
        echo "<script>alert('Data berhasil dihapus!'); window.location='../datasuratkeluar.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datasuratkeluar.php';</script>";
    }
}

mysqli_close($db);