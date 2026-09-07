<?php
date_default_timezone_set('Asia/Jakarta');

// Deteksi environment apakah Production (sidesacandirejo.com / omahcrypto) atau Localhost
$server_name = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
$is_production = (strpos($server_name, 'sidesacandirejo.com') !== false) 
                 || (strpos($server_name, 'omahcrypto.com') !== false)
                 || (getenv('APP_ENV') === 'production')
                 || (file_exists(__DIR__ . '/.production'));

if ($is_production) {
    // Production Credentials
    $server   = "localhost";
    $username = "omag8228_sidesa_user";
    $password = "brianwibowo123";
    $database = "omag8228_sidesa";
} else {
    // Local / Development Credentials
    $server   = "localhost";
    $username = "root";
    $password = "";
    $database = "omag8228_sidesa";
}

// Koneksi ke database
$db = @mysqli_connect($server, $username, $password, $database);

// Fallback koneksi untuk local jika database name lokal berbeda
if (!$db && !$is_production) {
    $db = @mysqli_connect("localhost", "root", "", "u655368359_db_surat");
}

// Cek koneksi
if (!$db) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Self-healing migration: pastikan kolom catatan ada di tb_booking
try {
    $col_check = mysqli_query($db, "SHOW COLUMNS FROM tb_booking LIKE 'catatan'");
    if ($col_check && mysqli_num_rows($col_check) === 0) {
        mysqli_query($db, "ALTER TABLE tb_booking ADD COLUMN catatan TEXT NULL AFTER local_guide");
    }
} catch (Throwable $e) {
    // Abaikan jika migrasi gagal atau hak akses terbatas
}
?>
