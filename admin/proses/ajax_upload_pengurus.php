<?php
// Set header ke JSON
header('Content-Type: application/json');

// Direktori utama untuk upload file pengurus
$base_upload_dir = '../uploads/pengurus/';

// Fungsi untuk mengirim response JSON
function kirim_response($sukses, $pesan, $namaFile = null) {
    echo json_encode([
        'sukses' => $sukses,
        'pesan' => $pesan,
        'namaFile' => $namaFile
    ]);
    exit;
}

// Cek file upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    kirim_response(false, 'Tidak ada file yang diunggah atau terjadi error.');
}

$file = $_FILES['file'];
$upload_type = isset($_POST['type']) ? $_POST['type'] : ''; // 'ktp' atau 'pasfoto'

// Tentukan sub-folder dan validasi
$sub_folder = '';
if ($upload_type === 'ktp') {
    $sub_folder = 'ktp/';
} elseif ($upload_type === 'pasfoto') {
    $sub_folder = 'pasfoto/';
} else {
    kirim_response(false, 'Tipe upload tidak valid.');
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 2 * 1024 * 1024; // 2 MB
$target_dir = $base_upload_dir . $sub_folder;

// Buat direktori jika belum ada
if (!is_dir($target_dir) && !mkdir($target_dir, 0777, true)) {
    kirim_response(false, 'Gagal membuat direktori upload.');
}

// Validasi ukuran dan tipe file
if ($file['size'] > $max_size) {
    kirim_response(false, 'Ukuran file melebihi batas maksimal 2MB.');
}
if (!in_array($file['type'], $allowed_types)) {
    kirim_response(false, 'Tipe file tidak diizinkan. Hanya JPEG, PNG, GIF.');
}

// Buat nama file unik
$nama_asli = pathinfo($file['name'], PATHINFO_FILENAME);
$ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$nama_file_unik = uniqid($nama_asli . '_', true) . '.' . $ekstensi;
$lokasi_file_final = $target_dir . $nama_file_unik;

// Pindahkan file
if (move_uploaded_file($file['tmp_name'], $lokasi_file_final)) {
    // Kirim response sukses dengan nama file baru (termasuk subfolder)
    kirim_response(true, 'Upload berhasil!', $sub_folder . $nama_file_unik);
} else {
    kirim_response(false, 'Gagal memindahkan file yang diunggah.');
}
?>