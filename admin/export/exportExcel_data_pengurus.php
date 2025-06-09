<?php
session_start();
include '../../koneksi/koneksi.php';

// Ambil data dari database
$sql = "SELECT * FROM pengurus ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Format timestamp untuk nama file
$timestamp = date("Ymd_His"); // Format: YYYYMMDD_HHMMSS
$filename = "Data_Pengurus_$timestamp.xls"; // Nama file dengan timestamp

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel untuk data Excel
echo "
<center>
        <h1>Data Pengurus</h1>
    </center>
<table border='1'>
<tr>
    <th>Nama</th>
    <th>No. KTP</th>
    <th>Jabatan</th>
    <th>Periode</th>
    <th>Alamat</th>
    <th>No. Telp</th>
</tr>";

while ($data = mysqli_fetch_array($query)) {
    echo "<tr>
        <td>" . htmlspecialchars($data['nama']) . "</td>
        <td>" . htmlspecialchars($data['no_ktp']) . "</td>
        <td>" . htmlspecialchars($data['jabatan']) . "</td>
        <td>" . htmlspecialchars($data['periode']) . "</td>
        <td>" . htmlspecialchars($data['alamat']) . "</td>
        <td>" . htmlspecialchars($data['no_telp']) . "</td>
    </tr>";
}

echo "</table>";
?> 