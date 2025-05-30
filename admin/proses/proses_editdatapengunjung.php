<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convert date format if needed (from YYYY/MM/DD to YYYY-MM-DD)
    $id = (int)$_POST['id'];
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $pilihan_paket_wisata = $_POST['pilihan_paket_wisata'];
    $jenis_wisatawan = $_POST['jenis_wisatawan'];
    $nama = $_POST['nama'];
    $pax = (int)$_POST['pax'];
    
    // Validasi data wajib
    if (empty($id) || empty($tanggal_kunjungan) || empty($pilihan_paket_wisata) || empty($jenis_wisatawan) || empty($nama) || $pax <= 0) {
        echo "<script>alert('Data wajib tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }
    
    // Handle optional fields with proper validation
    $kota = ($_POST['jenis_wisatawan'] == 'Domestik' && isset($_POST['kota']) && !empty($_POST['kota'])) ? $_POST['kota'] : null;
    $negara = ($_POST['jenis_wisatawan'] == 'Mancanegara' && isset($_POST['negara']) && !empty($_POST['negara'])) ? $_POST['negara'] : null;
    $agen_wisata = (isset($_POST['agen_wisata']) && !empty($_POST['agen_wisata'])) ? $_POST['agen_wisata'] : null;

    // Handle conditional fields based on package type
    $opsi_makan_tour = null;
    $jenis_makanan_paket = null;
    $opsi_cooking_lesson = null;
    
    // Only set relevant fields based on package type
    if (in_array($pilihan_paket_wisata, ['cycling_tour', 'dokar_tour', 'walking_tour'])) {
        $opsi_makan_tour = (isset($_POST['opsi_makan_tour']) && !empty($_POST['opsi_makan_tour'])) ? $_POST['opsi_makan_tour'] : null;
    }
    
    if ($pilihan_paket_wisata == 'meal_only') {
        $jenis_makanan_paket = (isset($_POST['jenis_makanan_paket']) && !empty($_POST['jenis_makanan_paket'])) ? $_POST['jenis_makanan_paket'] : null;
    }
    
    if ($pilihan_paket_wisata == 'cooking_lesson') {
        $opsi_cooking_lesson = (isset($_POST['opsi_cooking_lesson']) && !empty($_POST['opsi_cooking_lesson'])) ? $_POST['opsi_cooking_lesson'] : null;
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

    // Cek apakah data dengan ID tersebut ada
    $cek_stmt = $db->prepare("SELECT id FROM tb_data_pengunjung WHERE id = ?");
    $cek_stmt->bind_param("i", $id);
    $cek_stmt->execute();
    $cek_result = $cek_stmt->get_result();
    
    if ($cek_result->num_rows == 0) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='../datapengunjung.php';</script>";
        exit;
    }
    $cek_stmt->close();

    // Prepare statement untuk UPDATE
    $stmt = $db->prepare("UPDATE tb_data_pengunjung SET 
      tanggal_kunjungan = ?, 
      pilihan_paket_wisata = ?, 
      opsi_makan_tour = ?, 
      jenis_makanan_paket = ?, 
      opsi_cooking_lesson = ?, 
      jenis_wisatawan = ?, 
      kota = ?, 
      negara = ?, 
      nama = ?, 
      pax = ?, 
      agen_wisata = ?
      WHERE id = ?");

    if (!$stmt) {
        echo "<script>alert('Error preparing statement: " . htmlspecialchars($db->error) . "'); window.location='../datapengunjung.php';</script>";
        exit;
    }

    $stmt->bind_param("sssssssssisi", 
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
      $agen_wisata,
      $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data pengunjung berhasil diupdate!'); window.location='../datapengunjung.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . htmlspecialchars($stmt->error) . "'); window.location='../datapengunjung.php';</script>";
    }
    
    $stmt->close();
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='../datapengunjung.php';</script>";
}

$db->close();
?>