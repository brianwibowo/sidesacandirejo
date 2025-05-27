<?php
session_start();
include '../../koneksi/koneksi.php'; // Pastikan koneksi ke database benar

// Ambil data dari database
$sql = "SELECT * FROM tb_data_pengunjung ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Format timestamp untuk nama file
$timestamp = date("Ymd_His"); // Format: YYYYMMDD_HHMMSS
$filename = "Data_Pengunjung_$timestamp.xls"; // Nama file dengan timestamp

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel untuk data Excel
echo "
<center>
        <h1>Data Pengunjung</h1>
    </center>
<table border='1'>
<tr>
    <th>Tanggal Kunjungan</th>
    <th>Pilihan Paket Wisata</th>
    <th>Jenis Wisatawan</th>
    <th>Kota/Negara</th>
    <th>Nama</th>
    <th>Jenis Kelamin</th>
    <th>Usia</th>
    <th>Agen Wisata</th>
</tr>";

while ($data = mysqli_fetch_array($query)) {
    $lokasi = ($data['jenis_wisatawan'] == 'Domestik') ? $data['kota'] : $data['negara'];
    echo "<tr>
        <td>" . htmlspecialchars($data['tanggal_kunjungan']) . "</td>
        <td>" . htmlspecialchars($data['pilihan_paket_wisata']) . "</td>
        <td>" . htmlspecialchars($data['jenis_wisatawan']) . "</td>
        <td>" . htmlspecialchars($lokasi) . "</td>
        <td>" . htmlspecialchars($data['nama']) . "</td>
        <td>" . htmlspecialchars($data['jenis_kelamin']) . "</td>
        <td>" . htmlspecialchars($data['usia']) . "</td>
        <td>" . htmlspecialchars($data['agen_wisata']) . "</td>
    </tr>";
}

echo "</table>";
?>