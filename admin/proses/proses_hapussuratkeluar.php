<?php
// ─────────────────────────────────────────────────────────────────────────────
// proses_hapussuratkeluar.php
// Letakkan file ini di: admin/proses/proses_hapussuratkeluar.php
// ─────────────────────────────────────────────────────────────────────────────
session_start();
include '../../koneksi/koneksi.php';

function redirectAlert($page, $status, $msg = '') {
    $url = $page . '?status=' . $status;
    if ($msg) $url .= '&msg=' . urlencode($msg);
    echo "<script>window.location='" . $url . "';</script>";
    exit;
}

if (!isset($_GET['id'])) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Nomor tidak valid.');
}

$No = mysqli_real_escape_string($db, $_GET['id']);

// Ambil data sebelum dihapus
$query  = "SELECT file_surat, lampiran_absensi, lampiran_notulen, dokumentasi_foto FROM tb_arsip_surat_keluar WHERE No = '$No'";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Data tidak ditemukan.');
}

$data = mysqli_fetch_assoc($result);

// ── Direktori upload (relatif dari lokasi file ini: admin/proses/) ───────────
$dir_surat    = '../uploads/';   // file PDF surat keluar
$dir_lampiran = '../uploads/';   // lampiran absensi, notulen, dokumentasi

// ── Hapus file PDF surat ──────────────────────────────────────────────────────
if (!empty($data['file_surat'])) {
    $path_pdf = $dir_surat . basename($data['file_surat']);
    if (file_exists($path_pdf)) @unlink($path_pdf);
}

// ── Helper: hapus array file dari JSON ───────────────────────────────────────
function hapusFileJson($json_string, $dir) {
    if (empty($json_string)) return;
    $files = json_decode($json_string, true);
    if (!is_array($files)) $files = [$json_string];
    foreach ($files as $file) {
        if (empty($file)) continue;
        $path = $dir . basename($file);
        if (file_exists($path)) @unlink($path);
    }
}

hapusFileJson($data['lampiran_absensi'],   $dir_lampiran);
hapusFileJson($data['lampiran_notulen'],   $dir_lampiran);
hapusFileJson($data['dokumentasi_foto'],   $dir_lampiran);

// ── Hapus dari database ───────────────────────────────────────────────────────
$q_delete = "DELETE FROM tb_arsip_surat_keluar WHERE No = '$No'";

if (!mysqli_query($db, $q_delete)) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Gagal menghapus: ' . mysqli_error($db));
}

// ── Reorder No agar tetap berurutan ──────────────────────────────────────────
mysqli_query($db, "SET @count = 0");
mysqli_query($db, "UPDATE tb_arsip_surat_keluar SET No = (@count := @count + 1) ORDER BY No ASC");
mysqli_query($db, "ALTER TABLE tb_arsip_surat_keluar AUTO_INCREMENT = 1");

mysqli_close($db);

redirectAlert('../datasuratkeluar.php', 'deleted');