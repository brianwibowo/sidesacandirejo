<?php
session_start();
include '../../koneksi/koneksi.php'; // Ensure database connection is correct

// Fetch data from the database
$sql = "SELECT * FROM tb_data_penjualan_usaha ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Format timestamp for the filename
$timestamp = date("Ymd_His"); // Format: YYYYMMDD_HHMMSS
$filename = "Data_Penjualan_Usaha_$timestamp.xls"; // Filename with timestamp

// Set headers for the Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Create the table for the Excel data
echo "
<center>
        <h1>Data Penjualan Usaha</h1>
    </center>
<table border='1'>
<tr>
    <th>Jenis Produk</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Total</th>
</tr>";

// Loop through the fetched data and populate the table
while ($data = mysqli_fetch_array($query)) {
    echo "<tr>
        <td>" . htmlspecialchars($data['produk']) . "</td>
        <td>" . htmlspecialchars($data['jumlah']) . "</td>
        <td>" . htmlspecialchars($data['harga']) . "</td>
        <td>" . htmlspecialchars($data['total']) . "</td>
    </tr>";
}

echo "</table>";
?>