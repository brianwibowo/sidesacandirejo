<?php
require '../../dompdf/autoload.inc.php';  

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';

$sql = "SELECT * FROM tb_data_mitra ORDER BY id ASC";
$query = mysqli_query($db, $sql);


ob_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Laporan Data Mitra</title>
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
  <h2 class="title">Laporan Data Mitra</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Pemilik</th>
        <th>Nama Usaha</th>
        <th>Kategori Usaha</th>
        <th>Alamat</th>
        <th>Nomor Telepon</th>
        <th>Legalitas Usaha</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; 
      while($data = mysqli_fetch_array($query)) { 
        ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data['nama_pemilik']; ?></td>
        <td><?php echo $data['nama_usaha']; ?></td>
        <td><?php echo $data['kategori_usaha']; ?></td>
        <td><?php echo $data['alamat']; ?></td>
        <td><?php echo $data['nomor_telp']; ?></td>
        <td><?php echo $data['legalitas_usaha']; ?></td>
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
$dompdf->stream("laporan_data_mitra.pdf", ["Attachment" => false]); // Set Attachment to true to force download