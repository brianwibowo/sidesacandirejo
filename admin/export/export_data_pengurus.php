<?php
require '../../dompdf/autoload.inc.php';  

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';

$sql = "SELECT * FROM tb_data_pengurus ORDER BY id ASC";
$query = mysqli_query($db, $sql);

ob_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Laporan Data Pengurus</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    font-size: 12px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  table,
  th,
  td {
    border: 1px solid black;
    padding: 5px;
  }

  th {
    background-color: #f2f2f2;
  }

  .title {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
  }
  </style>
</head>

<body>
  <h2 class="title">Laporan Data Pengurus</h2>
  <table>
    <thead>
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
      <?php $no = 1; 
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
</body>

</html>

<?php
$html = ob_get_clean();

// Initialize DomPDF and load the HTML content
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation (optional)
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Output the generated PDF
$dompdf->stream("laporan_data_pengurus.pdf", ["Attachment" => false]); // Set Attachment to true to force download
?> 