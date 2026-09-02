<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request method.'); window.location='../datapengurus.php';</script>";
    exit;
}

$nama    = mysqli_real_escape_string($db, $_POST['nama']);
$no_ktp  = mysqli_real_escape_string($db, $_POST['no_ktp']);
$jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);
$periode = mysqli_real_escape_string($db, $_POST['periode']);
$alamat  = mysqli_real_escape_string($db, $_POST['alamat']);
$no_telp = mysqli_real_escape_string($db, $_POST['no_telp']);

// File sudah terupload via AJAX — tinggal ambil dari hidden field
$file_ktp     = isset($_POST['daftar_ktp_terupload'])     ? trim($_POST['daftar_ktp_terupload'])     : '';
$file_pasfoto = isset($_POST['daftar_pasfoto_terupload']) ? trim($_POST['daftar_pasfoto_terupload']) : '';

// Insert data utama
$query = "INSERT INTO tb_data_pengurus (nama, no_ktp, jabatan, periode, alamat, no_telp)
          VALUES ('$nama', '$no_ktp', '$jabatan', '$periode', '$alamat', '$no_telp')";

if (!mysqli_query($db, $query)) {
    echo "<script>alert('Gagal menyimpan data: " . mysqli_error($db) . "'); window.location='../inputdatapengurus.php';</script>";
    exit;
}

$id_pengurus = mysqli_insert_id($db);

// Insert foto KTP ke tb_foto_pengurus
if (!empty($file_ktp)) {
    $f = mysqli_real_escape_string($db, $file_ktp);
    mysqli_query($db, "INSERT INTO tb_foto_pengurus (id_pengurus, jenis_foto, nama_file) VALUES ('$id_pengurus', 'KTP', '$f')");
}

// Insert pas foto ke tb_foto_pengurus
if (!empty($file_pasfoto)) {
    $f = mysqli_real_escape_string($db, $file_pasfoto);
    mysqli_query($db, "INSERT INTO tb_foto_pengurus (id_pengurus, jenis_foto, nama_file) VALUES ('$id_pengurus', 'Pas Foto', '$f')");
}

echo "<script>alert('Data berhasil ditambahkan!'); window.location='../datapengurus.php';</script>";