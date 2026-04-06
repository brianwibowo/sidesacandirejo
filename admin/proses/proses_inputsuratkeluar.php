<?php
include '../../koneksi/koneksi.php';

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

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $tgl_keluar = $_POST['tanggal_keluar'];
    $nomor_surat = $_POST['nomor_surat'];
    $penerima = $_POST['penerima'];
    $tempat_acara = $_POST['tempat_acara'];
    $tanggal_kegiatan = isset($_POST['tanggal_kegiatan']) ? trim($_POST['tanggal_kegiatan']) : '';
    $perihal = $_POST['perihal'];
    $keterangan = $_POST['keterangan'];

    $tanggal_keluar_db = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_keluar)));

    // tanggal_kegiatan bersifat optional, simpan NULL jika kosong atau tidak valid.
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

    $target_dir = '../uploads/';
    $nama_file = basename($_FILES['file_surat']['name']);
    $destination = $target_dir . $nama_file;

    // Jika nama file sama sudah ada, timpa agar nama tetap sesuai file upload.
    if (file_exists($destination)) {
        unlink($destination);
    }

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        $lampiran_dir = '../uploads/surat_keluar_lampiran/';
        $absensi_files = uploadMultipleFiles('file_absensi', ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'], $lampiran_dir);
        $notulen_files = uploadMultipleFiles('file_notulen', ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'], $lampiran_dir);
        $dokumentasi_files = uploadMultipleFiles('file_dokumentasi', ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'], $lampiran_dir);

        $absensi_sql = !empty($absensi_files)
            ? "'" . mysqli_real_escape_string($db, json_encode($absensi_files)) . "'"
            : "NULL";
        $notulen_sql = !empty($notulen_files)
            ? "'" . mysqli_real_escape_string($db, json_encode($notulen_files)) . "'"
            : "NULL";
        $dokumentasi_sql = !empty($dokumentasi_files)
            ? "'" . mysqli_real_escape_string($db, json_encode($dokumentasi_files)) . "'"
            : "NULL";

        // Get the last No value
        $query_last_no = "SELECT MAX(No) as last_no FROM tb_arsip_surat_keluar";
        $result = mysqli_query($db, $query_last_no);
        $row = mysqli_fetch_assoc($result);
        $next_no = ($row['last_no'] ?? 0) + 1;

          $query = "INSERT INTO tb_arsip_surat_keluar (No, tanggal_keluar, nomor_surat, penerima, tempat_acara, tanggal_kegiatan, perihal, keterangan, file_surat, lampiran_absensi, lampiran_notulen, dokumentasi_foto) 
              VALUES ('$next_no', '$tanggal_keluar_db', '$nomor_surat', '$penerima', '$tempat_acara', $tanggal_kegiatan_sql, '$perihal', '$keterangan', '$destination', $absensi_sql, $notulen_sql, $dokumentasi_sql)";

        if (mysqli_query($db, $query)) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='../datasuratkeluar.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datasuratkeluar.php';</script>";
        }
    } else {
        echo "<script>alert('File gagal diupload!'); window.location='../datasuratkeluar.php';</script>";
    }
    
}