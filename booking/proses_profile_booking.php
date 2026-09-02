<?php
session_start();
include '../koneksi/koneksi.php';

// Pastikan login
if (!isset($_SESSION['id'])) {
    header('Location: ../admin/login/index.php');
    exit;
}

$id       = $_SESSION['id'];
$nama     = mysqli_real_escape_string($db, $_POST['nama_admin']);
$username = mysqli_real_escape_string($db, $_POST['username_admin']);

// Ambil data lama
$sql   = "SELECT * FROM tb_admin WHERE id_admin='" . $id . "'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header('Location: booking_profile.php?msg=error&detail=Data+admin+tidak+ditemukan');
    exit;
}

// ── Cek username sudah dipakai admin lain ────────────────────────────────────
$cek = mysqli_query($db, "SELECT id_admin FROM tb_admin WHERE username_admin='" . $username . "' AND id_admin != " . $id);
if (mysqli_num_rows($cek) > 0) {
    header('Location: booking_profile.php?msg=error&detail=Username+sudah+digunakan+admin+lain');
    exit;
}

// ── Path folder gambar ───────────────────────────────────────────────────────
// booking/ ada di root luar, gambar disimpan di admin/images/
$img_dir = "../admin/images/";

// ── Proses foto ──────────────────────────────────────────────────────────────
$gambar_file = $_FILES['gambar']['name'] ?? '';
$nama_gambar = $data['gambar']; // default pakai gambar lama

if ($gambar_file == '') {
    // Tidak upload foto baru — rename file lama sesuai username baru (jika ada)
    if (!empty($data['gambar'])) {
        $ext         = substr($data['gambar'], strripos($data['gambar'], '.'));
        $nama_gambar = $username . $ext;
        $path_lama   = $img_dir . $data['gambar'];
        $path_baru   = $img_dir . $nama_gambar;
        // Rename hanya kalau namanya berubah dan file ada
        if ($data['gambar'] !== $nama_gambar && file_exists($path_lama)) {
            rename($path_lama, $path_baru);
        }
    }
} else {
    // Upload foto baru
    $tipe_file   = $_FILES['gambar']['type'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tipe_ok     = in_array($tipe_file, ['image/jpeg', 'image/jpg', 'image/png']);
    $ukuran_ok   = $ukuran_file <= 2097152; // 2 MB

    if (!$tipe_ok || !$ukuran_ok) {
        $pesan = !$tipe_ok ? 'Format+foto+harus+JPG+atau+PNG' : 'Ukuran+foto+maksimal+2MB';
        header('Location: booking_profile.php?msg=error&detail=' . $pesan);
        exit;
    }

    // Hapus gambar lama
    if (!empty($data['gambar']) && file_exists($img_dir . $data['gambar'])) {
        unlink($img_dir . $data['gambar']);
    }

    $ext_file    = strtolower(substr($gambar_file, strripos($gambar_file, '.')));
    $nama_gambar = $username . $ext_file;
    $tmp_file    = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp_file, $img_dir . $nama_gambar);
}

// ── Query UPDATE ─────────────────────────────────────────────────────────────
$sql_update = "UPDATE tb_admin SET
                nama_admin     = '$nama',
                username_admin = '$username',
                gambar         = '$nama_gambar'
               WHERE id_admin = $id";

$execute = mysqli_query($db, $sql_update);

if ($execute) {
    $_SESSION['nama']     = $nama;
    $_SESSION['username'] = $username;
    header('Location: booking_profile.php?msg=sukses');
} else {
    header('Location: booking_profile.php?msg=error&detail=Gagal+menyimpan+ke+database');
}
exit;
?>