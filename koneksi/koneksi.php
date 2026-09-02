<?php
$server   = "localhost";
$username = "u346430374_sidesacandirej";
$password = "Brianscottkennedy120404";
$database = "u346430374_db_surat";

// Koneksi ke database
$db = mysqli_connect($server, $username, $password, $database);

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
