<?php 
session_start();
include '../../koneksi/koneksi.php';

if (!isset($_GET['id'])) {
    $_SESSION['notif_hapus'] = 'gagal';
    header('Location: ../datapengunjung.php');
    exit;
}

$id = (int)$_GET['id'];

// Ambil data foto sebelum dihapus
$row = mysqli_fetch_assoc(mysqli_query($db, "SELECT foto FROM tb_data_pengunjung WHERE id = $id"));

// Helper hapus file
function hapusFileLokal($nama_file) {
    $path = '../uploads/pengunjung/' . $nama_file;
    if (!empty($nama_file) && file_exists($path)) {
        @unlink($path);
    }
}

// --- Hapus foto sistem BARU (kolom foto JSON di tb_data_pengunjung) ---
if (!empty($row['foto'])) {
    $decoded = json_decode($row['foto'], true);
    if (is_array($decoded)) {
        foreach ($decoded as $f) hapusFileLokal($f);
    } else {
        hapusFileLokal($row['foto']); // string lama
    }
}

// --- Hapus foto sistem LAMA (tb_foto_pengunjung) ---
$res_lama = mysqli_query($db, "SELECT nama_file FROM tb_foto_pengunjung WHERE id_pengunjung = $id");
while ($f = mysqli_fetch_assoc($res_lama)) {
    hapusFileLokal($f['nama_file']);
}
// Hapus record di tabel lama
mysqli_query($db, "DELETE FROM tb_foto_pengunjung WHERE id_pengunjung = $id");

// --- Hapus record utama ---
if (mysqli_query($db, "DELETE FROM tb_data_pengunjung WHERE id = $id")) {
    $_SESSION['notif_hapus'] = 'berhasil';
} else {
    $_SESSION['notif_hapus'] = 'gagal';
}

mysqli_close($db);
header('Location: ../datapengunjung.php');
exit;