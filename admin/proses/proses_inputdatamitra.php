<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $nama_pemilik = $_POST['nama_pemilik'];
    $nama_usaha = $_POST['nama_usaha'];
    $legalitas = $_POST['legalitas_usaha'];

    // Initialize the variable for the file path
    
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $nama_usaha)) . "_{$jam}.pdf";

    $target_dir = '../uploads/';
    $destination = $target_dir.$nama_file;

    if(move_uploaded_file($_FILES['bukti_legalitas']['tmp_name'], $destination)){
        $query = "INSERT INTO tb_data_mitra (nama_pemilik, nama_usaha, legalitas_usaha" . 
             ($destination ? ", bukti_legalitas" : "") . 
             ") VALUES ('$nama_pemilik', '$nama_usaha', '$legalitas'" . 
             ($destination ? ", '$destination'" : "") . 
             ")";

        if (mysqli_query($db, $query)) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='../datamitra.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datamitra.php';</script>";
        }
    } else {
        echo "<script>alert('File gagal diupload!'); window.location='../datamitra.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location='../datamitra.php';</script>";
}