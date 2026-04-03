<?php
include '../../koneksi/koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $tgl_keluar = $_POST['tanggal_keluar'];
    $nomor_surat = $_POST['nomor_surat'];
    $penerima = $_POST['penerima'];
    $tempat_acara = $_POST['tempat_acara'];
    $tanggal_kegiatan = isset($_POST['tanggal_kegiatan']) ? trim($_POST['tanggal_kegiatan']) : '';
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
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

    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $penerima)) . "_{$tanggal_keluar_db}_{$jam}.pdf";

    $target_dir = '../uploads/';
    $destination = $target_dir.$nama_file;

    if(move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)){
        // Get the last No value
        $query_last_no = "SELECT MAX(No) as last_no FROM tb_arsip_surat_keluar";
        $result = mysqli_query($db, $query_last_no);
        $row = mysqli_fetch_assoc($result);
        $next_no = ($row['last_no'] ?? 0) + 1;

        $query = "INSERT INTO tb_arsip_surat_keluar (No, tanggal_keluar, nomor_surat, penerima, tempat_acara, tanggal_kegiatan, perihal, kode, keterangan, file_surat) 
              VALUES ('$next_no', '$tanggal_keluar_db', '$nomor_surat', '$penerima', '$tempat_acara', $tanggal_kegiatan_sql, '$perihal', '$kode', '$keterangan', '$destination')";

        if (mysqli_query($db, $query)) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='../datasuratkeluar.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datasuratkeluar.php';</script>";
        }
    } else {
        echo "<script>alert('File gagal diupload!'); window.location='../datasuratkeluar.php';</script>";
    }
    
}