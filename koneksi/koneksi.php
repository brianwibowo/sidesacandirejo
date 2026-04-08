<?php
date_default_timezone_set('Asia/Jakarta');

$server   = "localhost";
$username = "root";
$password = "";
$database = "u655368359_db_surat";

// Koneksi ke database
$db = mysqli_connect($server, $username, $password, $database);

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
