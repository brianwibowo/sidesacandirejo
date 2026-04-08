<?php
// ─────────────────────────────────────────────────────────────────────────────
// proses_inputsuratmasuk.php
// ─────────────────────────────────────────────────────────────────────────────
include '../../koneksi/koneksi.php';

// Helper: redirect dengan SweetAlert via GET param
function redirectAlert($page, $status, $msg = '')
{
    $url = $page . '?status=' . $status;
    if ($msg) $url .= '&msg=' . urlencode($msg);
    echo "<script>window.location='" . $url . "';</script>";
    exit;
}

// Helper: konversi gambar ke WebP menggunakan GD
function convertToWebP($sourcePath, $destPath, $quality = 85)
{
    $info = @getimagesize($sourcePath);
    if (!$info) return false;

    switch ($info['mime']) {
        case 'image/jpeg':
            $img = @imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $img = @imagecreatefrompng($sourcePath);
            // Pertahankan transparansi PNG
            if ($img) {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
            }
            break;
        case 'image/gif':
            $img = @imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $img = @imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }
    if (!$img) return false;

    $result = imagewebp($img, $destPath, $quality);
    imagedestroy($img);
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectAlert('../datasuratmasuk.php', 'error', 'Akses tidak valid.');
}

// ── Ambil data form ────────────────────────────────────────────────────────
$tanggal_terima = mysqli_real_escape_string($db, $_POST['tanggal_terima']);
$tanggal_surat  = mysqli_real_escape_string($db, $_POST['tanggal_surat']);
$nomor_surat    = mysqli_real_escape_string($db, $_POST['nomor_surat']);
$pengirim       = mysqli_real_escape_string($db, $_POST['pengirim']);
$penerima_surat = mysqli_real_escape_string($db, $_POST['penerima_surat']);
$disposisi      = mysqli_real_escape_string($db, $_POST['disposisi']);
$perihal        = mysqli_real_escape_string($db, $_POST['perihal']);
$keterangan     = mysqli_real_escape_string($db, $_POST['keterangan'] ?? '');

// ── Direktori upload ───────────────────────────────────────────────────────
$target_dir = "../uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// ── Nama dasar file ────────────────────────────────────────────────────────
$tanggal_fmt   = date('Y-m-d', strtotime($tanggal_surat));
$jam_fmt       = date('H-i-s');
$nama_dasar    = strtolower(str_replace([' ', '/'], ['_', '-'], $pengirim));

// ── Upload file PDF surat ──────────────────────────────────────────────────
if (!isset($_FILES['file_surat']) || $_FILES['file_surat']['error'] !== UPLOAD_ERR_OK) {
    redirectAlert('../datasuratmasuk.php', 'error', 'File surat wajib diupload.');
}

// Validasi ukuran (maks 5 MB)
if ($_FILES['file_surat']['size'] > 5 * 1024 * 1024) {
    redirectAlert('../datasuratmasuk.php', 'error', 'Ukuran file surat melebihi 5 MB.');
}

$nama_file   = "{$nama_dasar}_{$tanggal_fmt}_{$jam_fmt}.pdf";
$destination = $target_dir . $nama_file;

if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
    redirectAlert('../datasuratmasuk.php', 'error', 'Gagal mengupload file surat.');
}

// ── Upload & konversi lampiran foto (multi, opsional) ──────────────────────
$lampiran_foto_arr = [];

if (isset($_FILES['lampiran_foto']) && is_array($_FILES['lampiran_foto']['name'])) {
    $foto_count = count($_FILES['lampiran_foto']['name']);

    for ($i = 0; $i < $foto_count; $i++) {
        // Skip file kosong / tidak ada upload
        if ($_FILES['lampiran_foto']['error'][$i] !== UPLOAD_ERR_OK) continue;

        // Validasi ukuran (maks 2 MB per foto)
        if ($_FILES['lampiran_foto']['size'][$i] > 2 * 1024 * 1024) {
            // Lewati file yang terlalu besar, lanjutkan
            continue;
        }

        $tmp       = $_FILES['lampiran_foto']['tmp_name'][$i];
        $foto_name = "{$nama_dasar}_foto{$i}_{$tanggal_fmt}_{$jam_fmt}.webp";
        $foto_dest = $target_dir . $foto_name;

        if (convertToWebP($tmp, $foto_dest)) {
            $lampiran_foto_arr[] = $foto_name;
        }
        // Hapus tmp file sementara kalau GD sudah selesai
        @unlink($tmp);
    }
}

// Simpan sebagai JSON jika ada foto, null jika tidak
$lampiran_foto_db = !empty($lampiran_foto_arr)
    ? mysqli_real_escape_string($db, json_encode($lampiran_foto_arr))
    : null;

// ── Nomor urut ─────────────────────────────────────────────────────────────
$q_last = "SELECT MAX(No) as last_no FROM tb_arsip_surat_masuk";
$r_last = mysqli_query($db, $q_last);
$row    = mysqli_fetch_assoc($r_last);
$next_no = ($row['last_no'] ?? 0) + 1;

// ── Insert ke database ─────────────────────────────────────────────────────
$foto_val = ($lampiran_foto_db !== null) ? "'$lampiran_foto_db'" : "NULL";

$query = "INSERT INTO tb_arsip_surat_masuk
            (No, tanggal_terima, tanggal_surat, nomor_surat, pengirim, penerima_surat,
             disposisi, perihal, keterangan, file_surat, lampiran_foto)
          VALUES
            ('$next_no', '$tanggal_terima', '$tanggal_surat', '$nomor_surat',
             '$pengirim', '$penerima_surat', '$disposisi', '$perihal',
             '$keterangan', '$destination', $foto_val)";

if (mysqli_query($db, $query)) {
    redirectAlert('../datasuratmasuk.php', 'success');
} else {
    redirectAlert('../datasuratmasuk.php', 'error', 'Database error: ' . mysqli_error($db));
}

mysqli_close($db);
