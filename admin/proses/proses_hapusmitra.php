<?php
session_start();
include '../../koneksi/koneksi.php';

/**
 * Hapus file fisik dari server.
 * nama_file di DB sudah berbentuk: 'legalitas/file.pdf' atau 'kegiatan/foto.jpg'
 * File fisik ada di: /uploads/mitra/legalitas/ atau /uploads/mitra/kegiatan/
 * Script ini berada di: /admin/proses/ → naik 2 level ke root.
 */
function hapusFile($nama_file) {
    $nama_file = trim($nama_file);
    if (empty($nama_file)) return;

    $bersih = preg_replace('#^(\.\./)+#', '', $nama_file);

    $candidates = [
        '../../uploads/mitra/' . $bersih,
        '../uploads/mitra/' . $bersih,
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
    echo "<script>alert('ID tidak valid!'); window.location='../datamitra.php';</script>";
    exit;
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location='../datamitra.php';</script>";
    exit;
}

// Pastikan data mitra ada
$result = mysqli_query($db, "SELECT id FROM tb_data_mitra WHERE id = '$id'");
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='../datamitra.php';</script>";
    exit;
}

// ----------------------------------------------------------------
// Hapus file legalitas (dari tabel relasi + file fisik)
// ----------------------------------------------------------------
$result_legalitas = mysqli_query($db, "SELECT nama_file FROM tb_foto_legalitas WHERE id_mitra = '$id'");
while ($row = mysqli_fetch_assoc($result_legalitas)) {
    hapusFile($row['nama_file']);
}
mysqli_query($db, "DELETE FROM tb_foto_legalitas WHERE id_mitra = '$id'");

// ----------------------------------------------------------------
// Hapus foto kegiatan (dari tabel relasi + file fisik)
// ----------------------------------------------------------------
$result_kegiatan = mysqli_query($db, "SELECT nama_file FROM tb_foto_kegiatan WHERE id_mitra = '$id'");
while ($row = mysqli_fetch_assoc($result_kegiatan)) {
    hapusFile($row['nama_file']);
}
mysqli_query($db, "DELETE FROM tb_foto_kegiatan WHERE id_mitra = '$id'");

// ----------------------------------------------------------------
// Hapus record utama dari tb_data_mitra
// ----------------------------------------------------------------
if (mysqli_query($db, "DELETE FROM tb_data_mitra WHERE id = '$id'")) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='../datamitra.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data! Error: " . mysqli_error($db) . "'); window.location='../datamitra.php';</script>";
}

mysqli_close($db);