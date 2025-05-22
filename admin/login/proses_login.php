<?php
session_start();
include "../../koneksi/koneksi.php";

// Ambil data username dan password dari form
$username = mysqli_real_escape_string($db, $_POST['username_admin']);
$password	=	mysqli_real_escape_string($db,sha1($_POST['password'])); 

// Query untuk mendapatkan data user berdasarkan username dan password
$query = mysqli_query($db, "SELECT * FROM tb_admin WHERE username_admin='$username' AND password='$password'");
$jumlah = mysqli_num_rows($query);

if ($jumlah > 0) {
    // Jika username dan password cocok
    $data = mysqli_fetch_array($query);
    echo "Login berhasil!";
    $_SESSION['r3su'] = 'dmn';
    $_SESSION['id'] = $data['id_admin'];
    $_SESSION['username'] = $data['username_admin'];
    $_SESSION['nama'] = $data['nama_admin'];
    header('Location: ../'); // Arahkan ke halaman dashboard atau halaman utama
    exit();
} else {
    echo "<center>Username atau Password anda salah<br><br><h3>Silahkan Ulangi</h3></center>";
    echo "<meta http-equiv='refresh' content='2;url=../login/'>";
}
?>
