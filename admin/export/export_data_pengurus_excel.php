<?php
include '../../koneksi/koneksi.php';

// Set headers for Excel download
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Data_Pengurus.xls");

// Query to get data
$sql = "SELECT * FROM tb_data_pengurus ORDER BY id ASC";
$query = mysqli_query($db, $sql);
?>

<table border="1">
  <thead>
    <tr>
      <th colspan="7">Laporan Data Pengurus</th>
    </tr>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>No. KTP</th>
      <th>Jabatan</th>
      <th>Periode</th>
      <th>Alamat</th>
      <th>No. Telp</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $no = 1;
    while($data = mysqli_fetch_array($query)) { 
    ?>
    <tr>
      <td><?php echo $no++ ?></td>
      <td><?php echo $data['nama']; ?></td>
      <td><?php echo $data['no_ktp']; ?></td>
      <td><?php echo $data['jabatan']; ?></td>
      <td><?php echo $data['periode']; ?></td>
      <td><?php echo $data['alamat']; ?></td>
      <td><?php echo $data['no_telp']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table> 