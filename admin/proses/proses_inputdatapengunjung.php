<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $pilihan_paket_wisata = $_POST['pilihan_paket_wisata'];
    $jenis_wisatawan = $_POST['jenis_wisatawan'];
    $kota = isset($_POST['kota']) ? $_POST['kota'] : null;
    $negara = isset($_POST['negara']) ? $_POST['negara'] : null;
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $usia = $_POST['usia'];
    $agen_wisata = isset($_POST['agen_wisata']) ? $_POST['agen_wisata'] : null;

    $query = "INSERT INTO tb_data_pengunjung ( tanggal_kunjungan, pilihan_paket_wisata, jenis_wisatawan, kota, negara, nama, jenis_kelamin, usia, agen_wisata) 
              VALUES ('$tanggal_kunjungan', '$pilihan_paket_wisata', '$jenis_wisatawan', '$kota', '$negara', '$nama', '$jenis_kelamin', '$usia', '$agen_wisata')";

    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data pengunjung berhasil disimpan!'); window.location='../datapengunjung.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "'); window.location='../datapengunjung.php';</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='../datapengunjung.php';</script>";
}

mysqli_close($db);
?>