<?php
$server   = "localhost";
$username = "root";
$password = "";
$database = "db_surat";

// Koneksi ke database
$koneksi = mysqli_connect($server, $username, $password, $database);

// Ambil ID dari URL
$id = $_GET['id'];

// Pertama, ambil informasi file_path dari database
$query_get_file = "SELECT file_surat FROM tb_arsip_surat_masuk WHERE id='$id'";
$result_get_file = mysqli_query($koneksi, $query_get_file);

if ($result_get_file) {
    $data = mysqli_fetch_assoc($result_get_file);
    $file_path = $data['file_surat'];

    // Hapus record dari database
    $query_delete = "DELETE FROM tb_arsip_surat_masuk WHERE id='$id'";

    if (mysqli_query($koneksi, $query_delete)) {
        // Jika berhasil menghapus record, hapus file dari server
        if (file_exists($file_path)) {
            unlink($file_path); // Menghapus file
        }
        echo "Data surat masuk dan file berhasil dihapus.";
        header("Location: ../datasuratmasuk.php"); // Redirect ke halaman daftar surat
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    echo "Error: " . mysqli_error($koneksi);
}

// Tutup koneksi
mysqli_close($koneksi);
?>