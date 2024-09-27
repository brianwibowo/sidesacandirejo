<?php
include '../../koneksi/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Pertama, ambil informasi file_path dari database
$query_get_file = "SELECT file_surat FROM tb_arsip_surat_masuk WHERE id='$id'";
$result_get_file = mysqli_query($db, $query_get_file);

if ($result_get_file) {
    $data = mysqli_fetch_assoc($result_get_file);
    $file_path = $data['file_surat'];

    // Hapus record dari database
    $query_delete = "DELETE FROM tb_arsip_surat_masuk WHERE id='$id'";

    if (mysqli_query($db, $query_delete)) {
        // Jika berhasil menghapus record, hapus file dari server
        if (file_exists($file_path)) {
            unlink($file_path); // Menghapus file
        }
        echo "<script>alert('Data berhasil dihapus'); window.location='../datasuratmasuk.php';</script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "Error: " . mysqli_error($db);
}

// Tutup db
mysqli_close($db);