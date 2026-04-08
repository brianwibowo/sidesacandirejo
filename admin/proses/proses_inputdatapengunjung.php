<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $pilihan_paket_wisata = $_POST['pilihan_paket_wisata'];
    $opsi_makan_tour = isset($_POST['opsi_makan_tour']) ? $_POST['opsi_makan_tour'] : null;
    $jenis_makanan_paket = isset($_POST['jenis_makanan_paket']) ? $_POST['jenis_makanan_paket'] : null;
    $opsi_cooking_lesson = isset($_POST['opsi_cooking_lesson']) ? $_POST['opsi_cooking_lesson'] : null;
    $opsi_gamelan = isset($_POST['opsi_gamelan']) ? $_POST['opsi_gamelan'] : null;
    $jenis_wisatawan = $_POST['jenis_wisatawan'];
    $kota = isset($_POST['kota']) ? $_POST['kota'] : null;
    $negara = isset($_POST['negara']) ? $_POST['negara'] : null;
    $nama = $_POST['nama'];
    $pax = $_POST['pax'];
    $agen_wisata = isset($_POST['agen_wisata']) ? $_POST['agen_wisata'] : null;
    $driver_agent_guide = isset($_POST['driver_agent_guide']) ? $_POST['driver_agent_guide'] : null;
    $local_guide = isset($_POST['local_guide']) ? $_POST['local_guide'] : null;

    // Handle multiple file upload
    $foto_list = [];
    if (isset($_FILES['foto']) && is_array($_FILES['foto']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png'];
        
        // Create directory if it doesn't exist
        if (!file_exists('../uploads/pengunjung')) {
            mkdir('../uploads/pengunjung', 0777, true);
        }

        for ($i = 0; $i < count($_FILES['foto']['name']); $i++) {
            if ($_FILES['foto']['error'][$i] == 0) {
                $filename = $_FILES['foto']['name'][$i];
                $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($filetype, $allowed) && $_FILES['foto']['size'][$i] <= 2 * 1024 * 1024) {
                    $new_filename = uniqid() . '_' . $i . '.' . $filetype;
                    $upload_path = '../uploads/pengunjung/' . $new_filename;
                    
                    if (move_uploaded_file($_FILES['foto']['tmp_name'][$i], $upload_path)) {
                        $foto_list[] = $new_filename;
                    }
                }
            }
        }
    }
    $foto = !empty($foto_list) ? json_encode($foto_list) : null;

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
      (tanggal_kunjungan, pilihan_paket_wisata, opsi_makan_tour, jenis_makanan_paket, opsi_cooking_lesson, opsi_gamelan,
      jenis_wisatawan, kota, negara, nama, pax, agen_wisata, driver_agent_guide, local_guide, foto) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo "<script>alert('Error preparing statement: " . htmlspecialchars($db->error) . "'); window.location='../datapengunjung.php';</script>";
        exit;
    }

    $stmt->bind_param("ssssssssssissss", 
        $tanggal_kunjungan, 
        $pilihan_paket_wisata, 
        $opsi_makan_tour, 
        $jenis_makanan_paket, 
        $opsi_cooking_lesson,
        $opsi_gamelan,
        $jenis_wisatawan, 
        $kota, 
        $negara, 
        $nama, 
        $pax, 
        $agen_wisata,
        $driver_agent_guide,
        $local_guide,
        $foto
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