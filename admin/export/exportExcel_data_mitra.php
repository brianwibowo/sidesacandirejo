<?php
session_start();
include '../../koneksi/koneksi.php'; // Pastikan koneksi ke database benar

// Ambil data dari database
$sql = "SELECT * FROM tb_data_mitra ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Format timestamp untuk nama file
$timestamp = date("Ymd_His"); // Format: YYYYMMDD_HHMMSS
$filename = "Data_Mitra_$timestamp.xls"; // Nama file dengan timestamp

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel untuk data Excel
echo "
<center>
        <h1>Data Mitra</h1>
    </center>
<table border='1'>
<tr>
    <th>Nama Pemilik</th>
    <th>Nama Usaha</th>
    <th>Alamat</th>
    <th>Nomor Telepon</th>
    <th>Legalitas Usaha</th>
</tr>";

while ($data = mysqli_fetch_array($query)) {
    echo "<tr>
        <td>" . htmlspecialchars($data['nama_pemilik']) . "</td>
        <td>" . htmlspecialchars($data['nama_usaha']) . "</td>
        <td>" . htmlspecialchars($data['alamat']) . "</td>
        <td>" . htmlspecialchars($data['nomor_telp']) . "</td>
        <td>" . htmlspecialchars($data['legalitas_usaha']) . "</td>
    </tr>";
}

echo "</table>";
?>