<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convert date format if needed (from YYYY/MM/DD to YYYY-MM-DD)
    $id = (int)$_POST['id'];
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $pilihan_paket_wisata = $_POST['pilihan_paket_wisata'];
    $jenis_wisatawan = $_POST['jenis_wisatawan'];
    $nama = trim($_POST['nama']);
    $pax = (int)$_POST['pax'];
    
    // Validasi data wajib
    if (empty($id) || empty($tanggal_kunjungan) || empty($pilihan_paket_wisata) || empty($jenis_wisatawan) || empty($nama) || $pax <= 0) {
        echo "<script>alert('Data wajib tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }
    
    // Handle optional fields with proper validation
    $kota = ($_POST['jenis_wisatawan'] == 'Domestik' && isset($_POST['kota']) && !empty($_POST['kota'])) ? trim($_POST['kota']) : null;
    $negara = ($_POST['jenis_wisatawan'] == 'Mancanegara' && isset($_POST['negara']) && !empty($_POST['negara'])) ? trim($_POST['negara']) : null;
    $agen_wisata = (isset($_POST['agen_wisata']) && !empty($_POST['agen_wisata'])) ? trim($_POST['agen_wisata']) : null;
    $driver_agent_guide = (isset($_POST['driver_agent_guide']) && !empty($_POST['driver_agent_guide'])) ? trim($_POST['driver_agent_guide']) : null;
    $local_guide = (isset($_POST['local_guide']) && !empty($_POST['local_guide'])) ? trim($_POST['local_guide']) : null;

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

    // Get current foto
    $current_foto = null;
    $stmt = $db->prepare("SELECT foto FROM tb_data_pengunjung WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $current_foto = $row['foto'];
    }
    $stmt->close();

    // Handle file upload
    $foto = $current_foto;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['foto']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            // Check file size (2MB max)
            if ($_FILES['foto']['size'] <= 2 * 1024 * 1024) {
                $new_filename = uniqid() . '.' . $filetype;
                $upload_path = '../uploads/pengunjung/' . $new_filename;
                
                // Debug info
                error_log("Uploading file: " . $filename);
                error_log("New filename: " . $new_filename);
                error_log("Upload path: " . $upload_path);
                
                // Create directory if it doesn't exist
                if (!file_exists('../uploads/pengunjung/')) {
                    mkdir('../uploads/pengunjung', 0777, true);
                    error_log("Created upload directory");
                }
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    error_log("File successfully uploaded to: " . $upload_path);
                    // Delete old foto if exists
                    if ($current_foto && file_exists('../uploads/pengunjung/' . $current_foto)) {
                        unlink('../uploads/pengunjung/' . $current_foto);
                        error_log("Deleted old file: " . $current_foto);
                    }
                    $foto = $new_filename;
                } else {
                    error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
                }
            } else {
                error_log("File too large: " . $_FILES['foto']['size']);
            }
        } else {
            error_log("Invalid file type: " . $filetype);
        }
    } else if (isset($_FILES['foto'])) {
        error_log("File upload error code: " . $_FILES['foto']['error']);
    }

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
      agen_wisata = ?,
      driver_agent_guide = ?,
      local_guide = ?,
      foto = ?
      WHERE id = ?");

    if (!$stmt) {
        echo "<script>alert('Error preparing statement: " . htmlspecialchars($db->error) . "'); window.location='../datapengunjung.php';</script>";
        exit;
    }

    $stmt->bind_param("sssssssssissssi", 
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
      $driver_agent_guide,
      $local_guide,
      $foto,
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