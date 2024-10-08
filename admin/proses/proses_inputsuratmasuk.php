<?php
// Menghubungkan ke database

include '../../koneksi/koneksi.php';


// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $tanggal_terima = $_POST['tanggal_terima'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $nomor_surat = $_POST['nomor_surat'];
    $pengirim = $_POST['pengirim'];
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
    $keterangan = $_POST['keterangan'];

    $tanggal = date('Y-m-d', strtotime($tanggal_surat)); 
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $pengirim)) . "_{$tanggal}_{$jam}.pdf";
    
    $target_dir = "../uploads/"; 
    $destination = $target_dir . $nama_file;
    
    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        $query = "INSERT INTO tb_arsip_surat_masuk (tanggal_terima, tanggal_surat, nomor_surat, pengirim, perihal, kode, keterangan, file_surat) 
                  VALUES ('$tanggal_terima', '$tanggal_surat', '$nomor_surat', '$pengirim', '$perihal', '$kode', '$keterangan', '$destination')";

        if (mysqli_query($db, $query)) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='../datasuratmasuk.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datasuratmasuk.php';</script>";
        }
    } else {
        echo "<script>alert('File gagal diupload!'); window.location='../datasuratmasuk.php';</script>";
    }
}


mysqli_close($db);