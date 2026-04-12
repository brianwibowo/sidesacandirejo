<?php
session_start();
include "../koneksi/koneksi.php";

// Set timezone ke WIB
date_default_timezone_set('Asia/Jakarta');

// Cek semua kemungkinan session key yang dipakai untuk id admin
// Sesuaikan key di bawah dengan yang ada di ceksession.php kamu
$id = null;
if(isset($_SESSION['id_admin'])){
    $id = $_SESSION['id_admin'];
} elseif(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
}

if($id !== null){
    $id  = (int)$id; // casting int untuk keamanan
    $now = date('Y-m-d H:i:s');

    mysqli_query($db, "UPDATE tb_admin SET last_active = '$now' WHERE id_admin = $id");
}
?>