<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // Ambil data foto dulu sebelum dihapus
    $foto_query = mysqli_query($db, "SELECT foto FROM tb_data_pengunjung WHERE id = '$id'");
    $foto_data = mysqli_fetch_assoc($foto_query);

    // Hapus dari database
    $query = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";
    
    if (mysqli_query($db, $query)) {

        // Hapus file foto dari folder jika ada
        if (!empty($foto_data['foto'])) {
            $foto_arr = json_decode($foto_data['foto'], true);
            if (is_array($foto_arr)) {
                // Multiple foto (JSON)
                foreach ($foto_arr as $f) {
                    $file_path = '../../admin/uploads/pengunjung/' . $f;
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            } else {
                // Single foto (data lama)
                $file_path = '../../admin/uploads/pengunjung/' . $foto_data['foto'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }

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