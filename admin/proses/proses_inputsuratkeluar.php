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

function ensureColumnExists($db, $tableName, $columnName, $columnDefinition)
{
    $tableEsc = mysqli_real_escape_string($db, $tableName);
    $columnEsc = mysqli_real_escape_string($db, $columnName);

    $check = mysqli_query($db, "SHOW COLUMNS FROM `{$tableEsc}` LIKE '{$columnEsc}'");
    if ($check && mysqli_num_rows($check) === 0) {
        $alter = "ALTER TABLE `{$tableEsc}` ADD COLUMN {$columnDefinition}";
        if (!mysqli_query($db, $alter)) {
            redirectAlert('../datasuratkeluar.php', 'error', 'Gagal update struktur tabel: ' . mysqli_error($db));
        }
    }
}

// ── Cek method ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectAlert('../datasuratkeluar.php', 'error', 'Akses tidak valid.');
}

// ── Ambil & escape data form ──────────────────────────────────────────────
$tgl_keluar       = mysqli_real_escape_string($db, $_POST['tanggal_keluar']   ?? '');
$nomor_surat      = mysqli_real_escape_string($db, $_POST['nomor_surat']      ?? '');
$penerima         = mysqli_real_escape_string($db, $_POST['penerima']         ?? '');
$jenis_surat_raw  = strtolower(trim($_POST['jenis_surat'] ?? ''));
$jenis_surat      = in_array($jenis_surat_raw, ['keterangan', 'undangan'], true) ? $jenis_surat_raw : 'keterangan';
$tempat_acara     = mysqli_real_escape_string($db, $_POST['tempat_acara']     ?? '');
$tanggal_kegiatan = trim($_POST['tanggal_kegiatan'] ?? '');
$jam_kegiatan     = trim($_POST['jam_kegiatan'] ?? '');
$perihal          = mysqli_real_escape_string($db, $_POST['perihal']          ?? '');
$keterangan       = mysqli_real_escape_string($db, $_POST['keterangan']       ?? '');

if ($jenis_surat !== 'undangan') {
    $tempat_acara = '';
    $tanggal_kegiatan = '';
    $jam_kegiatan = '';
}

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

$jam_kegiatan_sql = "NULL";
if ($jam_kegiatan !== '') {
    $ts_jam = strtotime($jam_kegiatan);
    if ($ts_jam !== false) {
        $jam_kegiatan_sql = "'" . date('H:i:s', $ts_jam) . "'";
    }
}

// ── Upload file surat (PDF / gambar / Word, wajib) ───────────────────────
if (!isset($_FILES['file_surat']) || $_FILES['file_surat']['error'] !== UPLOAD_ERR_OK) {
    redirectAlert('../datasuratkeluar.php', 'error', 'File surat wajib diupload.');
}
if ($_FILES['file_surat']['size'] > 10 * 1024 * 1024) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Ukuran file surat melebihi 10 MB.');
}

// Validasi ekstensi & MIME
$allowed_surat_ext  = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif', 'doc', 'docx'];
$allowed_surat_mime = [
    'application/pdf',
    'image/jpeg', 'image/png', 'image/webp', 'image/gif',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
$orig_ext  = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
$finfo     = finfo_open(FILEINFO_MIME_TYPE);
$real_mime = finfo_file($finfo, $_FILES['file_surat']['tmp_name']);
finfo_close($finfo);

if (!in_array($orig_ext, $allowed_surat_ext, true) || !in_array($real_mime, $allowed_surat_mime, true)) {
    redirectAlert('../datasuratkeluar.php', 'error', 'Format file tidak didukung. Gunakan PDF, gambar (JPG/PNG/WebP/GIF), atau Word (DOC/DOCX).');
}

$dir_surat  = '../uploads/';
if (!is_dir($dir_surat)) mkdir($dir_surat, 0755, true);

$nama_dasar  = date('Y-m-d') . '_' . date('H-i-s') . '_' . strtolower(str_replace([' ', '/'], ['_', '-'], $penerima));
$nama_pdf    = $nama_dasar . '.' . $orig_ext;
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

if ($jenis_surat !== 'undangan') {
    $absensi_files = [];
    $notulen_files = [];
}

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
$jenis_surat_esc      = mysqli_real_escape_string($db, $jenis_surat);
$perihal_esc          = mysqli_real_escape_string($db, $perihal);
$keterangan_esc       = mysqli_real_escape_string($db, $keterangan);
$nama_pdf_esc         = mysqli_real_escape_string($db, $nama_pdf);

ensureColumnExists(
    $db,
    'tb_arsip_surat_keluar',
    'jenis_surat',
    "`jenis_surat` VARCHAR(20) NOT NULL DEFAULT 'keterangan' AFTER `penerima`"
);
ensureColumnExists(
    $db,
    'tb_arsip_surat_keluar',
    'jam_kegiatan',
    "`jam_kegiatan` TIME NULL AFTER `tanggal_kegiatan`"
);

// ── Insert ke database ────────────────────────────────────────────────────
$query = "INSERT INTO tb_arsip_surat_keluar
                        (No, tanggal_keluar, nomor_surat, penerima, jenis_surat, tempat_acara,
                         tanggal_kegiatan, jam_kegiatan, perihal, keterangan,
             file_surat, lampiran_absensi, lampiran_notulen, dokumentasi_foto)
          VALUES
                        ('$next_no', '$tanggal_keluar_db', '$nomor_surat_esc', '$penerima_esc', '$jenis_surat_esc',
                         '$tempat_acara_esc', $tanggal_kegiatan_sql, $jam_kegiatan_sql, '$perihal_esc', '$keterangan_esc',
             '$nama_pdf_esc', $absensi_sql, $notulen_sql, $dokumentasi_sql)";

if (mysqli_query($db, $query)) {
    redirectAlert('../datasuratkeluar.php', 'success');
} else {
    redirectAlert('../datasuratkeluar.php', 'error', 'Database error: ' . mysqli_error($db));
}

mysqli_close($db);