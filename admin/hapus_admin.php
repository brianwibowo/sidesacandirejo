<?php
session_start();
include "../koneksi/koneksi.php";
include "login/ceksession.php";

if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if(!$id){
    $_SESSION['alert'] = ['type'=>'danger','msg'=>'ID admin tidak valid.'];
    header("Location: manajemen_admin.php");
    exit();
}

// -------------------------------------------------------
// Cegah hapus akun sendiri
// Cek semua kemungkinan session key (sesuaikan dengan ceksession.php)
// -------------------------------------------------------
$id_login = null;
if(isset($_SESSION['id_admin']))      $id_login = intval($_SESSION['id_admin']);
elseif(isset($_SESSION['id']))        $id_login = intval($_SESSION['id']);

if($id_login && $id === $id_login){
    $_SESSION['alert'] = ['type'=>'warning','msg'=>'Anda tidak bisa menghapus akun sendiri.'];
    header("Location: manajemen_admin.php");
    exit();
}

// -------------------------------------------------------
// Ambil data admin (nama + gambar)
// -------------------------------------------------------
$result = mysqli_query($db, "SELECT nama_admin, gambar FROM tb_admin WHERE id_admin='$id'");
if(!$result || mysqli_num_rows($result) == 0){
    $_SESSION['alert'] = ['type'=>'danger','msg'=>'Admin tidak ditemukan.'];
    header("Location: manajemen_admin.php");
    exit();
}
$row        = mysqli_fetch_assoc($result);
$namaAdmin  = $row['nama_admin'];
$gambarFile = $row['gambar'];

// -------------------------------------------------------
// Hapus foto profil dari direktori jika ada
// Path: folder yang sama dengan hapus_admin.php → admin/images/
// -------------------------------------------------------
$gambarHapus = false;
if(!empty($gambarFile)){
    // __DIR__ = direktori file hapus_admin.php ini (folder admin/)
    $gambarPath = __DIR__ . "/images/" . $gambarFile;

    if(file_exists($gambarPath)){
        if(unlink($gambarPath)){
            $gambarHapus = true; // berhasil dihapus
        }
        // Jika unlink gagal (permission issue), tetap lanjut hapus data DB
    }
    // Jika file tidak ada di disk (mungkin sudah dihapus manual), tetap lanjut
}

// -------------------------------------------------------
// Hapus record dari database
// -------------------------------------------------------
$hapusDB = mysqli_query($db, "DELETE FROM tb_admin WHERE id_admin='$id'");

if($hapusDB){
    $pesanGambar = '';
    if(!empty($gambarFile)){
        $pesanGambar = $gambarHapus
            ? ' Foto profil juga berhasil dihapus.'
            : ' <small class="text-muted">(Foto profil tidak ditemukan di server atau sudah dihapus sebelumnya.)</small>';
    }
    $_SESSION['alert'] = [
        'type' => 'success',
        'msg'  => "Admin <strong>$namaAdmin</strong> berhasil dihapus.$pesanGambar"
    ];
} else {
    $_SESSION['alert'] = ['type'=>'danger','msg'=>'Gagal menghapus admin: ' . mysqli_error($db)];
}

header("Location: manajemen_admin.php");
exit();
?>