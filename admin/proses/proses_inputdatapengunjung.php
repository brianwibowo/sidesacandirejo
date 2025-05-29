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
    $pax = $_POST['pax'];
    $agen_wisata = isset($_POST['agen_wisata']) ? $_POST['agen_wisata'] : null;
    
    // Ambil opsi tambahan
    $opsi_makan_tour = isset($_POST['opsi_makan_tour']) ? $_POST['opsi_makan_tour'] : null;
    $jenis_makanan = isset($_POST['jenis_makanan']) ? $_POST['jenis_makanan'] : null;
    $opsi_cooking_tour = isset($_POST['opsi_cooking_tour']) ? $_POST['opsi_cooking_tour'] : null;

    // Query dengan semua field yang sesuai dengan struktur database
    $query = "INSERT INTO tb_data_pengunjung (
                tanggal_kunjungan, 
                pilihan_paket_wisata, 
                opsi_makan_tour, 
                jenis_makanan_paket, 
                opsi_cooking_lesson, 
                jenis_wisatawan, 
                kota, 
                negara, 
                nama, 
                pax, 
                agen_wisata
              ) VALUES (
                '$tanggal_kunjungan', 
                '$pilihan_paket_wisata', 
                '$opsi_makan_tour', 
                '$jenis_makanan', 
                '$opsi_cooking_tour', 
                '$jenis_wisatawan', 
                '$kota', 
                '$negara', 
                '$nama', 
                '$pax', 
                '$agen_wisata'
              )";

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