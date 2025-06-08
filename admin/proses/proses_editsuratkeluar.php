<?php

include '../../koneksi/koneksi.php';

$No = $_POST['No'];
$tgl_keluar = $_POST['tanggal_keluar'];
$kode = $_POST['kode'];
$nomor_surat = $_POST['nomor_surat'];
$penerima = $_POST['penerima'];
$perihal = $_POST['perihal'];
$keterangan = $_POST['keterangan'];

$query_get_file = "SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = $No";
$result = mysqli_query($db, $query_get_file);
$row = mysqli_fetch_assoc($result);
$file_lama = $row['file_surat'];

// Cek apakah file baru di-upload
if (isset($_FILES['file_surat'])&&$_FILES["file_surat"]["error"] === UPLOAD_ERR_OK) {
    $tanggal = date('Y-m-d', strtotime($tanggal_surat));
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $pengirim)) . "_{$tanggal}_{$jam}.pdf";

    // Path untuk menyimpan file
    $target_dir = "../uploads/";
    $destination = $target_dir . $nama_file;
    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        if (file_exists($file_lama)) {
            unlink($file_lama);
        }
        // Jika file baru berhasil dipindahkan, update nama file di database
        $query = "UPDATE tb_arsip_surat_keluar SET 
                  No = '$No',
                  tanggal_keluar = '$tgl_keluar',
                  nomor_surat = '$nomor_surat',
                  penerima = '$penerima',
                  perihal = '$perihal',
                  kode = '$kode',
                  keterangan = '$keterangan',
                  file_surat = '$destination'
                  WHERE id = '$id'";
    } else {
        echo "Gagal memindahkan file.";
        exit;
    }
} else {
    // Jika tidak ada file baru, tetap gunakan file lama
    $query = "UPDATE tb_arsip_surat_keluar SET tanggal_keluar='$tgl_keluar', nomor_surat='$nomor_surat', penerima='$penerima', perihal='$perihal', kode='$kode' WHERE No='$No'";
}

if (mysqli_query($db, $query)) {
    echo "<script>alert('Data berhasil diedit');; window.location='../datasuratkeluar.php';</script>";
} else {
    echo "Error: " . mysqli_error($db);
}

// Tutup koneksi
mysqli_close($db);