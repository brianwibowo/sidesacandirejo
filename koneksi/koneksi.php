<?php
$server   = "localhost";
$username = "u655368359_sidesacandirej";
$password = "Admindrtpmfeb123";
$database = "u655368359_db_surat";

// Koneksi ke database
$db = mysqli_connect($server, $username, $password, $database);

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>