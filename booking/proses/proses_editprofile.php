<?php
session_start();
include '../../koneksi/koneksi.php';

if (empty($_SESSION['id'])) {
    header("Location: ../booking_dashboard.php");
    exit();
}

$id       = (int)$_SESSION['id'];
$nama     = mysqli_real_escape_string($db, trim($_POST['nama_admin']));
$username = mysqli_real_escape_string($db, trim($_POST['username_admin']));
$gambar   = $_FILES['gambar']['name'] ?? '';

$sql    = "SELECT * FROM tb_admin WHERE id_admin='$id'";
$query  = mysqli_query($db, $sql);
$data   = mysqli_fetch_array($query);

if (!$data) {
    header("Location: ../booking_dashboard.php");
    exit();
}

if (empty($gambar)) {
    // Tidak ganti foto, cek apakah username berganti dan foto lama perlu di-rename
    $nama_b = $data['gambar'];
    if (!empty($data['gambar']) && file_exists("../../admin/images/" . $data['gambar'])) {
        $ext = substr($data['gambar'], strripos($data['gambar'], '.'));
        $nama_b = $username . $ext;
        if ($data['gambar'] !== $nama_b) {
            @rename("../../admin/images/" . $data['gambar'], "../../admin/images/" . $nama_b);
        }
    }

    $update = "UPDATE tb_admin SET 
                nama_admin     = '$nama',
                username_admin = '$username',
                gambar         = '$nama_b' 
               WHERE id_admin  = $id";
    mysqli_query($db, $update);

    $_SESSION['nama']     = $nama;
    $_SESSION['username'] = $username;

    header("Location: ../booking_profile.php?status=sukses");
    exit();
} else {
    // Pengguna mengunggah foto baru
    $tipe_file   = $_FILES['gambar']['type'];
    $ukuran_file = $_FILES['gambar']['size'];
    $allowed     = ['image/jpeg', 'image/jpg', 'image/png'];

    if (in_array(strtolower($tipe_file), $allowed) && $ukuran_file <= 2100000) {
        // Hapus foto lama jika ada
        if (!empty($data['gambar']) && file_exists("../../admin/images/" . $data['gambar'])) {
            @unlink("../../admin/images/" . $data['gambar']);
        }

        $ext_file  = substr($gambar, strripos($gambar, '.'));
        $tmp_file  = $_FILES['gambar']['tmp_name'];
        $nama_baru = $username . '_' . time() . $ext_file;
        $path      = "../../admin/images/" . $nama_baru;

        if (move_uploaded_file($tmp_file, $path)) {
            $update = "UPDATE tb_admin SET 
                        nama_admin     = '$nama',
                        username_admin = '$username',
                        gambar         = '$nama_baru' 
                       WHERE id_admin  = $id";
            mysqli_query($db, $update);

            $_SESSION['nama']     = $nama;
            $_SESSION['username'] = $username;

            header("Location: ../booking_profile.php?status=sukses");
            exit();
        } else {
            header("Location: ../booking_editprofile.php?pesan=gagal_gambar");
            exit();
        }
    } else {
        header("Location: ../booking_editprofile.php?pesan=gagal_gambar");
        exit();
    }
}
?>
