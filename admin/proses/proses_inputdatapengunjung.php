<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convert date format if needed (from YYYY/MM/DD to YYYY-MM-DD)
    $tanggal_kunjungan = str_replace('/', '-', $_POST['tanggal_kunjungan']);
    
    $pilihan_paket_wisata = $_POST['pilihan_paket_wisata'];
    $jenis_wisatawan = $_POST['jenis_wisatawan'];
    $nama = $_POST['nama'];
    $pax = (int)$_POST['pax'];
    
    // Handle optional fields with proper validation
    $kota = ($jenis_wisatawan == 'Domestik' && !empty($_POST['kota'])) ? $_POST['kota'] : null;
    $negara = ($jenis_wisatawan == 'Mancanegara' && !empty($_POST['negara'])) ? $_POST['negara'] : null;
    $agen_wisata = !empty($_POST['agen_wisata']) ? $_POST['agen_wisata'] : null;

    // Handle conditional fields based on package type
    $opsi_makan_tour = null;
    $jenis_makanan_paket = null;
    $opsi_cooking_lesson = null;
    
    // Only set relevant fields based on package type
    if (in_array($pilihan_paket_wisata, ['cycling_tour', 'dokar_tour', 'walking_tour'])) {
        $opsi_makan_tour = !empty($_POST['opsi_makan_tour']) ? $_POST['opsi_makan_tour'] : null;
    }
    
    if ($pilihan_paket_wisata == 'meal_only') {
        $jenis_makanan_paket = !empty($_POST['jenis_makanan_paket']) ? $_POST['jenis_makanan_paket'] : null;
    }
    
    if ($pilihan_paket_wisata == 'cooking_lesson') {
        $opsi_cooking_lesson = !empty($_POST['opsi_cooking_lesson']) ? $_POST['opsi_cooking_lesson'] : null;
    }

    // Validation
    if ($jenis_wisatawan == 'Domestik' && empty($kota)) {
        echo "<script>alert('Kota harus diisi untuk wisatawan domestik!'); window.history.back();</script>";
        exit;
    }
    
    if ($jenis_wisatawan == 'Mancanegara' && empty($negara)) {
        echo "<script>alert('Negara harus diisi untuk wisatawan mancanegara!'); window.history.back();</script>";
        exit;
    }

    // Prepare statement
    $stmt = $db->prepare("INSERT INTO tb_data_pengunjung 
      (tanggal_kunjungan, pilihan_paket_wisata, opsi_makan_tour, jenis_makanan_paket, opsi_cooking_lesson, jenis_wisatawan, kota, negara, nama, pax, agen_wisata) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo "<script>alert('Error preparing statement: " . htmlspecialchars($db->error) . "'); window.location='../datapengunjung.php';</script>";
        exit;
    }

    $stmt->bind_param("sssssssssis", 
      $tanggal_kunjungan, 
      $pilihan_paket_wisata, 
      $opsi_makan_tour, 
      $jenis_makanan_paket, 
      $opsi_cooking_lesson, 
      $jenis_wisatawan, 
      $kota, 
      $negara, 
      $nama, 
      $pax, 
      $agen_wisata
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data pengunjung berhasil disimpan!'); window.location='../datapengunjung.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . htmlspecialchars($stmt->error) . "'); window.location='../datapengunjung.php';</script>";
    }
    
    $stmt->close();
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='../datapengunjung.php';</script>";
}

$db->close();
?>