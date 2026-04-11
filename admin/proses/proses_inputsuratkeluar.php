<?php
// ─────────────────────────────────────────────────────────────────────────────
// proses_inputsuratkeluar.php
// ─────────────────────────────────────────────────────────────────────────────
include '../../koneksi/koneksi.php';

// ── Helper: redirect dengan status SweetAlert ─────────────────────────────
function redirectAlert($page, $status, $msg = '')
{
    $url = $page . '?status=' . $status;
    if ($msg) $url .= '&msg=' . urlencode($msg);
    echo "<script>window.location='" . $url . "';</script>";
    exit;
}

// ── Helper: upload banyak file sekaligus ──────────────────────────────────
function uploadMultipleFiles($inputName, $allowedExtensions, $targetDir)
{
    $uploaded = [];

    if (!isset($_FILES[$inputName]) || !is_array($_FILES[$inputName]['name'])) {
        return $uploaded;
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    foreach ($_FILES[$inputName]['name'] as $i => $originalName) {
        $error = $_FILES[$inputName]['error'][$i];
        if ($error === UPLOAD_ERR_NO_FILE) continue;
        if ($error !== UPLOAD_ERR_OK)      continue;

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions, true)) continue;

        // Buat nama unik agar tidak tertimpa file lain
        $uniqueName  = time() . '_' . $i . '_' . basename($originalName);
        $destination = $targetDir . $uniqueName;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'][$i], $destination)) {
            chmod($destination, 0644);
            $uploaded[] = $uniqueName; // simpan hanya nama file
        }
    }

    return $uploaded;
}

// ── Cek method ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectAlert('../datasuratkeluar.php', 'error', 'Akses tidak valid.');
}

// ── Ambil & escape data form ──────────────────────────────────────────────
$tgl_keluar       = mysqli_real_escape_string($db, $_POST['tanggal_keluar']   ?? '');
$nomor_surat      = mysqli_real_escape_string($db, $_POST['nomor_surat']      ?? '');
$penerima         = mysqli_real_escape_string($db, $_POST['penerima']         ?? '');
$tempat_acara     = mysqli_real_escape_string($db, $_POST['tempat_acara']     ?? '');
$tanggal_kegiatan = trim($_POST['tanggal_kegiatan'] ?? '');
$perihal          = mysqli_real_escape_string($db, $_POST['perihal']          ?? '');
$keterangan       = mysqli_real_escape_string($db, $_POST['keterangan']       ?? '');

// ── Format tanggal keluar ─────────────────────────────────────────────────
$ts_keluar = strtotime(str_replace('/', '-', $tgl_keluar));
if (!$ts_keluar) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Format tanggal keluar tidak valid.');
}
$tanggal_keluar_db = date('Y-m-d', $ts_keluar);

// ── Tanggal kegiatan (opsional) ───────────────────────────────────────────
if ($tanggal_kegiatan !== '') {
    $ts_kegiatan = strtotime(str_replace('/', '-', $tanggal_kegiatan));
    $tanggal_kegiatan_sql = $ts_kegiatan
        ? "'" . date('Y-m-d', $ts_kegiatan) . "'"
        : "NULL";
} else {
    $tanggal_kegiatan_sql = "NULL";
}

// ── Upload file surat utama (PDF, wajib) ──────────────────────────────────
if (!isset($_FILES['file_surat']) || $_FILES['file_surat']['error'] !== UPLOAD_ERR_OK) {
    redirectAlert('../datasuratkeluar.php', 'error', 'File surat wajib diupload.');
}
if ($_FILES['file_surat']['size'] > 5 * 1024 * 1024) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Ukuran file surat melebihi 5 MB.');
}

$dir_surat   = '../uploads/';
if (!is_dir($dir_surat)) mkdir($dir_surat, 0755, true);

$nama_dasar  = date('Y-m-d') . '_' . date('H-i-s') . '_' . strtolower(str_replace([' ', '/'], ['_', '-'], $penerima));
$nama_pdf    = $nama_dasar . '.pdf';
$dest_surat  = $dir_surat . $nama_pdf;

if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $dest_surat)) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Gagal mengupload file surat.');
}

// ── Upload lampiran (absensi, notulen, dokumentasi) ───────────────────────
$lampiran_dir      = '../uploads/';
$allowed_files          = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'];
$allowed_foto_only      = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

$absensi_files     = uploadMultipleFiles('file_absensi',     $allowed_files,     $lampiran_dir);
$notulen_files     = uploadMultipleFiles('file_notulen',     $allowed_files,     $lampiran_dir);
$dokumentasi_files = uploadMultipleFiles('file_dokumentasi', $allowed_foto_only, $lampiran_dir);

$absensi_sql     = !empty($absensi_files)
    ? "'" . mysqli_real_escape_string($db, json_encode($absensi_files)) . "'"
    : "NULL";
$notulen_sql     = !empty($notulen_files)
    ? "'" . mysqli_real_escape_string($db, json_encode($notulen_files)) . "'"
    : "NULL";
$dokumentasi_sql = !empty($dokumentasi_files)
    ? "'" . mysqli_real_escape_string($db, json_encode($dokumentasi_files)) . "'"
    : "NULL";

// ── Nomor urut ────────────────────────────────────────────────────────────
$r_last  = mysqli_query($db, "SELECT MAX(No) as last_no FROM tb_arsip_surat_keluar");
$row     = mysqli_fetch_assoc($r_last);
$next_no = ($row['last_no'] ?? 0) + 1;

// Escape nilai yang akan masuk query
$nomor_surat_esc      = mysqli_real_escape_string($db, $nomor_surat);
$penerima_esc         = mysqli_real_escape_string($db, $penerima);
$tempat_acara_esc     = mysqli_real_escape_string($db, $tempat_acara);
$perihal_esc          = mysqli_real_escape_string($db, $perihal);
$keterangan_esc       = mysqli_real_escape_string($db, $keterangan);
$nama_pdf_esc         = mysqli_real_escape_string($db, $nama_pdf);

// ── Insert ke database ────────────────────────────────────────────────────
$query = "INSERT INTO tb_arsip_surat_keluar
            (No, tanggal_keluar, nomor_surat, penerima, tempat_acara,
             tanggal_kegiatan, perihal, keterangan,
             file_surat, lampiran_absensi, lampiran_notulen, dokumentasi_foto)
          VALUES
            ('$next_no', '$tanggal_keluar_db', '$nomor_surat_esc', '$penerima_esc',
             '$tempat_acara_esc', $tanggal_kegiatan_sql, '$perihal_esc', '$keterangan_esc',
             '$nama_pdf_esc', $absensi_sql, $notulen_sql, $dokumentasi_sql)";

if (mysqli_query($db, $query)) {
    redirectAlert('../datasuratkeluar.php', 'success');
} else {
    redirectAlert('../datasuratkeluar.php', 'error', 'Database error: ' . mysqli_error($db));
}

mysqli_close($db);