<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../koneksi/koneksi.php';

// Validasi input
if (!isset($_POST['No']) || empty($_POST['No'])) {
    die("<script>alert('Nomor tidak valid'); window.location='../datasuratmasuk.php';</script>");
}

// Ambil data dari form
$No = mysqli_real_escape_string($db, $_POST['No']);
$tanggal_terima = mysqli_real_escape_string($db, $_POST['tanggal_masuk']);
$tanggal_surat = mysqli_real_escape_string($db, $_POST['tanggalsurat_suratmasuk']);
$nomor_surat = mysqli_real_escape_string($db, $_POST['nomor_suratmasuk']);
$pengirim = mysqli_real_escape_string($db, $_POST['pengirim']);
$penerima_surat = mysqli_real_escape_string($db, $_POST['penerima_surat']);
$disposisi = mysqli_real_escape_string($db, $_POST['disposisi']);
$perihal = mysqli_real_escape_string($db, $_POST['perihal']);
$kode = mysqli_real_escape_string($db, $_POST['kode']);
$keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($db, $_POST['keterangan']) : '';

// Variabel untuk menyimpan nama file baru
$nama_file = null;
$query_get_file = "SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No='$No'";
$result = mysqli_query($db, $query_get_file);

if (!$result) {
    die("<script>alert('Error: " . mysqli_error($db) . "'); window.location='../datasuratmasuk.php';</script>");
}

$row = mysqli_fetch_assoc($result);
$file_lama = $row['file_surat'];
$foto_lama = $row['lampiran_foto'];

// Path untuk menyimpan file
$target_dir = "../uploads/";

// Pastikan direktori upload ada
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Cek apakah file baru di-upload
if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == UPLOAD_ERR_OK) {
    // Format tanggal dan jam
    $tanggal = date('Y-m-d', strtotime($tanggal_surat));
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $pengirim)) . "_{$tanggal}_{$jam}.pdf";
    $destination = $target_dir . $nama_file;

    // Memindahkan file ke direktori tujuan
    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        // Hapus file lama jika ada
        if (!empty($file_lama) && file_exists($file_lama)) {
            @unlink($file_lama);
        }
        // Update query dengan file baru
        $query = "UPDATE tb_arsip_surat_masuk SET 
                    tanggal_terima='$tanggal_terima', 
                    tanggal_surat='$tanggal_surat', 
                    nomor_surat='$nomor_surat', 
                    pengirim='$pengirim', 
                    penerima_surat='$penerima_surat',
                    disposisi='$disposisi',
                    perihal='$perihal', 
                    kode='$kode',
                    keterangan='$keterangan',
                    file_surat='$destination' 
                WHERE No='$No'";
    } else {
        die("<script>alert('Gagal memindahkan file surat.'); window.location='../datasuratmasuk.php';</script>");
    }
} else {
    // Jika tidak ada file baru, tetap gunakan file lama
    $query = "UPDATE tb_arsip_surat_masuk SET 
                tanggal_terima='$tanggal_terima', 
                tanggal_surat='$tanggal_surat', 
                nomor_surat='$nomor_surat', 
                pengirim='$pengirim', 
                penerima_surat='$penerima_surat',
                disposisi='$disposisi',
                perihal='$perihal', 
                kode='$kode',
                keterangan='$keterangan'
            WHERE No='$No'";
}

// Handle lampiran foto
if (isset($_FILES['lampiran_foto']) && $_FILES['lampiran_foto']['error'] == UPLOAD_ERR_OK) {
    $tanggal = date('Y-m-d', strtotime($tanggal_surat));
    $jam = date('H-i-s');
    $foto_name = strtolower(str_replace(' ', '_', $pengirim)) . "_foto_{$tanggal}_{$jam}." . pathinfo($_FILES['lampiran_foto']['name'], PATHINFO_EXTENSION);
    $foto_destination = $target_dir . $foto_name;
    
    if (move_uploaded_file($_FILES['lampiran_foto']['tmp_name'], $foto_destination)) {
        // Hapus foto lama jika ada
        if (!empty($foto_lama) && file_exists($foto_lama)) {
            @unlink($foto_lama);
        }
        // Update query dengan foto baru
        $query = "UPDATE tb_arsip_surat_masuk SET 
                    tanggal_terima='$tanggal_terima', 
                    tanggal_surat='$tanggal_surat', 
                    nomor_surat='$nomor_surat', 
                    pengirim='$pengirim', 
                    penerima_surat='$penerima_surat',
                    disposisi='$disposisi',
                    perihal='$perihal', 
                    kode='$kode',
                    keterangan='$keterangan',
                    lampiran_foto='$foto_destination' 
                WHERE No='$No'";
    } else {
        die("<script>alert('Gagal memindahkan file foto.'); window.location='../datasuratmasuk.php';</script>");
    }
}

// Eksekusi query
if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil diedit'); window.location='../datasuratmasuk.php';</script>";
} else {
    die("<script>alert('Error: " . mysqli_error($db) . "'); window.location='../datasuratmasuk.php';</script>");
}

// Tutup koneksi
mysqli_close($db);