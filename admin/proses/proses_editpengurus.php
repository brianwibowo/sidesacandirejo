<?php
session_start();
include '../../koneksi/koneksi.php';

// Get and sanitize POST data
$id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';
$nama = isset($_POST['nama']) ? mysqli_real_escape_string($db, $_POST['nama']) : '';
$no_ktp = isset($_POST['no_ktp']) ? mysqli_real_escape_string($db, $_POST['no_ktp']) : '';
$jabatan = isset($_POST['jabatan']) ? mysqli_real_escape_string($db, $_POST['jabatan']) : '';
$periode = isset($_POST['periode']) ? mysqli_real_escape_string($db, $_POST['periode']) : '';
$alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($db, $_POST['alamat']) : '';
$no_telp = isset($_POST['no_telp']) ? mysqli_real_escape_string($db, $_POST['no_telp']) : '';

// Get current file names from database
$query = "SELECT foto_ktp, pas_foto FROM tb_data_pengurus WHERE id = '$id'";
$result = mysqli_query($db, $query);
$data = mysqli_fetch_assoc($result);

// Initialize file variables with current values
$foto_ktp = $data['foto_ktp'];
$pas_foto = $data['pas_foto'];

// Create upload directory if it doesn't exist
$upload_dir = '../admin/uploads/pengurus/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        echo "<script>alert('Gagal membuat direktori upload!'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
}

// Handle Foto KTP upload
if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] == 0) {
    // Delete old file if exists
    if (!empty($foto_ktp) && file_exists($upload_dir . $foto_ktp)) {
        unlink($upload_dir . $foto_ktp);
    }
    
    // Generate new filename
    $foto_ktp = time() . '_' . basename($_FILES['foto_ktp']['name']);
    $target_file = $upload_dir . $foto_ktp;
    
    // Check file type
    $allowed_types = array('jpg', 'jpeg', 'png');
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
    
    // Check file size (2MB max)
    if ($_FILES['foto_ktp']['size'] > 2000000) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $target_file)) {
        echo "<script>alert('Gagal mengupload Foto KTP! Error: " . error_get_last()['message'] . "'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
}

// Handle Pas Foto upload
if (isset($_FILES['pas_foto']) && $_FILES['pas_foto']['error'] == 0) {
    // Delete old file if exists
    if (!empty($pas_foto) && file_exists($upload_dir . $pas_foto)) {
        unlink($upload_dir . $pas_foto);
    }
    
    // Generate new filename
    $pas_foto = time() . '_' . basename($_FILES['pas_foto']['name']);
    $target_file = $upload_dir . $pas_foto;
    
    // Check file type
    $allowed_types = array('jpg', 'jpeg', 'png');
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
    
    // Check file size (2MB max)
    if ($_FILES['pas_foto']['size'] > 2000000) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['pas_foto']['tmp_name'], $target_file)) {
        echo "<script>alert('Gagal mengupload Pas Foto! Error: " . error_get_last()['message'] . "'); window.location='../editpengurus.php?id=" . $id . "';</script>";
        exit();
    }
}

// Update data in database
$query = "UPDATE tb_data_pengurus SET 
          nama = '$nama',
          no_ktp = '$no_ktp',
          jabatan = '$jabatan',
          periode = '$periode',
          alamat = '$alamat',
          no_telp = '$no_telp',
          foto_ktp = '$foto_ktp',
          pas_foto = '$pas_foto'
          WHERE id = '$id'";

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil diupdate!'); window.location='../datapengurus.php';</script>";
} else {
    echo "<script>alert('Gagal mengupdate data! Error: " . mysqli_error($db) . "'); window.location='../editpengurus.php?id=" . $id . "';</script>";
}
?> 