<?php
session_start();
include "../../koneksi/koneksi.php";       // koneksi database
include "../login/ceksession.php";         // cek session superadmin

// Proteksi halaman hanya superadmin
if($_SESSION['role'] != 'superadmin'){
    header("Location:../index.php");
    exit();
}

// Ambil data dari form
$id_admin = intval($_POST['id_admin']);
$nama_admin = mysqli_real_escape_string($db, $_POST['nama_admin']);
$username_admin = mysqli_real_escape_string($db, $_POST['username_admin']);
$role = $_POST['role'];

// Update database
$update = mysqli_query($db, "UPDATE tb_admin SET 
    nama_admin='$nama_admin', 
    username_admin='$username_admin', 
    role='$role'
    WHERE id_admin='$id_admin'");

// Output styled alert
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Admin</title>
<link href="../../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top:50px;">
    <?php if($update){ ?>
        <div class="alert alert-success text-center" role="alert">
            <strong>Data admin berhasil diperbarui.</strong><br>
            <a href="../manajemen_admin.php" class="btn btn-primary btn-sm" style="margin-top:10px;">Kembali ke Daftar Admin</a>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger text-center" role="alert">
            <strong>Gagal memperbarui data admin!</strong><br>
            <a href="javascript:history.back()" class="btn btn-warning btn-sm" style="margin-top:10px;">Kembali</a>
        </div>
    <?php } ?>
</div>

<script src="../../assets/vendors/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>