<?php
// Set header ke JSON karena response kita akan berupa JSON
header('Content-Type: application/json');

// Direktori utama untuk upload file mitra
$base_upload_dir = '../uploads/mitra/';

// Fungsi untuk mengirim response JSON dan menghentikan skrip
function kirim_response($sukses, $pesan, $namaFile = null) {
    echo json_encode([
        'sukses' => $sukses,
        'pesan' => $pesan,
        'namaFile' => $namaFile
    ]);
    exit;
}

// Cek apakah ada file yang di-upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    kirim_response(false, 'Tidak ada file yang diunggah atau terjadi error upload.');
}

$file = $_FILES['file'];
$upload_type = isset($_POST['type']) ? $_POST['type'] : ''; // 'legalitas' atau 'kegiatan'

// Tentukan sub-folder berdasarkan tipe upload
$sub_folder = '';
if ($upload_type === 'legalitas') {
    $sub_folder = 'legalitas/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $max_size = 10 * 1024 * 1024; // 10 MB
} elseif ($upload_type === 'kegiatan') {
    $sub_folder = 'kegiatan/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2 MB
} else {
    kirim_response(false, 'Tipe upload tidak valid.');
}

$target_dir = $base_upload_dir . $sub_folder;

// Buat direktori jika belum ada
if (!is_dir($target_dir) && !mkdir($target_dir, 0777, true)) {
    kirim_response(false, 'Gagal membuat direktori upload.');
}

// Validasi ukuran file
if ($file['size'] > $max_size) {
    kirim_response(false, 'Ukuran file melebihi batas maksimal.');
}

// Validasi tipe file
$file_info = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($file_info, $file['tmp_name']);
finfo_close($file_info);

if (!in_array($mime_type, $allowed_types)) {
    kirim_response(false, 'Tipe file tidak diizinkan.');
}

// Buat nama file yang unik untuk menghindari penimpaan file
$nama_asli = pathinfo($file['name'], PATHINFO_FILENAME);
$ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$nama_file_unik = uniqid($nama_asli . '_', true) . '.' . $ekstensi;
$lokasi_file_final = $target_dir . $nama_file_unik;

// Pindahkan file dari temporary ke lokasi final
if (move_uploaded_file($file['tmp_name'], $lokasi_file_final)) {
    // Jika berhasil, kirim response sukses dengan nama file baru
    kirim_response(true, 'Upload berhasil!', $sub_folder . $nama_file_unik);
} else {
    // Jika gagal, kirim response error
    kirim_response(false, 'Gagal memindahkan file yang diunggah.');
}
?>