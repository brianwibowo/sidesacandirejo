<?php
session_start();
include '../../koneksi/koneksi.php';

// Helper function untuk SweetAlert2 response
function swalResponse($title, $message, $icon, $redirect, $iconColor = null) {
    $color = $iconColor ?? ($icon == 'success' ? '#4ade80' : ($icon == 'error' ? '#e74c3c' : '#f39c12'));
    $timerScript = ($icon == 'success') ? '
      didOpen: () => {
        const bar = Swal.getTimerProgressBar();
        if (bar) { bar.style.background = "linear-gradient(90deg,#4ade80,#22d3ee)"; bar.style.height = "5px"; }
      },' : '';
    $confirmBtn = ($icon == 'success') ? 'showConfirmButton: false, timer: 2500, timerProgressBar: true,' : 'confirmButtonText: "Kembali",';
    $willClose = ($icon == 'success') ? 'willClose: () => { window.location.href = "' . $redirect . '"; }' : '';
    $then = ($icon != 'success') ? '.then(() => { window.location.href = "' . $redirect . '"; })' : '';
    
    return '<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Proses Data</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<style>
  body{font-family:"Poppins",sans-serif;background:#f4f6f9;}
  .swal-custom-popup{border-radius:20px!important;padding:30px 20px!important;box-shadow:0 25px 60px rgba(0,0,0,0.25)!important;font-family:"Poppins",sans-serif!important;animation:swalPopIn 0.35s cubic-bezier(0.175,0.885,0.32,1.275)!important;}
  @keyframes swalPopIn{from{transform:scale(0.7);opacity:0;}to{transform:scale(1);opacity:1;}}
  .swal-custom-title{font-family:"Poppins",sans-serif!important;font-weight:700!important;font-size:20px!important;color:#1a1a2e!important;}
</style>
<script>
  Swal.fire({
    title: "' . $title . '",
    html: `<div style="font-family:\'Poppins\',sans-serif;"><p style="color:#555;font-size:15px;margin-bottom:6px;">' . $message . '</p></div>`,
    icon: "' . $icon . '",
    iconColor: "' . $color . '",
    ' . $confirmBtn . '
    background: "#fff",
    color: "#1a1a2e",
    customClass: { popup: "swal-custom-popup", title: "swal-custom-title" },
    ' . $timerScript . '
    ' . $willClose . '
  })' . $then . ';
</script>
</body></html>';
}

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
        echo swalResponse('Validasi Gagal!', 'Kota harus diisi untuk wisatawan domestik!', 'warning', 'javascript:history.back()');
        exit;
    }
    
    if ($jenis_wisatawan == 'Mancanegara' && empty($negara)) {
        echo swalResponse('Validasi Gagal!', 'Negara harus diisi untuk wisatawan mancanegara!', 'warning', 'javascript:history.back()');
        exit;
    }

    // Prepare statement
    $stmt = $db->prepare("INSERT INTO tb_data_pengunjung 
      (tanggal_kunjungan, pilihan_paket_wisata, opsi_makan_tour, jenis_makanan_paket, opsi_cooking_lesson, opsi_gamelan,
      jenis_wisatawan, kota, negara, nama, pax, agen_wisata, driver_agent_guide, local_guide, foto) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo swalResponse('Error!', 'Error preparing statement: ' . htmlspecialchars($db->error), 'error', '../datapengunjung.php');
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
        echo swalResponse('Berhasil Disimpan!', 'Data pengunjung berhasil disimpan. Mengalihkan ke halaman data pengunjung...', 'success', '../datapengunjung.php');
    } else {
        echo swalResponse('Terjadi Kesalahan!', htmlspecialchars($stmt->error), 'error', '../datapengunjung.php');
    }
    
    $stmt->close();
} else {
    echo swalResponse('Permintaan Tidak Valid!', 'Metode permintaan tidak diizinkan.', 'error', '../datapengunjung.php');
}

$db->close();
?>