<?php
session_start();
include "../../koneksi/koneksi.php";

// Ambil data username dan password dari form
$username = mysqli_real_escape_string($db, $_POST['username_admin']);
$password = mysqli_real_escape_string($db, sha1($_POST['password']));

$query = mysqli_query($db, "SELECT * FROM tb_admin WHERE username_admin='$username'");
$data = mysqli_fetch_array($query);

if ($data) {
    // Username ada, cek password
    if ($data['password'] == $password) {

        $_SESSION['r3su'] = 'dmn';
        $_SESSION['id'] = $data['id_admin'];
        $_SESSION['username'] = $data['username_admin'];
        $_SESSION['nama'] = $data['nama_admin'];
        $_SESSION['role'] = $data['role'];

        // Tentukan redirect berdasarkan asal tombol
        $from = isset($_SESSION['login_from']) ? $_SESSION['login_from'] : 'arsip';
        unset($_SESSION['login_from']); // bersihkan setelah dipakai

        if ($from === 'booking') {
            header('Location: ../../booking/booking_dashboard.php');
        } else {
            // default: arsip surat
            header('Location: ../');
        }
        exit();

    } else {
        $_SESSION['error'] = "Password salah!";
    }
} else {
    $_SESSION['error'] = "Username tidak terdaftar!";
}

$_SESSION['old_username'] = $username;
header("Location: ../login/");
exit();