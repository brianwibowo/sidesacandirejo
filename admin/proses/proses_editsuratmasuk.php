<?php
$server   = "localhost";
$username = "root";
$password = "";
$database = "db_surat";

// Koneksi ke database
$koneksi = mysqli_connect($server, $username, $password, $database);

// Ambil data dari form
$id = $_POST['id'];
$tanggal_terima = $_POST['tanggal_masuk'];
$tanggal_surat = $_POST['tanggalsurat_suratmasuk'];
$nomor_surat = $_POST['nomor_suratmasuk'];
$pengirim = $_POST['pengirim'];
$perihal = $_POST['perihal'];
$kode = $_POST['kode'];


// Variabel untuk menyimpan nama file baru
$nama_file = null;

// Cek apakah file baru di-upload
if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == UPLOAD_ERR_OK) {
    // Format tanggal dan jam
    $tanggal = date('Y-m-d', strtotime($tanggal_surat));
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $pengirim)) . "_{$tanggal}_{$jam}.pdf";

    // Path untuk menyimpan file
    $target_dir = "../uploads/";
    $destination = $target_dir . $nama_file;

    // Memindahkan file ke direktori tujuan
    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        // Jika file baru berhasil dipindahkan, update nama file di database
        $query = "UPDATE tb_arsip_surat_masuk SET tanggal_terima='$tanggal_terima', tanggal_surat='$tanggal_surat', nomor_surat='$nomor_surat', pengirim='$pengirim', perihal='$perihal', kode='$kode' , file_path='$destination' WHERE id='$id'";
    } else {
        echo "Gagal memindahkan file.";
        exit;
    }
} else {
    // Jika tidak ada file baru, tetap gunakan file lama
    $query = "UPDATE tb_arsip_surat_masuk SET tanggal_terima='$tanggal_terima', tanggal_surat='$tanggal_surat', nomor_surat='$nomor_surat', pengirim='$pengirim', perihal='$perihal', kode='$kode' WHERE id='$id'";
}

// Eksekusi query
if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Data berhasil diedit'); window.location='../datasuratmasuk.php';</script>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}

// Tutup koneksi
mysqli_close($koneksi);
?>