<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi data dari form
    $id = (int)$_POST['id']; // Cast ke integer untuk keamanan
    $tanggal_kunjungan = mysqli_real_escape_string($db, $_POST['tanggal_kunjungan']);
    $pilihan_paket_wisata = mysqli_real_escape_string($db, $_POST['pilihan_paket_wisata']);
    $jenis_wisatawan = mysqli_real_escape_string($db, $_POST['jenis_wisatawan']);
    $nama = mysqli_real_escape_string($db, $_POST['nama']);
    $pax = (int)$_POST['pax'];
    
    // Validasi data wajib
    if (empty($id) || empty($tanggal_kunjungan) || empty($pilihan_paket_wisata) || empty($jenis_wisatawan) || empty($nama) || $pax <= 0) {
        echo "<script>alert('Data wajib tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }
    
    // Ambil opsi tambahan berdasarkan paket yang dipilih
    $opsi_makan_tour = null;
    $jenis_makanan = null;
    $opsi_cooking_tour = null;
    
    // Untuk paket cycling_tour, dokar_tour, walking_tour
    if (in_array($pilihan_paket_wisata, ['cycling_tour', 'dokar_tour', 'walking_tour'])) {
        $opsi_makan_tour = isset($_POST['opsi_makan_tour']) ? mysqli_real_escape_string($db, $_POST['opsi_makan_tour']) : null;
    }
    
    // Untuk paket meal_only
    if ($pilihan_paket_wisata == 'meal_only') {
        $jenis_makanan = isset($_POST['jenis_makanan']) ? mysqli_real_escape_string($db, $_POST['jenis_makanan']) : null;
        // Validasi jenis makanan wajib untuk meal_only
        if (empty($jenis_makanan)) {
            echo "<script>alert('Jenis makanan wajib dipilih untuk paket Meal Only!'); window.history.back();</script>";
            exit;
        }
    }
    
    // Untuk paket cooking_lesson
    if ($pilihan_paket_wisata == 'cooking_lesson') {
        $opsi_cooking_tour = isset($_POST['opsi_cooking_tour']) ? mysqli_real_escape_string($db, $_POST['opsi_cooking_tour']) : null;
    }
    
    // Handle kota/negara berdasarkan jenis wisatawan
    $kota = null;
    $negara = null;
    
    if ($jenis_wisatawan == 'Domestik') {
        $kota = isset($_POST['kota']) ? mysqli_real_escape_string($db, $_POST['kota']) : null;
        if (empty($kota)) {
            echo "<script>alert('Kota wajib diisi untuk wisatawan domestik!'); window.history.back();</script>";
            exit;
        }
    } elseif ($jenis_wisatawan == 'Mancanegara') {
        $negara = isset($_POST['negara']) ? mysqli_real_escape_string($db, $_POST['negara']) : null;
        if (empty($negara)) {
            echo "<script>alert('Negara wajib diisi untuk wisatawan mancanegara!'); window.history.back();</script>";
            exit;
        }
    }
    
    // Agen wisata (opsional)
    $agen_wisata = isset($_POST['agen_wisata']) && !empty($_POST['agen_wisata']) ? mysqli_real_escape_string($db, $_POST['agen_wisata']) : null;
    
    // Cek apakah data dengan ID tersebut ada
    $cek_query = "SELECT id FROM tb_data_pengunjung WHERE id = '$id'";
    $cek_result = mysqli_query($db, $cek_query);
    
    if (mysqli_num_rows($cek_result) == 0) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='../datapengunjung.php';</script>";
        exit;
    }
    
    // Query UPDATE dengan prepared statement yang lebih aman
    $query = "UPDATE tb_data_pengunjung SET 
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
              WHERE id = ?";
    
    $stmt = mysqli_prepare($db, $query);
    
    if ($stmt) {
        // Bind parameters (s = string, i = integer)
        mysqli_stmt_bind_param($stmt, "ssssssssisii", 
            $tanggal_kunjungan, 
            $pilihan_paket_wisata, 
            $opsi_makan_tour, 
            $jenis_makanan, 
            $opsi_cooking_tour, 
            $jenis_wisatawan, 
            $kota, 
            $negara, 
            $nama, 
            $pax, 
            $agen_wisata,
            $id
        );
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data pengunjung berhasil diupdate!'); window.location='../datapengunjung.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengupdate data: " . mysqli_error($db) . "'); window.history.back();</script>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Terjadi kesalahan dalam mempersiapkan query: " . mysqli_error($db) . "'); window.history.back();</script>";
    }
    
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='../datapengunjung.php';</script>";
}

mysqli_close($db);
?>