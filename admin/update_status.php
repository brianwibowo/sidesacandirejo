<?php
session_start();
include "../koneksi/koneksi.php";

// Set timezone ke WIB
date_default_timezone_set('Asia/Jakarta');

if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];

    // Ambil waktu sekarang sesuai WIB
    $now = date('Y-m-d H:i:s');

    // Update last_active pakai waktu WIB dari PHP
    mysqli_query($db,"UPDATE tb_admin 
        SET last_active = '$now' 
        WHERE id_admin='$id'");
}

// Optional: untuk debug
// echo $_SESSION['id'];
?>