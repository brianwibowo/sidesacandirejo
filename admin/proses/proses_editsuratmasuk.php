<?php
// ─────────────────────────────────────────────────────────────────────────────
// proses_editsuratmasuk.php
// ─────────────────────────────────────────────────────────────────────────────
error_reporting(E_ALL);
ini_set('display_errors', 0);

include '../../koneksi/koneksi.php';

// Helper: redirect dengan SweetAlert via GET param
function redirectAlert($page, $status, $msg = '') {
    $url = $page . '?status=' . $status;
    if ($msg) $url .= '&msg=' . urlencode($msg);
    echo "<script>window.location='" . $url . "';</script>";
    exit;
}

// Helper: konversi gambar ke WebP menggunakan GD
function convertToWebP($sourcePath, $destPath, $quality = 85) {
    $info = @getimagesize($sourcePath);
    if (!$info) return false;

    switch ($info['mime']) {
        case 'image/jpeg':
            $img = @imagecreatefromjpeg($sourcePath); break;
        case 'image/png':
            $img = @imagecreatefrompng($sourcePath);
            if ($img) {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
            }
            break;
        case 'image/gif':
            $img = @imagecreatefromgif($sourcePath); break;
        case 'image/webp':
            $img = @imagecreatefromwebp($sourcePath); break;
        default:
            return false;
    }
    if (!$img) return false;

    $result = imagewebp($img, $destPath, $quality);
    imagedestroy($img);
    return $result;
}

// ── Validasi ───────────────────────────────────────────────────────────────
if (!isset($_POST['No']) || empty($_POST['No'])) {
    redirectAlert('../datasuratmasuk.php', 'error', 'Nomor tidak valid.');
}

$No             = mysqli_real_escape_string($db, $_POST['No']);
$tanggal_terima = mysqli_real_escape_string($db, $_POST['tanggal_masuk']);
$tanggal_surat  = mysqli_real_escape_string($db, $_POST['tanggalsurat_suratmasuk']);
$nomor_surat    = mysqli_real_escape_string($db, $_POST['nomor_suratmasuk']);
$pengirim       = mysqli_real_escape_string($db, $_POST['pengirim']);
$penerima_surat = mysqli_real_escape_string($db, $_POST['penerima_surat']);
$disposisi      = mysqli_real_escape_string($db, $_POST['disposisi']);
$perihal        = mysqli_real_escape_string($db, $_POST['perihal']);
$kode           = mysqli_real_escape_string($db, $_POST['kode']);
$keterangan     = mysqli_real_escape_string($db, $_POST['keterangan'] ?? '');

// ── Ambil data lama dari DB ────────────────────────────────────────────────
$q_get = "SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No='$No'";
$r_get = mysqli_query($db, $q_get);
if (!$r_get) {
    redirectAlert('../datasuratmasuk.php', 'error', mysqli_error($db));
}
$row_db   = mysqli_fetch_assoc($r_get);
$file_lama = $row_db['file_surat'];

// Foto lama: bisa JSON array atau string lama (backward compat)
$foto_lama_all = [];
if (!empty($row_db['lampiran_foto'])) {
    $decoded = json_decode($row_db['lampiran_foto'], true);
    $foto_lama_all = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
        ? $decoded
        : [$row_db['lampiran_foto']];
}

// ── Foto yang dipertahankan user (dikirim via hidden input JSON) ───────────
$foto_retained = [];
if (!empty($_POST['foto_lama_json'])) {
    $decoded = json_decode($_POST['foto_lama_json'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $foto_retained = $decoded;
    }
}

// Hapus foto lama yang sudah tidak dipertahankan
$target_dir = "../uploads/";
foreach ($foto_lama_all as $oldFoto) {
    if (!in_array($oldFoto, $foto_retained)) {
        $fullPath = $target_dir . $oldFoto;
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}

// ── Nama dasar ─────────────────────────────────────────────────────────────
$tanggal_fmt = date('Y-m-d', strtotime($tanggal_surat));
$jam_fmt     = date('H-i-s');
$nama_dasar  = strtolower(str_replace([' ', '/'], ['_', '-'], $pengirim));

// ── Handle file PDF surat ──────────────────────────────────────────────────
$file_surat_db = $file_lama; // default: file lama

if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['file_surat']['size'] > 10 * 1024 * 1024) {
        redirectAlert('../datasuratmasuk.php', 'error', 'Ukuran file surat melebihi 10 MB.');
    }

    $nama_file   = "{$nama_dasar}_{$tanggal_fmt}_{$jam_fmt}.pdf";
    $destination = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        // Hapus file PDF lama
        if (!empty($file_lama) && file_exists($file_lama)) {
            @unlink($file_lama);
        }
        $file_surat_db = $destination;
    } else {
        redirectAlert('../datasuratmasuk.php', 'error', 'Gagal mengupload file surat.');
    }
}

// ── Handle foto baru (multi, opsional) ────────────────────────────────────
$foto_baru_arr = [];

if (isset($_FILES['lampiran_foto']) && is_array($_FILES['lampiran_foto']['name'])) {
    $foto_count = count($_FILES['lampiran_foto']['name']);

    for ($i = 0; $i < $foto_count; $i++) {
        if ($_FILES['lampiran_foto']['error'][$i] !== UPLOAD_ERR_OK) continue;
        if ($_FILES['lampiran_foto']['size'][$i] > 2 * 1024 * 1024) continue;

        $tmp       = $_FILES['lampiran_foto']['tmp_name'][$i];
        $foto_name = "{$nama_dasar}_foto{$i}_{$tanggal_fmt}_{$jam_fmt}.webp";
        $foto_dest = $target_dir . $foto_name;

        if (convertToWebP($tmp, $foto_dest)) {
            $foto_baru_arr[] = $foto_name;
        }
        @unlink($tmp);
    }
}

// Gabungkan: foto yang dipertahankan + foto baru
$foto_final = array_merge($foto_retained, $foto_baru_arr);
$lampiran_foto_db = !empty($foto_final)
    ? mysqli_real_escape_string($db, json_encode($foto_final))
    : null;

$foto_val = ($lampiran_foto_db !== null) ? "'$lampiran_foto_db'" : "NULL";

// ── Update database ────────────────────────────────────────────────────────
$file_surat_esc = mysqli_real_escape_string($db, $file_surat_db);

$query = "UPDATE tb_arsip_surat_masuk SET
            tanggal_terima   = '$tanggal_terima',
            tanggal_surat    = '$tanggal_surat',
            nomor_surat      = '$nomor_surat',
            pengirim         = '$pengirim',
            penerima_surat   = '$penerima_surat',
            disposisi        = '$disposisi',
            perihal          = '$perihal',
            kode             = '$kode',
            keterangan       = '$keterangan',
            file_surat       = '$file_surat_esc',
            lampiran_foto    = $foto_val
          WHERE No = '$No'";

if (mysqli_query($db, $query)) {
    redirectAlert('../datasuratmasuk.php', 'success');
} else {
    redirectAlert('../datasuratmasuk.php', 'error', 'Database error: ' . mysqli_error($db));
}

mysqli_close($db);