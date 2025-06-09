<?php
include '../../koneksi/koneksi.php';

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($db, $_POST['nama']);
    $no_ktp = mysqli_real_escape_string($db, $_POST['no_ktp']);
    $jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);
    $periode = mysqli_real_escape_string($db, $_POST['periode']);
    $alamat = mysqli_real_escape_string($db, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($db, $_POST['no_telp']);

    // Handle file uploads
    $foto_ktp = '';
    $pas_foto = '';

    // Create upload directories if they don't exist
    $upload_dir = '../admin/uploads/pengurus/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle Foto KTP upload
    if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] == 0) {
        $foto_ktp = time() . '_' . $_FILES['foto_ktp']['name'];
        move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $upload_dir . $foto_ktp);
    }

    // Handle Pas Foto upload
    if (isset($_FILES['pas_foto']) && $_FILES['pas_foto']['error'] == 0) {
        $pas_foto = time() . '_' . $_FILES['pas_foto']['name'];
        move_uploaded_file($_FILES['pas_foto']['tmp_name'], $upload_dir . $pas_foto);
    }

    $sql = "INSERT INTO tb_data_pengurus (nama, no_ktp, jabatan, periode, alamat, no_telp, foto_ktp, pas_foto) 
            VALUES ('$nama', '$no_ktp', '$jabatan', '$periode', '$alamat', '$no_telp', '$foto_ktp', '$pas_foto')";

    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='../datapengurus.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($db) . "'); window.location.href='../inputdatapengurus.php';</script>";
    }
} else {
    header("Location: ../inputdatapengurus.php");
}
?> 