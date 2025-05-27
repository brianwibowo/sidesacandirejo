<?php
include '../../koneksi/koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $tgl_keluar = $_POST['tanggal_keluar'];
    $nomor_surat = $_POST['nomor_surat'];
    $penerima = $_POST['penerima'];
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
    $keterangan = $_POST['keterangan'];

    $tanggal = date('Y-m-d', strtotime($tgl_keluar)); 
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $penerima)) . "_{$tanggal}_{$jam}.pdf";

    $target_dir = '../uploads/';
    $destination = $target_dir.$nama_file;

    if(move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)){
        $query = "INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) 
                  VALUES ('$tgl_keluar', '$nomor_surat', '$penerima', '$perihal', '$kode', '$keterangan', '$destination')";

        if (mysqli_query($db, $query)) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='../datasuratkeluar.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datasuratkeluar.php';</script>";
        }
    } else {
        echo "<script>alert('File gagal diupload!'); window.location='../datasuratkeluar.php';</script>";
    }
    
}