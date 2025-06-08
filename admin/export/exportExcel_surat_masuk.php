<?php
// Koneksi ke database
include '../../koneksi/koneksi.php';

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$timestamp = date("Ymd_His"); 
$filename = "Data_Surat_Masuk_$timestamp.xls"; 

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");


// Tampilkan konten
echo '<!DOCTYPE html>
<html>
<head>
    <title>Export Data Ke Excel</title>
    <style type="text/css">
    body {
        font-family: sans-serif;
    }
    table {
        margin: 20px auto;
        border-collapse: collapse;
    }
    table th, table td {
        border: 1px solid #3c3c3c;
        padding: 3px 8px;
    }
    </style>
</head>
<body>
    <center>
        <h1>Data Surat Masuk</h1>
    </center>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Nomor Surat</th>
            <th>Tanggal Terima</th>
            <th>Tanggal Surat</th>
            <th>Pengirim</th>
            <th>Penerima</th>
            <th>Disposisi</th>
            <th>Perihal</th>
            <th>Kode</th>
            <th>Keterangan</th>
        </tr>';

// Ambil data dari tabel
$sql = "SELECT * FROM tb_arsip_surat_masuk ORDER BY No ASC";
$result = mysqli_query($db, $sql);

// Tampilkan data
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . htmlspecialchars($row['No']) . '</td>
                <td>' . htmlspecialchars($row['nomor_surat']) . '</td>
                <td>' . htmlspecialchars($row['tanggal_terima']) . '</td>
                <td>' . htmlspecialchars($row['tanggal_surat']) . '</td>
                <td>' . htmlspecialchars($row['pengirim']) . '</td>
                <td>' . htmlspecialchars($row['penerima_surat']) . '</td>
                <td>' . htmlspecialchars($row['disposisi']) . '</td>
                <td>' . htmlspecialchars($row['perihal']) . '</td>
                <td>' . htmlspecialchars($row['kode']) . '</td>
                <td>' . htmlspecialchars($row['keterangan']) . '</td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="9" style="text-align:center;">Tidak ada data</td></tr>';
}

echo '    </table>
</body>
</html>';

// Menutup d
mysqli_close($db);