<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request method.'); window.location='../datamitra.php';</script>";
    exit;
}

$id              = (int) $_POST['id'];
$nama_pemilik    = mysqli_real_escape_string($db, $_POST['nama_pemilik']);
$nama_usaha      = mysqli_real_escape_string($db, $_POST['nama_usaha']);
$kategori_usaha  = mysqli_real_escape_string($db, $_POST['kategori_usaha']);
$alamat          = mysqli_real_escape_string($db, $_POST['alamat']);
$nomor_telp      = mysqli_real_escape_string($db, $_POST['nomor_telp']);
$legalitas_usaha = mysqli_real_escape_string($db, $_POST['legalitas_usaha']);

// Daftar file BARU yang dikirim dari form (sudah terupload via AJAX)
$daftar_legalitas_baru = isset($_POST['daftar_legalitas_terupload']) ? trim($_POST['daftar_legalitas_terupload']) : '';
$daftar_kegiatan_baru  = isset($_POST['daftar_kegiatan_terupload'])  ? trim($_POST['daftar_kegiatan_terupload'])  : '';

// Daftar file LAMA yang dipertahankan (tidak dihapus pengguna)
// Form harus mengirim ini agar file yang tidak dihapus tetap tersimpan
$daftar_legalitas_lama = isset($_POST['daftar_legalitas_existing']) ? trim($_POST['daftar_legalitas_existing']) : '';
$daftar_kegiatan_lama  = isset($_POST['daftar_kegiatan_existing'])  ? trim($_POST['daftar_kegiatan_existing'])  : '';

// Validasi id
if ($id <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location='../datamitra.php';</script>";
    exit;
}

// ----------------------------------------------------------------
// Update data utama tb_data_mitra
// ----------------------------------------------------------------
$query_update = "UPDATE tb_data_mitra SET
    nama_pemilik    = '$nama_pemilik',
    nama_usaha      = '$nama_usaha',
    kategori_usaha  = '$kategori_usaha',
    alamat          = '$alamat',
    nomor_telp      = '$nomor_telp',
    legalitas_usaha = '$legalitas_usaha'
    WHERE id = '$id'";

if (!mysqli_query($db, $query_update)) {
    echo "<script>alert('Gagal mengupdate data: " . mysqli_error($db) . "'); window.location='../datamitra.php';</script>";
    exit;
}

// ----------------------------------------------------------------
// Helper: hapus file fisik dari server
// ----------------------------------------------------------------
function hapusFileFisik($nama_file_db) {
    $nama_file_db = trim($nama_file_db);
    if (empty($nama_file_db)) return;

    $bersih = preg_replace('#^(\.\./)+#', '', $nama_file_db);
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

// ----------------------------------------------------------------
// Kelola tb_foto_legalitas
// ----------------------------------------------------------------

// Gabungkan file lama yang dipertahankan + file baru
$array_legalitas_pertahankan = !empty($daftar_legalitas_lama)
    ? array_filter(array_map('trim', explode(',', $daftar_legalitas_lama)))
    : [];
$array_legalitas_baru = !empty($daftar_legalitas_baru)
    ? array_filter(array_map('trim', explode(',', $daftar_legalitas_baru)))
    : [];

// Ambil semua file legalitas yang ada di DB saat ini
$result_legalitas = mysqli_query($db, "SELECT id_file, nama_file FROM tb_foto_legalitas WHERE id_mitra = '$id'");
$existing_legalitas = [];
while ($row = mysqli_fetch_assoc($result_legalitas)) {
    $existing_legalitas[$row['id_file']] = $row['nama_file'];
}

// Hapus file yang tidak ada di daftar pertahankan
foreach ($existing_legalitas as $id_file => $nama_file) {
    if (!in_array($nama_file, $array_legalitas_pertahankan)) {
        hapusFileFisik($nama_file);
        mysqli_query($db, "DELETE FROM tb_foto_legalitas WHERE id_file = '$id_file'");
    }
}

// Tambah file legalitas baru
foreach ($array_legalitas_baru as $nama_file) {
    $nama_file = mysqli_real_escape_string($db, $nama_file);
    if (!empty($nama_file)) {
        mysqli_query($db, "INSERT INTO tb_foto_legalitas (id_mitra, nama_file) VALUES ('$id', '$nama_file')");
    }
}

// ----------------------------------------------------------------
// Kelola tb_foto_kegiatan
// ----------------------------------------------------------------

$array_kegiatan_pertahankan = !empty($daftar_kegiatan_lama)
    ? array_filter(array_map('trim', explode(',', $daftar_kegiatan_lama)))
    : [];
$array_kegiatan_baru = !empty($daftar_kegiatan_baru)
    ? array_filter(array_map('trim', explode(',', $daftar_kegiatan_baru)))
    : [];

// Ambil semua foto kegiatan yang ada di DB saat ini
$result_kegiatan = mysqli_query($db, "SELECT id_foto, nama_file FROM tb_foto_kegiatan WHERE id_mitra = '$id'");
$existing_kegiatan = [];
while ($row = mysqli_fetch_assoc($result_kegiatan)) {
    $existing_kegiatan[$row['id_foto']] = $row['nama_file'];
}

// Hapus foto yang tidak ada di daftar pertahankan
foreach ($existing_kegiatan as $id_foto => $nama_file) {
    if (!in_array($nama_file, $array_kegiatan_pertahankan)) {
        hapusFileFisik($nama_file);
        mysqli_query($db, "DELETE FROM tb_foto_kegiatan WHERE id_foto = '$id_foto'");
    }
}

// Tambah foto kegiatan baru
foreach ($array_kegiatan_baru as $nama_file) {
    $nama_file = mysqli_real_escape_string($db, $nama_file);
    if (!empty($nama_file)) {
        mysqli_query($db, "INSERT INTO tb_foto_kegiatan (id_mitra, nama_file) VALUES ('$id', '$nama_file')");
    }
}

echo "<script>alert('Data berhasil diupdate'); window.location='../datamitra.php';</script>";