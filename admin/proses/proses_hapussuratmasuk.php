<?php
// ─────────────────────────────────────────────────────────────────────────────
// proses_hapussuratmasuk.php
// Letakkan file ini di: admin/proses/proses_hapussuratmasuk.php
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
    redirectAlert('../datasuratmasuk.php', 'error', 'Nomor tidak valid.');
}

$No = mysqli_real_escape_string($db, $_GET['id']);

// Ambil data sebelum dihapus
$query  = "SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No = '$No'";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    redirectAlert('../datasuratmasuk.php', 'error', 'Data tidak ditemukan.');
}

$data = mysqli_fetch_assoc($result);

// ── Direktori upload (relatif dari lokasi file ini: admin/proses/) ──────────
// File surat PDF  → admin/surat_masuk/<namafile>
// Foto lampiran   → admin/uploads/<namafile>
$dir_upload = '../uploads/';


// ── Hapus file PDF surat ─────────────────────────────────────────────────────
if (!empty($data['file_surat'])) {
    $filename_pdf = basename($data['file_surat']); // amankan: ambil nama file saja
    $path_pdf     = $dir_upload . $filename_pdf;
    if (file_exists($path_pdf)) {
        @unlink($path_pdf);
    }
}

// ── Hapus semua foto lampiran (JSON array atau string lama) ──────────────────
if (!empty($data['lampiran_foto'])) {
    $decoded = json_decode($data['lampiran_foto'], true);
    $fotos   = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
               ? $decoded : [$data['lampiran_foto']];

    foreach ($fotos as $foto) {
        if (empty($foto)) continue;
        $filename_foto = basename($foto); // amankan: ambil nama file saja
        $path_foto     = $dir_upload . $filename_foto;
        if (file_exists($path_foto)) {
            @unlink($path_foto);
        }
    }
}

// ── Hapus dari database ──────────────────────────────────────────────────────
$q_delete = "DELETE FROM tb_arsip_surat_masuk WHERE No = '$No'";

if (!mysqli_query($db, $q_delete)) {
    redirectAlert('../datasuratmasuk.php', 'error', 'Gagal menghapus: ' . mysqli_error($db));
}

// ── Reorder No agar tetap berurutan ─────────────────────────────────────────
mysqli_query($db, "SET @count = 0");
mysqli_query($db, "UPDATE tb_arsip_surat_masuk SET No = (@count := @count + 1) ORDER BY No ASC");
mysqli_query($db, "ALTER TABLE tb_arsip_surat_masuk AUTO_INCREMENT = 1");

mysqli_close($db);

redirectAlert('../datasuratmasuk.php', 'deleted');