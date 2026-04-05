<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $No = mysqli_real_escape_string($db, $_GET['id']);
    
    // Get file names before deleting
    $query = "SELECT file_surat, lampiran_absensi, lampiran_notulen, dokumentasi_foto FROM tb_arsip_surat_keluar WHERE No = '$No'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    
    // Delete main file if it exists
    if (!empty($data['file_surat']) && file_exists($data['file_surat'])) {
        @unlink($data['file_surat']);
    }

    // Delete related attachment files if they exist
    $lampiran_dir = '../../uploads/surat_keluar_lampiran/';
    
    $absensi_files = json_decode($data['lampiran_absensi'] ?? '[]', true);
    if (is_array($absensi_files)) {
        foreach ($absensi_files as $filename) {
            if (!empty($filename)) {
                $filePath = $lampiran_dir . $filename;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }

    $notulen_files = json_decode($data['lampiran_notulen'] ?? '[]', true);
    if (is_array($notulen_files)) {
        foreach ($notulen_files as $filename) {
            if (!empty($filename)) {
                $filePath = $lampiran_dir . $filename;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }

    $dokumentasi_files = json_decode($data['dokumentasi_foto'] ?? '[]', true);
    if (is_array($dokumentasi_files)) {
        foreach ($dokumentasi_files as $filename) {
            if (!empty($filename)) {
                $filePath = $lampiran_dir . $filename;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }
    
    // Delete from database
    $query = "DELETE FROM tb_arsip_surat_keluar WHERE No = '$No'";
    
    if (mysqli_query($db, $query)) {
        // Reorder IDs
        $query = "SET @count = 0";
        mysqli_query($db, $query);
        
        $query = "UPDATE tb_arsip_surat_keluar SET No = @count:= @count + 1";
        mysqli_query($db, $query);
        
        $query = "ALTER TABLE tb_arsip_surat_keluar AUTO_INCREMENT = 1";
        mysqli_query($db, $query);
        
        echo "<script>alert('Data berhasil dihapus!'); window.location='../datasuratkeluar.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datasuratkeluar.php';</script>";
    }
} else {
    echo "<script>alert('Nomor tidak valid!'); window.location='../datasuratkeluar.php';</script>";
}

mysqli_close($db);