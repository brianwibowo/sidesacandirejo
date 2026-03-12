<?php
session_start();
include "../koneksi/koneksi.php";
include "login/ceksession.php";

// Hanya superadmin yang boleh akses
if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

$id = $_GET['id'] ?? null;

if(!$id){
    $_SESSION['alert'] = ['type'=>'danger','msg'=>'ID admin tidak ditemukan.'];
    header("Location:manajemen_admin.php");
    exit();
}

// Cegah menghapus akun sendiri
if($id == $_SESSION['id']){
    $_SESSION['alert'] = ['type'=>'warning','msg'=>'Anda tidak bisa menghapus akun sendiri.'];
    header("Location:manajemen_admin.php");
    exit();
}

// Ambil nama file gambar admin
$sql = "SELECT gambar, nama_admin FROM tb_admin WHERE id_admin='$id'";
$result = mysqli_query($db, $sql);
if(!$result || mysqli_num_rows($result) == 0){
    $_SESSION['alert'] = ['type'=>'danger','msg'=>'Admin tidak ditemukan.'];
    header("Location:manajemen_admin.php");
    exit();
}
$row = mysqli_fetch_assoc($result);
$gambarFile = $row['gambar'];

// Hapus file gambar jika ada
if($gambarFile && file_exists(__DIR__ . "/images/" . $gambarFile)){
    unlink(__DIR__ . "/images/" . $gambarFile);
}

// Hapus record admin
mysqli_query($db, "DELETE FROM tb_admin WHERE id_admin='$id'");

// Set alert sukses
$_SESSION['alert'] = ['type'=>'success','msg'=>"Admin <strong>{$row['nama_admin']}</strong> berhasil dihapus."];

// Redirect kembali
header("Location:manajemen_admin.php");
exit();
?>