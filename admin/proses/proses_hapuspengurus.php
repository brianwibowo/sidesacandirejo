<?php
session_start();
include '../../koneksi/koneksi.php';

/**
 * Hapus file fisik dari server.
 * nama_file di DB: 'ktp/file.jpg' atau 'pasfoto/file.jpg'
 * File fisik ada di: /uploads/pengurus/ktp/ atau /uploads/pengurus/pasfoto/
 * Script ini di: /admin/proses/ → naik 2 level ke root.
 */
function hapusFile($nama_file) {
    $nama_file = trim($nama_file);
    if (empty($nama_file)) return;

    $bersih = preg_replace('#^(\.\./)+#', '', $nama_file);
    $candidates = [
        '../../uploads/pengurus/' . $bersih,
        '../uploads/pengurus/'    . $bersih,
        $bersih,
    ];
    foreach ($candidates as $path) {
        if (file_exists($path) && is_file($path)) {
            @unlink($path);
            return;
        }
    }
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak valid!'); window.location='../datapengurus.php';</script>";
    exit;
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location='../datapengurus.php';</script>";
    exit;
}

// Pastikan data ada
$cek = mysqli_query($db, "SELECT id FROM tb_data_pengurus WHERE id = '$id'");
if (!$cek || mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='../datapengurus.php';</script>";
    exit;
}

// Hapus semua foto dari tb_foto_pengurus + file fisiknya
$result_foto = mysqli_query($db, "SELECT nama_file FROM tb_foto_pengurus WHERE id_pengurus = '$id'");
while ($row = mysqli_fetch_assoc($result_foto)) {
    hapusFile($row['nama_file']);
}
mysqli_query($db, "DELETE FROM tb_foto_pengurus WHERE id_pengurus = '$id'");

// Hapus data utama
if (mysqli_query($db, "DELETE FROM tb_data_pengurus WHERE id = '$id'")) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='../datapengurus.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datapengurus.php';</script>";
}

mysqli_close($db);