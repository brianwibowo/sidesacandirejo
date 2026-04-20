<?php

include '../../koneksi/koneksi.php';

function ensureColumnExists($db, $tableName, $columnName, $columnDefinition)
{
    $tableEsc = mysqli_real_escape_string($db, $tableName);
    $columnEsc = mysqli_real_escape_string($db, $columnName);

    $check = mysqli_query($db, "SHOW COLUMNS FROM `{$tableEsc}` LIKE '{$columnEsc}'");
    if ($check && mysqli_num_rows($check) === 0) {
        $alter = "ALTER TABLE `{$tableEsc}` ADD COLUMN {$columnDefinition}";
        mysqli_query($db, $alter);
    }
}

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
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            continue;
        }

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions, true)) {
            continue;
        }

        $originalFileName = basename($originalName);
        $destination = $targetDir . $originalFileName;

        // Jika nama sama sudah ada, timpa dengan file terbaru agar nama tetap asli.
        if (file_exists($destination)) {
            unlink($destination);
        }

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'][$i], $destination)) {
            chmod($destination, 0644);
            // Simpan hanya filename, bukan full path
            $uploaded[] = $originalFileName;
        }
    }

    return $uploaded;
}

$id = mysqli_real_escape_string($db, $_POST['id']);
$No = mysqli_real_escape_string($db, $_POST['No']);
$tgl_keluar = $_POST['tanggal_keluar'];
$tanggal_kegiatan = isset($_POST['tanggal_kegiatan']) ? trim($_POST['tanggal_kegiatan']) : '';
$jam_kegiatan = isset($_POST['jam_kegiatan']) ? trim($_POST['jam_kegiatan']) : '';
$nomor_surat = mysqli_real_escape_string($db, $_POST['nomor_surat']);
$penerima = mysqli_real_escape_string($db, $_POST['penerima']);
$jenis_surat_raw = strtolower(trim($_POST['jenis_surat'] ?? 'keterangan'));
$jenis_surat = in_array($jenis_surat_raw, ['keterangan', 'undangan'], true) ? $jenis_surat_raw : 'keterangan';
$tempat_acara = isset($_POST['tempat_acara']) ? trim($_POST['tempat_acara']) : '';
$perihal = mysqli_real_escape_string($db, $_POST['perihal']);
$keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($db, $_POST['keterangan']) : '';

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

$tanggal_keluar_db = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_keluar)));

if ($tanggal_kegiatan !== '') {
    $timestamp_kegiatan = strtotime(str_replace('/', '-', $tanggal_kegiatan));
    if ($timestamp_kegiatan !== false) {
        $tanggal_kegiatan_sql = "'" . date('Y-m-d', $timestamp_kegiatan) . "'";
    } else {
        $tanggal_kegiatan_sql = "NULL";
    }
} else {
    $tanggal_kegiatan_sql = "NULL";
}

$jam_kegiatan_sql = "NULL";
if ($jam_kegiatan !== '') {
    $timestamp_jam = strtotime($jam_kegiatan);
    if ($timestamp_jam !== false) {
        $jam_kegiatan_sql = "'" . date('H:i:s', $timestamp_jam) . "'";
    }
}

if ($tempat_acara !== '') {
    $tempat_acara_sql = "'" . mysqli_real_escape_string($db, $tempat_acara) . "'";
} else {
    $tempat_acara_sql = "NULL";
}

$query_get_file = "SELECT file_surat, lampiran_absensi, lampiran_notulen, dokumentasi_foto FROM tb_arsip_surat_keluar WHERE No = '$id'";
$result = mysqli_query($db, $query_get_file);
$row = mysqli_fetch_assoc($result);
$file_lama = $row['file_surat'];

$existing_absensi = json_decode($row['lampiran_absensi'] ?? '[]', true);
if (!is_array($existing_absensi)) $existing_absensi = [];
$existing_notulen = json_decode($row['lampiran_notulen'] ?? '[]', true);
if (!is_array($existing_notulen)) $existing_notulen = [];
$existing_dokumentasi = json_decode($row['dokumentasi_foto'] ?? '[]', true);
if (!is_array($existing_dokumentasi)) $existing_dokumentasi = [];

$delete_absensi = isset($_POST['delete_absensi']) && is_array($_POST['delete_absensi'])
    ? array_map('basename', $_POST['delete_absensi'])
    : [];
$delete_notulen = isset($_POST['delete_notulen']) && is_array($_POST['delete_notulen'])
    ? array_map('basename', $_POST['delete_notulen'])
    : [];
$delete_dokumentasi = isset($_POST['delete_dokumentasi']) && is_array($_POST['delete_dokumentasi'])
    ? array_map('basename', $_POST['delete_dokumentasi'])
    : [];

$existing_absensi = array_values(array_diff($existing_absensi, $delete_absensi));
$existing_notulen = array_values(array_diff($existing_notulen, $delete_notulen));
$existing_dokumentasi = array_values(array_diff($existing_dokumentasi, $delete_dokumentasi));

$lampiran_dir = '../uploads/';

foreach ($delete_absensi as $filename) {
    $delete_path = $lampiran_dir . $filename;
    if (file_exists($delete_path)) {
        unlink($delete_path);
    }
}
foreach ($delete_notulen as $filename) {
    $delete_path = $lampiran_dir . $filename;
    if (file_exists($delete_path)) {
        unlink($delete_path);
    }
}
foreach ($delete_dokumentasi as $filename) {
    $delete_path = $lampiran_dir . $filename;
    if (file_exists($delete_path)) {
        unlink($delete_path);
    }
}

$new_absensi     = uploadMultipleFiles('file_absensi',     ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'], $lampiran_dir);
$new_notulen     = uploadMultipleFiles('file_notulen',     ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'], $lampiran_dir);
$new_dokumentasi = uploadMultipleFiles('file_dokumentasi', ['jpg', 'jpeg', 'png', 'webp', 'gif'],        $lampiran_dir);

if ($jenis_surat !== 'undangan') {
    $tempat_acara_sql = "NULL";
    $tanggal_kegiatan_sql = "NULL";
    $jam_kegiatan_sql = "NULL";
    $existing_absensi = [];
    $existing_notulen = [];
    $new_absensi = [];
    $new_notulen = [];
}

$absensi_final = array_values(array_unique(array_merge($existing_absensi, $new_absensi)));
$notulen_final = array_values(array_unique(array_merge($existing_notulen, $new_notulen)));
$dokumentasi_final = array_values(array_unique(array_merge($existing_dokumentasi, $new_dokumentasi)));

$absensi_sql = !empty($absensi_final)
    ? "'" . mysqli_real_escape_string($db, json_encode($absensi_final)) . "'"
    : "NULL";
$notulen_sql = !empty($notulen_final)
    ? "'" . mysqli_real_escape_string($db, json_encode($notulen_final)) . "'"
    : "NULL";
$dokumentasi_sql = !empty($dokumentasi_final)
    ? "'" . mysqli_real_escape_string($db, json_encode($dokumentasi_final)) . "'"
    : "NULL";

$has_new_file_surat = isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK;

if (empty($file_lama) && !$has_new_file_surat) {
    echo "<script>alert('File Surat wajib diisi.'); window.history.back();</script>";
    exit;
}

if ($has_new_file_surat) {
    $nama_file = basename($_FILES['file_surat']['name']);

    $target_dir = "../uploads/";
    $destination = $target_dir . $nama_file;

    // Jika nama file tujuan sudah ada, timpa agar nama tetap sama seperti file upload.
    if (file_exists($destination)) {
        unlink($destination);
    }

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        if (!empty($file_lama)) {
            // file_lama bisa berupa path lengkap (lama) atau nama file saja (baru)
            $old_path = (strpos($file_lama, '/') !== false)
                ? $file_lama
                : '../uploads/' . $file_lama;
            if ($old_path !== $destination && file_exists($old_path)) {
                unlink($old_path);
            }
        }

        $query = "UPDATE tb_arsip_surat_keluar SET 
                  No = '$No',
                  tanggal_keluar = '$tanggal_keluar_db',
                  nomor_surat = '$nomor_surat',
                  penerima = '$penerima',
                  jenis_surat = '$jenis_surat',
                  tempat_acara = $tempat_acara_sql,
                  tanggal_kegiatan = $tanggal_kegiatan_sql,
                  jam_kegiatan = $jam_kegiatan_sql,
                  perihal = '$perihal',
                  keterangan = '$keterangan',
                  lampiran_absensi = $absensi_sql,
                  lampiran_notulen = $notulen_sql,
                  dokumentasi_foto = $dokumentasi_sql,
                  file_surat = '$nama_file'
                  WHERE No = '$id'";
    } else {
        echo "Gagal memindahkan file.";
        exit;
    }
} else {
    $file_surat_val = !empty($file_lama)
        ? "'" . mysqli_real_escape_string($db, basename($file_lama)) . "'"
        : "NULL";

    $query = "UPDATE tb_arsip_surat_keluar SET 
              No = '$No',
              tanggal_keluar = '$tanggal_keluar_db',
              nomor_surat = '$nomor_surat',
              penerima = '$penerima',
              jenis_surat = '$jenis_surat',
              tempat_acara=$tempat_acara_sql,
              tanggal_kegiatan=$tanggal_kegiatan_sql,
              jam_kegiatan=$jam_kegiatan_sql,
              perihal = '$perihal',
              keterangan = '$keterangan',
              lampiran_absensi = $absensi_sql,
              lampiran_notulen = $notulen_sql,
              dokumentasi_foto = $dokumentasi_sql,
              file_surat = $file_surat_val
              WHERE No = '$id'";
}

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil diedit'); window.location='../datasuratkeluar.php';</script>";
} else {
    echo "Error: " . mysqli_error($db);
}

mysqli_close($db);