<?php
$server   = "db";
$username = "root";
$password = "root";
$database = "db_surat";

// Koneksi ke database
$db = mysqli_connect($server, $username, $password, $database);

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
