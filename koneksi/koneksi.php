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

// Self-healing database auto-migration & index optimization
// Hanya dicek 1x per proses/session agar tidak membebani server/RAM
static $__db_migration_checked = false;
if (!$__db_migration_checked && (!isset($_SESSION) || !isset($_SESSION['_db_opt_v1']))) {
    $__db_migration_checked = true;
    try {
        // 1. Pastikan kolom catatan ada di tb_booking
        $col_check = mysqli_query($db, "SHOW COLUMNS FROM tb_booking LIKE 'catatan'");
        if ($col_check && mysqli_num_rows($col_check) === 0) {
            @mysqli_query($db, "ALTER TABLE tb_booking ADD COLUMN catatan TEXT NULL AFTER local_guide");
        }

        // 2. Index performa tb_data_pengunjung
        $idx_check = mysqli_query($db, "SHOW INDEX FROM tb_data_pengunjung WHERE Key_name = 'idx_tgl_id'");
        if ($idx_check && mysqli_num_rows($idx_check) === 0) {
            @mysqli_query($db, "ALTER TABLE tb_data_pengunjung ADD INDEX idx_tgl_id (tanggal_kunjungan, id)");
            @mysqli_query($db, "ALTER TABLE tb_data_pengunjung ADD INDEX idx_paket (pilihan_paket_wisata)");
            @mysqli_query($db, "ALTER TABLE tb_data_pengunjung ADD INDEX idx_jenis (jenis_wisatawan)");
            @mysqli_query($db, "ALTER TABLE tb_data_pengunjung ADD INDEX idx_negara (negara)");
        }

        // 3. Composite Index tb_booking
        $idx_b = mysqli_query($db, "SHOW INDEX FROM tb_booking WHERE Key_name = 'idx_status_tgl'");
        if ($idx_b && mysqli_num_rows($idx_b) === 0) {
            @mysqli_query($db, "ALTER TABLE tb_booking ADD INDEX idx_status_tgl (status, tanggal_kunjungan, id)");
        }

        // 4. Index surat keluar
        $idx_sk = mysqli_query($db, "SHOW INDEX FROM tb_arsip_surat_keluar WHERE Key_name = 'idx_sk_tgl'");
        if ($idx_sk && mysqli_num_rows($idx_sk) === 0) {
            @mysqli_query($db, "ALTER TABLE tb_arsip_surat_keluar ADD INDEX idx_sk_tgl (tanggal_keluar)");
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['_db_opt_v1'] = true;
        }
    } catch (Throwable $e) {
        // Lewati jika user database memiliki hak akses terbatas
    }
}
?>
