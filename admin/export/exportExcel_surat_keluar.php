<?php
// Mulai sesi
session_start();
include '../../koneksi/koneksi.php'; // Ganti dengan koneksi database Anda

// Ambil data dari database
$sql = "SELECT * FROM tb_arsip_surat_keluar ORDER BY No ASC";
$query = mysqli_query($db, $sql);


$timestamp = date("Ymd_His"); 
$filename = "Data_Surat_Keluar_$timestamp.xls"; 
// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel untuk data Excel
echo "
<center>
        <h1>Data Surat Masuk</h1>
    </center>
<table border='1'>
<tr>
    <th>No</th>
    <th>Nomor Surat</th>
    <th>Tanggal Keluar</th>
    <th>Penerima</th>
    <th>Perihal</th>
    <th>Kode</th>
    <th>Keterangan</th>
</tr>";

while ($data = mysqli_fetch_array($query)) {
    echo "<tr>
        <td>{$data['No']}</td>
        <td>{$data['nomor_surat']}</td>
        <td>{$data['tanggal_keluar']}</td>
        <td>{$data['penerima']}</td>
        <td>{$data['perihal']}</td>
        <td>{$data['kode']}</td>
        <td>{$data['keterangan']}</td>
    </tr>";
}

echo "</table>";