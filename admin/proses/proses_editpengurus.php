<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request method.'); window.location='../datapengurus.php';</script>";
    exit;
}

$id      = (int) $_POST['id'];
$nama    = mysqli_real_escape_string($db, $_POST['nama']);
$no_ktp  = mysqli_real_escape_string($db, $_POST['no_ktp']);
$jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);
$periode = mysqli_real_escape_string($db, $_POST['periode']);
$alamat  = mysqli_real_escape_string($db, $_POST['alamat']);
$no_telp = mysqli_real_escape_string($db, $_POST['no_telp']);

// File dari hidden field:
// - daftar_ktp_terupload     = nama file BARU (jika ada upload baru) ATAU file lama jika tidak diganti
// - daftar_pasfoto_terupload = sama
// Logika di editpengurus.php: hidden field diisi nilai lama saat load,
// lalu diganti dengan nilai baru jika ada upload baru.
$file_ktp_baru     = isset($_POST['daftar_ktp_terupload'])     ? trim($_POST['daftar_ktp_terupload'])     : '';
$file_pasfoto_baru = isset($_POST['daftar_pasfoto_terupload']) ? trim($_POST['daftar_pasfoto_terupload']) : '';

if ($id <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location='../datapengurus.php';</script>";
    exit;
}

// Helper hapus file fisik
function hapusFileFisik($nama_file) {
    if (empty(trim($nama_file))) return;
    $bersih = preg_replace('#^(\.\./)+#', '', trim($nama_file));
    $candidates = [
        '../../uploads/pengurus/' . $bersih,
        '../uploads/pengurus/' . $bersih,
        $bersih,
    ];
    foreach ($candidates as $path) {
        if (file_exists($path) && is_file($path)) {
            @unlink($path);
            return;
        }
    }
}

// Update data utama
$query = "UPDATE tb_data_pengurus SET
    nama    = '$nama',
    no_ktp  = '$no_ktp',
    jabatan = '$jabatan',
    periode = '$periode',
    alamat  = '$alamat',
    no_telp = '$no_telp'
    WHERE id = '$id'";

if (!mysqli_query($db, $query)) {
    echo "<script>alert('Gagal update data: " . mysqli_error($db) . "'); window.location='../editpengurus.php?id=$id';</script>";
    exit;
}

// ---- Kelola Foto KTP ----
$row_ktp = mysqli_fetch_assoc(mysqli_query($db, "SELECT id_foto, nama_file FROM tb_foto_pengurus WHERE id_pengurus = '$id' AND jenis_foto = 'KTP' LIMIT 1"));

if (empty($file_ktp_baru)) {
    // Pengguna hapus foto KTP — hapus file fisik + record DB
    if ($row_ktp) {
        hapusFileFisik($row_ktp['nama_file']);
        mysqli_query($db, "DELETE FROM tb_foto_pengurus WHERE id_foto = '" . $row_ktp['id_foto'] . "'");
    }
} else {
    $f = mysqli_real_escape_string($db, $file_ktp_baru);
    if ($row_ktp) {
        // Ada record lama
        if ($row_ktp['nama_file'] !== $file_ktp_baru) {
            // File diganti — hapus file lama, update record
            hapusFileFisik($row_ktp['nama_file']);
            mysqli_query($db, "UPDATE tb_foto_pengurus SET nama_file = '$f' WHERE id_foto = '" . $row_ktp['id_foto'] . "'");
        }
        // Jika sama, tidak perlu melakukan apa-apa
    } else {
        // Belum ada record — insert baru
        mysqli_query($db, "INSERT INTO tb_foto_pengurus (id_pengurus, jenis_foto, nama_file) VALUES ('$id', 'KTP', '$f')");
    }
}

// ---- Kelola Pas Foto ----
$row_pas = mysqli_fetch_assoc(mysqli_query($db, "SELECT id_foto, nama_file FROM tb_foto_pengurus WHERE id_pengurus = '$id' AND jenis_foto = 'Pas Foto' LIMIT 1"));

if (empty($file_pasfoto_baru)) {
    // Pengguna hapus pas foto
    if ($row_pas) {
        hapusFileFisik($row_pas['nama_file']);
        mysqli_query($db, "DELETE FROM tb_foto_pengurus WHERE id_foto = '" . $row_pas['id_foto'] . "'");
    }
} else {
    $f = mysqli_real_escape_string($db, $file_pasfoto_baru);
    if ($row_pas) {
        if ($row_pas['nama_file'] !== $file_pasfoto_baru) {
            hapusFileFisik($row_pas['nama_file']);
            mysqli_query($db, "UPDATE tb_foto_pengurus SET nama_file = '$f' WHERE id_foto = '" . $row_pas['id_foto'] . "'");
        }
    } else {
        mysqli_query($db, "INSERT INTO tb_foto_pengurus (id_pengurus, jenis_foto, nama_file) VALUES ('$id', 'Pas Foto', '$f')");
    }
}

echo "<script>alert('Data berhasil diupdate!'); window.location='../datapengurus.php';</script>";