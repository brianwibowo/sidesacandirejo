<?php
session_start();
include "../../koneksi/koneksi.php";
include "../login/ceksession.php";

if($_SESSION['role'] != 'superadmin'){
    header("Location: ../index.php");
    exit();
}

if(!isset($_POST['id_admin'])){
    header("Location: ../manajemen_admin.php");
    exit();
}

$id_admin       = intval($_POST['id_admin']);
$nama_admin     = mysqli_real_escape_string($db, trim($_POST['nama_admin']));
$username_admin = mysqli_real_escape_string($db, trim($_POST['username_admin']));
$role           = mysqli_real_escape_string($db, $_POST['role']);

// Cek username sudah dipakai admin lain
$cek = mysqli_query($db, "SELECT id_admin FROM tb_admin WHERE username_admin='$username_admin' AND id_admin != $id_admin");
if(mysqli_num_rows($cek) > 0){
    $_SESSION['alert'] = ['type'=>'warning', 'msg'=>'Username <strong>'.$username_admin.'</strong> sudah digunakan admin lain.'];
    header("Location: ../edit_admin.php?id=$id_admin");
    exit();
}

$update = mysqli_query($db, "UPDATE tb_admin SET 
    nama_admin     = '$nama_admin', 
    username_admin = '$username_admin', 
    role           = '$role'
    WHERE id_admin = $id_admin");

if($update){
    $_SESSION['alert'] = ['type'=>'success', 'msg'=>"Data admin <strong>$nama_admin</strong> berhasil diperbarui."];
    header("Location: ../manajemen_admin.php");
} else {
    $_SESSION['alert'] = ['type'=>'danger', 'msg'=>'Gagal memperbarui data: ' . mysqli_error($db)];
    header("Location: ../edit_admin.php?id=$id_admin");
}
exit();
?>