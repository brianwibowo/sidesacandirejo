<?php
// File ini hanya untuk menangani upload via AJAX
header('Content-Type: application/json'); // Set header agar respons berupa JSON

$respons = [];

// Cek apakah ada file yang dikirim dengan nama 'foto'
if (isset($_FILES['foto'])) {
    $file = $_FILES['foto'];
    
    $batas_ukuran = 5 * 1024 * 1024; // 5 MB
    $tipe_diizinkan = ['jpg', 'jpeg', 'png', 'heic'];

    $nama_file_asli = $file['name'];
    $ukuran_file = $file['size'];
    $error_file = $file['error'];
    $tmp_file = $file['tmp_name'];
    $tipe_file = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

    // Validasi
    if ($error_file !== UPLOAD_ERR_OK) {
        $respons = ['sukses' => false, 'pesan' => 'Terjadi error saat upload. Kode: ' . $error_file];
    } elseif ($ukuran_file > $batas_ukuran) {
        $respons = ['sukses' => false, 'pesan' => 'File terlalu besar (Maksimal 5MB).'];
    } elseif (!in_array($tipe_file, $tipe_diizinkan)) {
        $respons = ['sukses' => false, 'pesan' => 'Tipe file tidak diizinkan (Hanya JPG, JPEG, PNG, dan HEIC).'];
    } else {
        // Jika lolos validasi, pindahkan file
        $nama_file_baru = uniqid() . '.' . $tipe_file;
        $lokasi_upload = '../uploads/pengunjung/' . $nama_file_baru; // Sesuaikan path ../

        if (move_uploaded_file($tmp_file, $lokasi_upload)) {
            $respons = ['sukses' => true, 'namaFile' => $nama_file_baru];
        } else {
            $respons = ['sukses' => false, 'pesan' => 'Gagal memindahkan file.'];
        }
    }
} else {
    $respons = ['sukses' => false, 'pesan' => 'Tidak ada file yang diterima.'];
}

echo json_encode($respons);
exit();
?>