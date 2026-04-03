<?php

include '../../koneksi/koneksi.php';

$id = mysqli_real_escape_string($db, $_POST['id']);
$No = mysqli_real_escape_string($db, $_POST['No']);
$tgl_keluar = $_POST['tanggal_keluar'];
$tanggal_kegiatan = isset($_POST['tanggal_kegiatan']) ? trim($_POST['tanggal_kegiatan']) : '';
$kode = mysqli_real_escape_string($db, $_POST['kode']);
$nomor_surat = mysqli_real_escape_string($db, $_POST['nomor_surat']);
$penerima = mysqli_real_escape_string($db, $_POST['penerima']);
$tempat_acara = isset($_POST['tempat_acara']) ? trim($_POST['tempat_acara']) : '';
$perihal = mysqli_real_escape_string($db, $_POST['perihal']);
$keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($db, $_POST['keterangan']) : '';

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

if ($tempat_acara !== '') {
    $tempat_acara_sql = "'" . mysqli_real_escape_string($db, $tempat_acara) . "'";
} else {
    $tempat_acara_sql = "NULL";
}

$query_get_file = "SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = '$id'";
$result = mysqli_query($db, $query_get_file);
$row = mysqli_fetch_assoc($result);
$file_lama = $row['file_surat'];

if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $penerima)) . "_{$tanggal_keluar_db}_{$jam}.pdf";

    $target_dir = "../uploads/";
    $destination = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        if (!empty($file_lama) && file_exists($file_lama)) {
            unlink($file_lama);
        }

        $query = "UPDATE tb_arsip_surat_keluar SET 
                  No = '$No',
                  tanggal_keluar = '$tanggal_keluar_db',
                  nomor_surat = '$nomor_surat',
                  penerima = '$penerima',
                  tempat_acara = $tempat_acara_sql,
                  tanggal_kegiatan = $tanggal_kegiatan_sql,
                  perihal = '$perihal',
                  kode = '$kode',
                  keterangan = '$keterangan',
                  file_surat = '$destination'
                  WHERE No = '$id'";
    } else {
        echo "Gagal memindahkan file.";
        exit;
    }
} else {
    $query = "UPDATE tb_arsip_surat_keluar SET 
              No = '$No',
              tanggal_keluar = '$tanggal_keluar_db',
              nomor_surat = '$nomor_surat',
              penerima = '$penerima',
              tempat_acara=$tempat_acara_sql,
              tanggal_kegiatan=$tanggal_kegiatan_sql,
              perihal = '$perihal',
              kode = '$kode',
              keterangan = '$keterangan'
              WHERE No = '$id'";
}

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil diedit'); window.location='../datasuratkeluar.php';</script>";
} else {
    echo "Error: " . mysqli_error($db);
}

mysqli_close($db);
