<?php
// ─────────────────────────────────────────────────────────────────────────────
// proses_hapussuratmasuk.php
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
$data   = mysqli_fetch_assoc($result);

$upload_dir = '../uploads/';

// Hapus file PDF surat
if (!empty($data['file_surat'])) {
    $pdf_path = $data['file_surat']; // path sudah relatif dari proses
    if (file_exists($pdf_path)) @unlink($pdf_path);
    // coba juga path dengan upload_dir prefix
    if (file_exists($upload_dir . basename($pdf_path))) @unlink($upload_dir . basename($pdf_path));
}

// Hapus semua foto lampiran (JSON array atau string lama)
if (!empty($data['lampiran_foto'])) {
    $decoded = json_decode($data['lampiran_foto'], true);
    $fotos   = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
               ? $decoded : [$data['lampiran_foto']];

    foreach ($fotos as $foto) {
        $foto_path = $upload_dir . $foto;
        if (file_exists($foto_path)) @unlink($foto_path);
    }
}

// Hapus dari database
$q_delete = "DELETE FROM tb_arsip_surat_masuk WHERE No = '$No'";

if (!mysqli_query($db, $q_delete)) {
    redirectAlert('../datasuratmasuk.php', 'error', 'Gagal menghapus: ' . mysqli_error($db));
}

// Reorder No agar tetap berurutan
mysqli_query($db, "SET @count = 0");
mysqli_query($db, "UPDATE tb_arsip_surat_masuk SET No = (@count := @count + 1) ORDER BY No ASC");
mysqli_query($db, "ALTER TABLE tb_arsip_surat_masuk AUTO_INCREMENT = 1");

mysqli_close($db);

redirectAlert('../datasuratmasuk.php', 'deleted');