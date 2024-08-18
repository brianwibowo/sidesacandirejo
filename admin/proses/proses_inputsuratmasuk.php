<?php
session_start();
include "../../koneksi/koneksi.php";// Pastikan ini mengatur $db dengan benar

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $tanggal_terima = $_POST['tanggal_terima'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $nomor_surat = $_POST['nomor_surat'];
    $pengirim = $_POST['pengirim'];
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
    $keterangan = $_POST['keterangan'];
    
    // Mengatur direktori upload
    $target_dir = "../surat_masuk/";
    $target_file = $target_dir . basename($_FILES["file_surat"]["name"]);

    // Debug informasi file
    echo '<pre>';
    print_r($_FILES);
    echo '</pre>';

    // Cek apakah file berhasil dipindahkan
    if (move_uploaded_file($_FILES["file_surat"]["tmp_name"], $target_file)) {
        echo "File ". htmlspecialchars(basename($_FILES["file_surat"]["name"])) . " berhasil diunggah.";
        
        // Menyimpan data ke database
        $query = "INSERT INTO arsip_surat_masuk (tanggal_terima, tanggal_surat, nomor_surat, pengirim, perihal, kode, keterangan, file_surat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ssssssss", $tanggal_terima, $tanggal_surat, $nomor_surat, $pengirim, $perihal, $kode, $keterangan, $_FILES["file_surat"]["name"]);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($db);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Gagal mengupload file. Error: " . $_FILES["file_surat"]["error"];
    }
    
    mysqli_close($db); // Pastikan $db sudah terdefinisi
}
?>
