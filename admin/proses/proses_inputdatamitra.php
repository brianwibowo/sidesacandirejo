<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request method.'); window.location='../datamitra.php';</script>";
    exit;
}

// Ambil data form
$nama_pemilik    = mysqli_real_escape_string($db, $_POST['nama_pemilik']);
$nama_usaha      = mysqli_real_escape_string($db, $_POST['nama_usaha']);
$kategori_usaha  = mysqli_real_escape_string($db, $_POST['kategori_usaha']);
$alamat          = mysqli_real_escape_string($db, $_POST['alamat']);
$nomor_telp      = mysqli_real_escape_string($db, $_POST['nomor_telp']);
$legalitas_usaha = mysqli_real_escape_string($db, $_POST['legalitas_usaha']);

// Ambil daftar file yang sudah terupload via AJAX
// Format: "legalitas/file1.pdf,legalitas/file2.jpg"
$daftar_legalitas = isset($_POST['daftar_legalitas_terupload']) ? trim($_POST['daftar_legalitas_terupload']) : '';
$daftar_kegiatan  = isset($_POST['daftar_kegiatan_terupload'])  ? trim($_POST['daftar_kegiatan_terupload'])  : '';

// Insert data utama ke tb_data_mitra
$query = "INSERT INTO tb_data_mitra (nama_pemilik, nama_usaha, kategori_usaha, alamat, nomor_telp, legalitas_usaha)
          VALUES ('$nama_pemilik', '$nama_usaha', '$kategori_usaha', '$alamat', '$nomor_telp', '$legalitas_usaha')";

if (!mysqli_query($db, $query)) {
    echo "<script>alert('Gagal menyimpan data: " . mysqli_error($db) . "'); window.location='../inputdatamitra.php';</script>";
    exit;
}

$id_mitra = mysqli_insert_id($db);

// Insert file legalitas ke tb_foto_legalitas
if (!empty($daftar_legalitas)) {
    $array_legalitas = array_filter(explode(',', $daftar_legalitas));
    foreach ($array_legalitas as $nama_file) {
        $nama_file = mysqli_real_escape_string($db, trim($nama_file));
        if (!empty($nama_file)) {
            mysqli_query($db, "INSERT INTO tb_foto_legalitas (id_mitra, nama_file) VALUES ('$id_mitra', '$nama_file')");
        }
    }
}

// Insert foto kegiatan ke tb_foto_kegiatan
if (!empty($daftar_kegiatan)) {
    $array_kegiatan = array_filter(explode(',', $daftar_kegiatan));
    foreach ($array_kegiatan as $nama_file) {
        $nama_file = mysqli_real_escape_string($db, trim($nama_file));
        if (!empty($nama_file)) {
            mysqli_query($db, "INSERT INTO tb_foto_kegiatan (id_mitra, nama_file) VALUES ('$id_mitra', '$nama_file')");
        }
    }
}

echo "<script>alert('Data berhasil ditambahkan'); window.location='../datamitra.php';</script>";